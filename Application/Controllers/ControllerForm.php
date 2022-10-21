<?php

use Application\Core\ControllerSecuredAdmin;
use Application\Core\Helper;
use Application\Models\Partners;
use Application\Models\PartnersServices;
use Application\Models\Structures;
use Application\Models\StructuresServices;
use Application\Models\User;
use Application\Models\UserConfirmModel;

/**
 * ControllerForm, il est uniquement accessible à la personne aillant un status Admin
 * Ainsi impossible pour un utilisateur classique d'accédé aux fonctions des formulaires
 * Même avec les liens.
 */
class ControllerForm extends ControllerSecuredAdmin {

    private User $user;
    private Partners $partner;
    private Structures $structure;
    private StructuresServices $structurerService;
    private PartnersServices $partnerService;

    private string $form_error;
    private string $form_success;

    public function __construct() {
        $this->structure = new Structures();
        $this->partnerService = new PartnersServices();
        $this->structurerService = new StructuresServices();
        $this->partner = new Partners();
        $this->user = new User();

        $this->form_error = '';
        $this->form_success = '';
    }

    /**
     * Page /form (redirection)
     */
    public function index() {$this->redirect('/');}

    /**
     * Permet de faire certain test plus simplement sur les formulaires soumis en faisant des tests générique
     */
    public function checkForm(string $parameter, $type, $valueMinLength = null, $valueMaxLength = null) : bool{
        if($type === 'strlen') {
            return $this->request->existParameter($parameter) && strlen($this->request->getParameter($parameter)) >= $valueMinLength && (!($valueMaxLength !== null) || strlen($this->request->getParameter($parameter)) <= $valueMaxLength);
        }
        else if($type === 'is_numeric') {
            return $this->request->existParameter($parameter) && is_numeric($this->request->getParameter($parameter)) && (!($valueMinLength !== null) || strlen($this->request->getParameter($parameter)) >= $valueMinLength) && (!($valueMaxLength !== null) || strlen($this->request->getParameter($parameter)) <= $valueMaxLength);
        }
        else if($type === 'mail') {
            return $this->request->existParameter($parameter) && Helper::validMail($this->request->getParameter($parameter));
        }
        else if($type === 'bool'){
            return $this->request->existParameter($parameter) && ($this->request->getParameter($parameter) == 0 || $this->request->getParameter($parameter) == 1);
        }
        else
            return false;
    }

    /**
     * Fonction permettant de vérifier si une chaine passer en paramètre de la requête et de validé si elle est valide à une regex précise
     * @param string $parameter : nom du paramètre passer en get ou post
     * @param string $regex : modèle de la regex à laquelle doit être valide la valeur du paramètre
     * @return bool
     * @throws Exception
     */
    public function checkFormRegex(string $parameter, string $regex) : bool{
        return $this->request->existParameter($parameter) && (bool)preg_match('#^' . $regex . '$#u', trim($this->request->getParameter($parameter)));
    }


    /**************************************************************/
    /*                         Structure                          */
    /**************************************************************/

    /**
     * Ajout d'une nouvelle structure
     * @return void
     * @throws Exception
     */
    public function addStructure(){
        if(self::isValidCsrf()){
            $err = [];
            $this->checkForm('inputLastName', 'strlen', 3, 40) ? '': $err['LastName'] = true;
            $this->checkForm('inputFirstName', 'strlen', 3, 40) ? '' : $err['FirstName'] = true;
            $this->checkForm('inputEmail', 'mail') ? '' : $err['Email'] = true;
            $this->checkForm('inputPhone', 'is_numeric', 9, 10) ? '': $err['Phone'] = true;
            $this->checkForm('inputSocialName', 'strlen', 3, 40) ? '' : $err['SocialName'] = true;
            $this->checkForm('inputAddress', 'strlen', 3, 80) ? '' : $err['Address'] = true;
            $this->checkForm('partner_id', 'is_numeric') ? '' : $err['partner_id'] = true;

            if(count($err)> 0){
                print_r($err);
                //$this->redirect('/partner');
            }
            else {
                //creation de l'utilisateur et récupération de l'id
                $newUser = new User();
                $newUser->setUserFirstname($this->request->getParameter('inputFirstName'));
                $newUser->setUserLastname($this->request->getParameter('inputLastName'));
                $newUser->setUserMail($this->request->getParameter('inputEmail'));
                $newUser->setUserPassword(bin2hex(random_bytes(24))); // un mot de passe aléatoire inutilisable (pour ne pas laisser vide)
                $newUser->setUserPhone($this->request->getParameter('inputPhone'));
                $newUser->setUserAddress($this->request->getParameter('inputAddress'));
                $newUser->setUserPostal(0);
                $newUser->setRoleId(ROLE_STRUCTURE);
                $user_id = $newUser->addUser(); // Retourne le LastInsertId()

                $confirmKey = md5(bin2hex(random_bytes(32)));
                // Création du lien pour que l'utilisateur valide son compte
                $user_confirm = new UserConfirmModel();
                $user_confirm->setUserId($user_id);
                $user_confirm->setUserKey($confirmKey);
                $user_confirm->addUserConfirm();

                //Ajout d'une nouvelle structure
                $structure = new Structures();
                $structure->setPartnerId($this->request->getParameter('partner_id'));
                $structure->setUserId($user_id);
                $structure->setStructureName($this->request->getParameter('inputSocialName'));
                $id_structure = $structure->addStructure(); // Retourne le LastInsertId()


                $partnerService = new PartnersServices();
                $listPartnerServices  = $partnerService->getPartnerServiceListByPartnerId($this->request->getParameter('partner_id'));

                /**
                 * On a récupéré les listes des services du partenaire, on va ajouter les services actifs sur la structure
                 */
                foreach ($listPartnerServices as $_partnerService){
                    if($_partnerService->getIsActive()) {
                        $structureService = new StructuresServices();
                        $structureService->setStructureId($id_structure);
                        $structureService->setPartnerServiceId($_partnerService->getPartnerServiceId());
                        $structureService->addStructureService();
                    }
                }

                $message = sprintf(MAIL_BODY_NEW_STRUCTURE, $confirmKey);
                if (SEND_EMAIL) {
                    Helper::sendMail($this->request->getParameter('inputEmail'), $message, MAIL_TITLE_NEW_STRUCTURE); // Mail pour le gérant de la structure
                    //Helper::sendMail($user_mail_partner, $message, MAIL_TITLE_NEW_PARTNER_STRUCTURE); // Mail pour le partenaire
                }

                $this->redirect('/structure/information/' . $id_structure);
            }
        }
        else{
            die('mauvais csrf');
        }
    }

    /**
     * Mise à jour des informations des structures
     * @return void
     */
    public function editStructure()
    {
        if(self::isValidCsrf()){

            $form = [];
            $this->checkForm('inputLastName', 'strlen', 3, 40) ? '': $form['LastName'] = true;
            $this->checkForm('inputFirstName', 'strlen', 3, 40) ? '' : $form['FirstName'] = true;
            $this->checkForm('inputEmail', 'mail') ? '' : $err['Email'] = true;
            $this->checkForm('inputPhone', 'is_numeric', 9, 10) ? '': $form['Phone'] = true;
            $this->checkForm('inputSocialName', 'strlen', 3, 40) ? '' : $form['SocialName'] = true;
            $this->checkForm('inputAddress', 'strlen', 3, 80) ? '' : $form['Address'] = true;
            $this->checkForm('inputStructureId', 'is_numeric') ? '' : $form['StructureId'] = true;

            if(count($form) === 0){
                $updatedStructure = false;
                $updatedUser = false;
                /**
                 * On doit vérifier que le partenaire existe
                 */
                $structure = new Structures();
                $structure->setStructureId($this->request->getParameter('inputStructureId'));

                $updateStructure = $structure->getStructure();

                if($updateStructure->getStructureId() > 0) {
                    /**
                     * La structure existe donc l'utilisateur existe obligatoirement
                     */
                    $updateUser = new User();
                    $updateUser->getUser($updateStructure->getUserId());

                    $partenaireUser = new User();
                    $partenaireUser->getUser($updateStructure->getPartnerId());

                    // testons les différences du partenaire, le partenaire à un nom social (en général nom de la boite, et le status actif ou inactif)
                    if($updateStructure->getStructureName() != $this->request->getParameter('inputSocialName')  || // nom de l'entreprise
                        ($updateStructure->getIsActive() != $this->checkForm('inputStructureActive', 'bool')) // si partner active
                    ) {
                        // au moins une des 2 conditions précédente est vraie alors on doit mettre à jour le partenaire
                        $updateStructure->setStructureName($this->request->getParameter('inputSocialName'));
                        $updateStructure->setIsActive(($this->checkForm('inputStructureActive', 'bool')) ? 1 : 0);
                        if($updateStructure->saveStructure()){
                            $this->form_success .= 'La structure à bien été mis à jours<br />';
                            $updatedStructure = true;
                        }
                        else{
                            $this->form_error .= 'La structure n\'a pas été mis à jour !<br />';
                        }
                    }

                    // teston les différences avec l'utilisateur, si au moins une différence est avérée on fera une mise à jour
                    if($updateUser->getUserLastname() != $this->request->getParameter('inputLastName') || // nom différent
                        $updateUser->getUserFirstname() != $this->request->getParameter('inputFirstName') || // prénom différent
                        $updateUser->getUserAddress() != $this->request->getParameter('inputAddress') || // Adresse différente
                        $updateUser->getUserPhone() != $this->request->getParameter('inputPhone') || // téléphone différent
                        $updateUser->isUserActive() != $this->checkForm('inputUserActive', 'bool') || // téléphone différent
                        $updateUser->getUserMail() != $this->request->getParameter('inputEmail')  // mail différent
                    ){
                        // Au moins une différence a été constatée, mise à jour de l'utilisateur
                        $updateUser->setUserFirstname($this->request->getParameter('inputFirstName'));
                        $updateUser->setUserLastname($this->request->getParameter('inputLastName'));
                        $updateUser->setUserMail($this->request->getParameter('inputEmail'));
                        $updateUser->setUserPhone($this->request->getParameter('inputPhone'));
                        $updateUser->setUserAddress($this->request->getParameter('inputAddress'));
                        $updateUser->setUserActive($this->checkForm('inputUserActive', 'bool'));
                        // On sauvegarde et on ajoute un message flash qui sera affiché
                        if($updateUser->updateUser()){
                            $this->form_success .= 'L\'utilisateur à bien été mis à jours<br />';
                            $updatedUser = true;
                        }
                        // la sauvegarde a échoué on le notifie dans un message flash
                        else{
                            $this->form_error .= 'L\'utilisateur n\'a pas été mis à jour !<br />';
                        }
                    }

                    if(($updatedUser || $updatedStructure) && SEND_EMAIL){
                        // Il faut envoyer un mail pour toute modification à la structure
                        helper::sendMail($updateUser->getUserMail(), 'Bonjour '.$updateUser->getUserFirstname().' '.$updateUser->getUserLastname().', les informations de votre compte on été modifier!', 'les information de votre compte on été modifier');
                        // Il faut aussi le mail au partenaire pour l'avertir
                        helper::sendMail($partenaireUser->getUserMail(), 'Bonjour '.$partenaireUser->getUserFirstname().' '.$partenaireUser->getUserLastname().', la structure '.$updateStructure->getStructureName().' à été modifier!', 'les information de votre compte on été modifier');
                    }
                }
                else{
                    $this->form_error .= 'le partenaire n\'existe pas !';
                }


            }
            else{
                $this->form_error .=
                    ((!empty($form['Email'])) ? 'Désolé l\'adresse mail n\'est pas valide<br />' : '').
                    ((!empty($form['LastName'])) ? 'Désolé le nom doit avoir plus de 3 caractère<br />' : '').
                    ((!empty($form['FirstName'])) ? 'Désolé le prénom doit avoir plus de 2 caractère<br />' : '').
                    ((!empty($form['Phone'])) ? 'Désolé le numéro de téléphone n\'est pas valide<br />' : '').
                    ((!empty($form['Address'])) ? 'Désolé l\'adresse n\'est pas valide<br />' : '').
                    ((!empty($form['StructureId'])) ? 'Désolé l\'id du partenaire n\'est pas valide<br />' : '').
                    ((!empty($form['SocialName'])) ? 'Désolé le nom du partenaire n\'est pas valide<br />' : '');
            }

            $this->request->getSession()->setAttribute("form_success",  $this->form_success);
            $this->request->getSession()->setAttribute("form_error",  $this->form_error);
            $this->redirect('/structure/information/'.$this->request->getParameter('inputStructureId'));
        }
        else{
            $this->generateView(array('title' => 'Csrf erreur', 'msgError' => 'Oups : il y à un petit problème avec le code CSRF ;)'), 'error', 'error');
        }
        $this->request->getSession()->setAttribute("error_form",  $this->form_error);
    }

    /**
     * Activation / Désactivation d'une structure (depuis la page partenaire)
     * @return void
     * @throws Exception
     */
    public function enableDisableStructure(){
        if(self::isValidCsrf()){
            $err = [];
            $this->checkForm('is_active', 'is_numeric', 0 , 1) ? '': $err['is_active'] = true;
            $this->checkForm('structure_id', 'is_numeric') ? '': $err['structure_id'] = true;
            if(count($err) == 0) {
                $structure = new Structures();
                $structure->setStructureId($this->request->getParameter('structure_id'));
                // on récupère les informations de la structure actuelle
                $updateStructure = $structure->getStructure();
                // on vérifie que la structure existe en vérifiant si un partner_id est supérieur à zéro
                if ($updateStructure->getPartnerId() > 0) {
                    // on lui passe le status passer en paramètre
                    $updateStructure->setIsActive($this->request->getParameter('is_active'));
                    // on sauvegarde la structure
                    $updateStructure->saveStructure();
                    // Redirection vers la page du partenaire
                    $this->redirect('/partner/information/' . $updateStructure->getPartnerId());
                }
                else {
                    $this->generateView(array('title' => 'Formulaire Erreur', 'msgError' => 'Oups : il y a un petit problème avec cette structure, certaine donnée semble incorrecte'), 'error', 'error');
                }
            }
            else{
                $this->generateView(array('title' => 'Formulaire Erreur', 'msgError' => 'Oups : il y à un petit problème avec le formulaire, certaines données reçu ne sont pas autorisée'), 'error', 'error');
            }
        }
        else{
            $this->generateView(array('title' => 'Csrf erreur', 'msgError' => 'Oups : il y à un petit problème avec le code CSRF ;)'), 'error', 'error');
        }
    }



    /**************************************************************/
    /*                       Partenaire                           */
    /**************************************************************/

    /**
     * Ajout d'un nouveau partenaire
     * @return void
     * @throws Exception
     */
    public function addPartner()
    {
        if(self::isValidCsrf()){
            $err = [];
            $this->checkFormRegex('inputLastName', REGEX_LASTNAME) ? '' : $err['LastName'] = 1;
            $this->checkFormRegex('inputFirstName', REGEX_FIRSTNAME) ? '' : $err['FirstName'] = 1;
            $this->checkFormRegex('inputEmail', REGEX_MAIL) ? '' : $err['Email'] = 1;
            $this->checkFormRegex('inputPhone', REGEX_PHONE) ? '' : $err['Phone'] = 1;
            $this->checkFormRegex('inputSocialName', REGEX_SOCIAL_NAME) ? '' : $err['SocialName'] = 1;
            $this->checkFormRegex('inputAddress', REGEX_ADDRESS) ? '' : $err['Address'] = 1;

            if(count($err) == 0){

                //On passe la création en transaction afin de s'assurer de ne pas entrer un user orphelin
                \Application\Core\Database::start_transaction();
                try {
                    //creation de l'utilisateur et récupération de l'id
                    $newUser = new User();
                    $newUser->setUserFirstname($this->request->getParameter('inputFirstName'));
                    $newUser->setUserLastname($this->request->getParameter('inputLastName'));
                    $newUser->setUserMail($this->request->getParameter('inputEmail'));
                    $newUser->setUserPassword(bin2hex(random_bytes(24))); // un mot de passe aléatoire inutilisable (pour ne pas laisser vide)
                    $newUser->setUserPhone($this->request->getParameter('inputPhone'));
                    $newUser->setUserAddress($this->request->getParameter('inputAddress'));
                    $newUser->setUserPostal(0);
                    $newUser->setRoleId(ROLE_PARTNER);

                    $user_id = $newUser->addUser(); // Retourne le LastInsertId()
                    if($user_id > 0 ){
                        $this->form_success .= 'L\'utilisateur à bien été mis à jours<br />';
                        $updatedUser = true;
                    }
                    // la sauvegarde a échoué on le notifie dans un message flash
                    else{
                        $this->form_error .= 'L\'utilisateur n\'a pas été mis à jour !<br />';
                    }

                    $partner_id = $this->partner->createPartner($this->request->getParameter('inputSocialName'), $user_id);
                    if($partner_id > 0){

                        $updatedUser = true;
                    }
                    $confirm_hash = md5(bin2hex(random_bytes(32)));

                    $user_confirm = new UserConfirmModel();
                    $user_confirm->setUserId($user_id);
                    $user_confirm->setUserKey($confirm_hash);
                    $user_confirm->addUserConfirm();
                    //Tous est bon on commit
                    \Application\Core\Database::end_transaction_commit();
                    if(SEND_EMAIL) {
                        $message = sprintf(MAIL_BODY_NEW_PARTNER, $confirm_hash);
                        Helper::sendMail($this->request->getParameter('inputEmail'), $message, MAIL_TITLE_NEW_PARTNER_STRUCTURE); // Mail pour le partenaire
                    }


                    $this->redirect('/partner/information/'.$partner_id);
                }
                catch (\Exception $e){
                        //il y a une erreur on Rollback la transaction
                        \Application\Core\Database::end_transaction_rollback();
                        $this->generateView(array('title' => 'Csrf erreur', 'msgError' => 'Désolé la création à échoué, certaines données ne sont pas conforme <br /><b>Detail :</b><br />'.$e->getMessage()), 'error', 'error');
                }
            }
            else{
                $this->form_error .=
                    ((!empty($form['Email'])) ? 'Désolé l\'adresse mail n\'est pas valide<br />' : '').
                    ((!empty($form['LastName'])) ? 'Désolé le nom doit avoir plus de 3 caractère<br />' : '').
                    ((!empty($form['FirstName'])) ? 'Désolé le prénom doit avoir plus de 2 caractère<br />' : '').
                    ((!empty($form['Phone'])) ? 'Désolé le numéro de téléphone n\'est pas valide<br />' : '').
                    ((!empty($form['Address'])) ? 'Désolé l\'adresse n\'est pas valide<br />' : '').
                    ((!empty($form['StructureId'])) ? 'Désolé l\'id du partenaire n\'est pas valide<br />' : '').
                    ((!empty($form['SocialName'])) ? 'Désolé le nom du partenaire n\'est pas valide<br />' : '');
                $this->generateView(array('title' => 'Formulaire Erreur', 'msgError' => $this->form_error), 'error', 'error');
            }
        }
        else{
            $this->generateView(array('title' => 'Csrf erreur', 'msgError' => 'Oups : il y à un petit problème avec le code CSRF ;)'), 'error', 'error');
        }
    }

    /**
     * Mise à jour d'un partenaire
     * @return void
     * @throws Exception
     */
    public function editPartner()
    {
        if(self::isValidCsrf()){
            $err = [];
            $this->checkFormRegex('inputLastName', REGEX_LASTNAME) ? '' : $err['LastName'] = 1;
            $this->checkFormRegex('inputFirstName', REGEX_FIRSTNAME) ? '' : $err['FirstName'] = 1;
            $this->checkFormRegex('inputMail', REGEX_MAIL) ? '' : $err['Mail'] = 1;
            $this->checkFormRegex('inputPhone', REGEX_PHONE) ? '' : $err['Phone'] = 1;
            $this->checkFormRegex('inputSocialName', REGEX_SOCIAL_NAME) ? '' : $err['SocialName'] = 1;
            $this->checkFormRegex('inputAddress', REGEX_ADDRESS) ? '' : $err['Address'] = 1;
            $this->checkFormRegex('inputPartnerId', REGEX_PARTNER_ID) ? '' : $err['PartnerId'] = 1;

            if(count($err) == 0){
                /**
                 * On doit vérifier que le partenaire existe
                 */
                $updatePartner = new Partners();
                $updatePartner->getPartnerByPartnerId($this->request->getParameter('inputPartnerId'));

                if($updatePartner->getPartnerId() > 0) {

                    $partnerActive = $this->request->existParameter('inputPartnerActive');
                    $userActive = $this->request->existParameter('inputUserActive');
                    /**
                     * Le partenaire existe donc l'utilisateur existe obligatoirement
                     */
                    $updateUser = new User();
                    $updateUser->getUser($updatePartner->getUserId());

                    // testons les différences du partenaire, le partenaire à un nom social (en général nom de la boite, et le status actif ou inactif)
                    if($updatePartner->getPartnerName() != $this->request->getParameter('inputSocialName')  || // nom de l'entreprise
                        ($updatePartner->partnerActive() != $partnerActive) // si partner active
                    ) {
                        // au moins une des 2 conditions précédente est vraie alors on doit mettre à jour le partenaire
                        //$updatePartner->
                        $updatePartner->setPartnerName($this->request->getParameter('inputSocialName'));
                        $updatePartner->setPartnerActive(($partnerActive) ? 1 : 0);
                        if($updatePartner->partnerSave()){
                            $this->form_success .= 'Le partenaire à bien été mis à jours<br />';
                        }
                        else{
                            $this->form_error .= 'Le partenaire n\'a pas été mis à jour !<br />';
                        }
                    }

                    // teston les différences avec l'utilisateur, si au moins une différence est avérée on fera une mise à jour
                    if($updateUser->getUserLastname() != $this->request->getParameter('inputLastName') || // nom différent
                        $updateUser->getUserFirstname() != $this->request->getParameter('inputFirstName') || // prénom différent
                        $updateUser->getUserAddress() != $this->request->getParameter('inputAddress') || // Adresse différente
                        $updateUser->getUserPhone() != $this->request->getParameter('inputPhone') || // téléphone différent
                        $updateUser->isUserActive() != $userActive || // téléphone différent
                        $updateUser->getUserMail() != $this->request->getParameter('inputMail')  // mail différent
                    ){
                        // Au moins une différence a été constatée, mise à jour de l'utilisateur
                        $updateUser->setUserFirstname($this->request->getParameter('inputFirstName'));
                        $updateUser->setUserLastname($this->request->getParameter('inputLastName'));
                        $updateUser->setUserMail($this->request->getParameter('inputMail'));
                        $updateUser->setUserPhone($this->request->getParameter('inputPhone'));
                        $updateUser->setUserAddress($this->request->getParameter('inputAddress'));
                        $updateUser->setUserActive($userActive);
                        // On sauvegarde et on ajoute un message flash qui sera affiché
                        if($updateUser->updateUser()){
                            $this->form_success .= 'L\'utilisateur à bien été mis à jours<br />';
                        }
                        // la sauvegarde a échoué on le notifie dans un message flash
                        else{
                            $this->form_error .= 'L\'utilisateur n\'a pas été mis à jour !<br />';
                        }
                    }
                }
                else{
                    $this->form_error .= 'le partenaire n\'existe pas !';
                }
            }
            else{
                $this->form_error .=
                    ((!empty($err['Mail'])) ? 'Désolé l\'adresse mail n\'est pas valide<br />' : '').
                    ((!empty($err['LastName'])) ? 'Désolé le nom doit avoir plus de 3 caractère<br />' : '').
                    ((!empty($err['FirstName'])) ? 'Désolé le prénom doit avoir plus de 2 caractère<br />' : '').
                    ((!empty($err['Phone'])) ? 'Désolé le numéro de téléphone n\'est pas valide<br />' : '').
                    ((!empty($err['Address'])) ? 'Désolé l\'adresse n\'est pas valide<br />' : '').
                    ((!empty($err['PartnerId'])) ? 'Désolé l\'id du partenaire n\'est pas valide<br />' : '').
                    ((!empty($err['SocialName'])) ? 'Désolé le nom du partenaire n\'est pas valide<br />' : '');
            }
            $this->request->getSession()->setAttribute("form_success",  $this->form_success);
            $this->request->getSession()->setAttribute("form_error",  $this->form_error);
            $this->redirect('/partner/information/'.$this->request->getParameter('inputPartnerId'));
        }
        else{
            $this->form_error .= 'Désolé le code Csrf n\'est pas valide !';
        }
        $this->request->getSession()->setAttribute("error_form",  $this->form_error);

    }

    /**
     * Activation / Désactivation d'un partenaire
     * @return void
     * @throws Exception
     */
    public function enableDisablePartner()
    {
        if($this->request->existParameter('partner_id') && $this->request->existParameter('partner_active') ){
            $this->partner->updateStatusById($this->request->getParameter('partner_id'), $this->request->getParameter('partner_active'));
            // on mail le partenaire
            //Helper::sendMail($mailDest, $message, $object);

            // vu que l'on doit désactiver les structures si les partenaires sont désactivé fesons le ici
            $structureList = $this->structure->getStructureListByPartnerId($this->request->getParameter('partner_id'));

            // personnellement je ne désactiverais pas les structures, car pour la simple raison que si on doit réactiver le partenaire,
            // on devrait réactiver les structure sans savoir s'il y en avait des désactivés ou non
            foreach ($structureList as $structure){
                //envoie de mail à chaque structure pour prévenir de la désactivation du partenaire et donc de sa structure par affiliation
                //$this->structure->updateStatusById($structure->structure_id, $this->request->getParameter('partner_active'));
                //Helper::sendMail($structure->user_mail, $message, $object);
            }
        }
        /*if(self::isValidCsrf()){

        }*/
    }



    /**************************************************************/
    /*                         Services                           */
    /**************************************************************/

    /**
     * Permet d'activer ou désactiver un service sur le partenaire et sur une structure
     */
    public function enableDisableService()
    {
        if(self::isValidCsrf()){
            $type = ['structure', 'partner'];
            $err['service_id'] = $this->request->existParameter('service_id') && is_numeric($this->request->getParameter('service_id'));
            $err['service_active'] = $this->request->existParameter('service_active') && is_numeric($this->request->getParameter('service_active'));
            $err['service_type'] = $this->request->existParameter('service_type') && in_array($this->request->getParameter('service_type'), $type);
            $err['service_type_id'] = $this->request->existParameter('service_type_id') && is_numeric($this->request->getParameter('service_type_id')) > 0;

            if($err['service_id'] && $err['service_active'] && $err['service_type'] && $err['service_type_id']){

                if($this->request->getParameter('service_type') == 'structure'){
                    // il faudrait récupérer les informations du partenaire pour lui envoyé un mail

                    $editService = $this->structurerService->addRemoveService( $this->request->getParameter('service_id'), $this->request->getParameter('service_type_id'), $this->request->getParameter('service_active') );
                    if($editService){

                        /**
                         * Modification on envoie les mails
                         */
                        if(SEND_EMAIL){
                            // blabla envoie d'email... à la structure et au partenaire pour les prévenirs
                        }
                        $this->redirect('/structure/information/'.$this->request->getParameter('service_type_id'));

                    }
                    else
                        $this->generateView(array('title' => 'Erreur de mise à jour', 'msgError' => 'Oups : désolé une erreur c\'est surement glisser dans le formulaire, mais cela n\'a pu aboutir !'), 'error', 'error');
                }

                //Pour les partenaires
                elseif($this->request->getParameter('service_type') == 'partner'){

                    $editService = $this->partnerService->addUpdateRemoveService( $this->request->getParameter('service_id'), $this->request->getParameter('service_type_id'), $this->request->getParameter('service_active') );
                    if($editService) {

                        /**
                         * Modification on envoie les mails
                         */
                        if (SEND_EMAIL) {
                            // blabla envoie d'email... à la structure et au partenaire pour les prévenirs
                        }
                        $this->redirect('/partner/information/' . $this->request->getParameter('service_type_id'));
                    }
                    else
                        $this->generateView(array('title' => 'Erreur de mise à jour', 'msgError' => 'Oups : désolé une erreur c\'est surement glisser dans le formulaire, mais cela n\'a pu aboutir !'), 'error', 'error');
                }
                else{
                    //on pourrait afficher un page personnalisé pour l\'erreur, mais manque de temps
                    die('Oups : Non mais les services ne sont que pour les structure et partenaire aucune autre option n\'est disponible');
                }

            }
            else{
                //on pourrait afficher un page personnalisé pour l\'erreur, mais manque de temps
                die('Oups les paramètre passer ne sont pas bon... ');
            }
        }
        else{
            $this->generateView(array('title' => 'Csrf erreur', 'msgError' => 'Oups : il y à un petit problème avec le code CSRF ;)'), 'error', 'error');
        }
    }

    /**
     * Permet d'ajouter ou supprimer un service sur le partenaire et sur une structure
     */
    public function addRemoveService()
    {
        if(self::isValidCsrf()){
            $type = ['add','remove'];
            $err['service_id'] = $this->request->existParameter('service_id') && is_numeric($this->request->getParameter('service_id'));
            $err['service_type'] = $this->request->existParameter('service_type') && in_array($this->request->getParameter('service_type'), $type);
            $err['service_type_id'] = $this->request->existParameter('service_type_id') && is_numeric($this->request->getParameter('service_type_id')) > 0;

            if($err['service_id'] && $err['service_type'] && $err['service_type_id']){

                // On retrouve le partenaire
                $partner = $this->partner->getPartnerByPartnerId($this->request->getParameter('service_type_id'));

                // On retrouve l'utilisateur
                $partnerUser = new User();
                $partnerUser->getUser($partner->user_id);
                /**
                 * Suppression d'un service sur le partenaire
                 */
                if($this->request->getParameter('service_type') == 'remove'){

                    // On récupère les informations du service
                    $service = $this->partnerService->getInformationService($this->request->getParameter('service_id'));

                    // on retrouve la liste des structures du partenaire qui possède le service concerné
                    $listStructure = $this->structurerService->getListStructureByPartnerServiceId($this->request->getParameter('service_id'));

                    // on va initialisez une liste de mail vide et on les ajoutera s'il y en as
                    $mails = [];
                    /** Étape suivante, on doit supprimer sur les structures la relation avec le service du partenaire */
                    if(count($listStructure) >= 1) {
                        foreach ($listStructure as $structureService) {
                            $this->structurerService->deleteStructureServiceById($structureService->structure_service_id);
                            $mails[] = $structureService->user_mail;
                        }
                    }

                    // j'utiliserai bien des enum mais il faut php 8.+ et je suis en php 7.4
                    $remove = $this->partnerService->addUpdateRemoveService( $this->request->getParameter('service_id'), $this->request->getParameter('service_type_id'), 2);

                    // si la suppression à fonctionner
                    if($remove) {
                        // On prévient chaque structure et le partenaire par mail
                        if(SEND_EMAIL){
                            // Envoi de l'email à toutes les structures
                            foreach($mails as $mail){
                                Helper::sendMail($mail, sprintf(MAIL_BODY_SERVICE_DELETED_STRUCTURE, $partner->partner_name, $service->service_name), MAIL_OBJECT_SERVICE_DELETED);
                            }
                            // Envoi de l'email au partenaire
                            Helper::sendMail($partnerUser->getUserMail(), sprintf(MAIL_BODY_SERVICE_DELETED_PARTNER, $partner->partner_name, $service->service_name), MAIL_OBJECT_SERVICE_DELETED);
                        }
                        $this->redirect('/partner/information/' . $this->request->getParameter('service_type_id'));
                    }
                    else
                        $this->generateView(array( 'title' => 'Erreur : Suppression refusé', 'msgError' => 'Il reste actuellement des structure avec le service que vous essayer de supprimer actif sur ce partenaire !'), 'error', 'error');
                }

                /**
                 * Ajout d'un nouveau service sur le partenaire
                 */
                elseif($this->request->getParameter('service_type') == 'add'){



                    $add = $this->partnerService->addUpdateRemoveService($this->request->getParameter('service_id'), $this->request->getParameter('service_type_id'));

                    // On récupère les informations du service
                    $service = $this->partnerService->getInformationService($this->request->getParameter('service_id'));

                    if($add){
                        //Envoie d'un mail au partenaire pour lui signaler que le service a été ajouter
                        if(SEND_EMAIL){
                            //Helper::sendMail($partnerUser->user_mail, sprintf(MAIL_BODY_NEW_SERVICE, $service->service_name), MAIL_OBJECT_NEW_SERVICE);
                        }
                        //Redirection vers le partenaire
                        $this->redirect('/partner/information/' . $this->request->getParameter('service_type_id'));
                    }
                    else{
                        $this->generateView(array('title' => 'Erreur : Sql refusé', 'msgError' => 'Oups, ce partenaire possédè déjà ce service !'), 'error', 'error');
                    }
                }


                else{
                    $this->generateView(array('title' => 'Erreur : Paramètre incorrect', 'msgError' => 'Oups, certain paramètre passer ne sont pas bon...'), 'error', 'error');
                }
            }
            else{
                $this->generateView(array('title' => 'Erreur : Paramètre incorrect', 'msgError' => 'Oups les paramètre passer ne sont pas correct...'), 'error', 'error');
            }
        }
        else{
            $this->generateView(array('title' => 'Csrf erreur', 'msgError' => 'Oups : il y à un petit problème avec le code CSRF ;)'), 'error', 'error');
        }
    }
}