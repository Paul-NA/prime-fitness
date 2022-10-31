<?php

use Application\Core\ControllerSecuredAdmin;
use Application\Core\Helper;
use Application\Models\Partner;
use Application\Models\PartnerService;
use Application\Models\Service;
use Application\Models\Structure;
use Application\Models\StructureService;
use Application\Models\User;
use Application\Models\UserConfirm;

/**
 * ControllerForm, il est uniquement accessible à la personne aillant un status Admin
 * Ainsi impossible pour un utilisateur classique d'accédé aux fonctions des formulaires
 * Même avec les liens.
 */
class ControllerForm extends ControllerSecuredAdmin {

    //private Users $user;
    private Partner $partner;
    private Structure $structure;
    private StructureService $structurerService;
    private PartnerService $partnerService;

    private string $form_error;
    private string $form_success;

    public function __construct() {
        $this->structure = new Structure();
        $this->partnerService = new PartnerService();
        $this->structurerService = new StructureService();
        $this->partner = new Partner();
        $this->user = new User();

        $this->form_error = '';
        $this->form_success = '';
    }

    /**
     * Page /form (redirection)
     */
    public function index() {$this->redirect('/');}

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
    /*                         Users                              */
    /**************************************************************/

    public function removeUser(){

        if(self::isValidCsrf()){
            $err = [];
            $this->checkFormRegex('user_id', REGEX_USER_ID) ? '' : $err['UserId'] = 1;
            if(count($err) === 0){
                $userPartner = new User();
                $delUser = $userPartner->getUser($this->request->getParameter('user_id'));
                /**
                 * L'utilisateur existe
                 */
                if($delUser->getUserId() > 0) {
                    /**
                     * L'utilisateur est un partenaire
                     */
                    if ($delUser->getRoleId() == ROLE_PARTNER) {
                        $partner = new Partner();
                        $partnerInfo = $partner->getPartnerByUserId($delUser->getUserId());

                        //On va rechercher les structure lier afin de récupérer tous les utilisateurs associer
                        $structures = new Structure();
                        $structuresList = $structures->getStructureListByPartnerId($partnerInfo->getPartnerId());
                        $userKey = array_keys($structuresList);

                        // On va créer une liste d'email à contacter
                        $listEmail = [];
                        $delUser->getUserMail();
                        if(count($userKey) >= 1) {
                            // Maintenant on va récupérer la liste des utilisateurs et leurs informations et les delete
                            $users = new User();
                            $usersList = $users->getUserListByUsersId($userKey);



                            // on parcourt la liste et on récupère seulement les emails que l'on ajoute au tableau créer juste avant
                            foreach ($usersList as $user) {
                                $listEmail[] = $user->getUserMail();
                                // on ne supprime pas les utilisateurs un par un mais avec la liste que l'on a récupéré (les requêtes sql dans les boucles, ce n'est pas bien).
                            }
                        }

                        if($delUser->deleteUsersList($userKey)){

                        }
                        else{
                            die('erreur');
                        }

                        // On supprime le partenaire par la suite
                        $delUser->deleteUser($delUser->getUserId());

                        if(SEND_EMAIL){
                            Helper::sendMail($delUser->getUserMail(), 'Bonjours, votre compte ainsi que toutes les donnée associer, ainsi que toutes vos structure et leur données associer on été supprimer', 'Suppression de votre votre et informations');
                            foreach ($listEmail as $mail){
                                Helper::sendMail($mail, 'Bonjours, vote compte ainsi que toutes les données associer on été supprimer de notre base de donnée.', 'Suppression de votre compte et donnée associer');
                            }
                        }
                        $this->redirect('/');
                    }
                    /**
                     *
                     */
                    elseif ($delUser->getRoleId() == ROLE_STRUCTURE) {

                    }
                }
                else{ die('erreur de suppression id user = 0'); }
            }
            else { var_dump($_POST); die('oups'); }
        }
        else{ $this->generateView(array('title' => 'Csrf erreur', 'msgError' => 'Oups : il y à un petit problème avec le code CSRF ;)'), 'error', 'error'); }
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
            $this->checkFormRegex('inputLastName', REGEX_LASTNAME) ? '' : $err['LastName'] = 1;
            $this->checkFormRegex('inputFirstName', REGEX_FIRSTNAME) ? '' : $err['FirstName'] = 1;
            $this->checkFormRegex('inputEmail', REGEX_MAIL) ? '' : $err['Email'] = 1;
            $this->checkFormRegex('inputPhone', REGEX_PHONE) ? '' : $err['Phone'] = 1;
            $this->checkFormRegex('inputSocialName', REGEX_SOCIAL_NAME) ? '' : $err['SocialName'] = 1;
            $this->checkFormRegex('inputAddress', REGEX_ADDRESS) ? '' : $err['Address'] = 1;
            $this->checkFormRegex('partner_id', REGEX_PARTNER_ID) ? '' : $err['partner_id'] = 1;
            if(count($err) === 0){

                $_partner = new Partner();
                $partner = $_partner->getPartnerByPartnerId($this->request->getParameter('partner_id'));

                if($partner->getPartnerId() > 0) {
                    \Application\Core\Database::start_transaction();
                    $_user = new User();
                    $partner_user = $_user->getUser($partner->getUserId());

                    //creation de l'utilisateur et récupération de l'id
                    $newUser = new User();
                    $newUser->setUserFirstname($this->request->getParameter('inputFirstName'));
                    $newUser->setUserLastname($this->request->getParameter('inputLastName'));
                    $newUser->setUserMail($this->request->getParameter('inputEmail'));
                    $newUser->setUserPassword(bin2hex(random_bytes(24))); // un mot de passe aléatoire inutilisable (pour ne pas laisser vide)
                    $newUser->setUserPhone($this->request->getParameter('inputPhone'));
                    $newUser->setUserAddress($this->request->getParameter('inputAddress'));
                    $newUser->setRoleId(ROLE_STRUCTURE);
                    $user_id = $newUser->addUser(); // Retourne le LastInsertId()

                    $confirmKey = md5(bin2hex(random_bytes(32)));
                    // Création du lien pour que l'utilisateur valide son compte
                    $user_confirm = new UserConfirm();
                    $user_confirm->setUserId($user_id);
                    $user_confirm->setUserKey($confirmKey);
                    $user_confirm->addUserConfirm();

                    //Ajout d'une nouvelle structure
                    $structure = new Structure();
                    $structure->setPartnerId($this->request->getParameter('partner_id'));
                    $structure->setUserId($user_id);
                    $structure->setStructureName($this->request->getParameter('inputSocialName'));
                    $id_structure = $structure->addStructure(); // Retourne le LastInsertId()


                    $partnerService = new PartnerService();
                    $listPartnerServices = $partnerService->getPartnerServiceListByPartnerId($this->request->getParameter('partner_id'));

                    /**
                     * On a récupéré les listes des services du partenaire, on va ajouter les services actifs sur la structure
                     */
                    foreach ($listPartnerServices as $partner_Service) {
                        if ($partner_Service->getPartnerServiceActive()) {
                            $structureService = new StructureService();
                            $structureService->setStructureId($id_structure);
                            $structureService->setPartnerServiceId($partner_Service->getPartnerServiceId());
                            $structureService->addStructureService();
                        }
                    }

                    \Application\Core\Database::end_transaction_commit();
                    $message = sprintf(MAIL_BODY_NEW_STRUCTURE, $confirmKey);
                    if (SEND_EMAIL) {
                        Helper::sendMail($this->request->getParameter('inputEmail'), $message , MAIL_TITLE_NEW_STRUCTURE); // Mail pour le gérant de la structure
                        Helper::sendMail($partner_user->getUserMail(), 'Bonjour, Une nouvelle structure viens d\'etre ajouter à votre compte.', MAIL_TITLE_NEW_PARTNER_STRUCTURE); // Mail pour le partenaire
                    }
                    $this->redirect('/structure/information/' . $id_structure);
                }
                else{
                    $this->generateView(array('title' => 'Erreur', 'msgError' => 'Oups : le partenaire n\'existe pas !'), 'error', 'error');
                }
            }
            else {
                $this->generateView(array('title' => 'Erreur', 'msgError' => 'Désolé il y a une erreur dans le formulaire !'), 'error', 'error');
            }
        }
        else{
            $this->generateView(array('title' => 'Csrf erreur', 'msgError' => 'Oups : il y à un petit problème avec le code CSRF ;)'), 'error', 'error');
        }
    }

    /**
     * Mise à jour des informations des structures
     * @return void
     */
    public function editStructure()
    {
        if(self::isValidCsrf()){

            $err = [];
            $this->checkFormRegex('inputLastName', REGEX_LASTNAME) ? '' : $err['LastName'] = 1;
            $this->checkFormRegex('inputFirstName', REGEX_FIRSTNAME) ? '' : $err['FirstName'] = 1;
            $this->checkFormRegex('inputEmail', REGEX_MAIL) ? '' : $err['Email'] = 1;
            $this->checkFormRegex('inputPhone', REGEX_PHONE) ? '' : $err['Phone'] = 1;
            $this->checkFormRegex('inputSocialName', REGEX_SOCIAL_NAME) ? '' : $err['SocialName'] = 1;
            $this->checkFormRegex('inputAddress', REGEX_ADDRESS) ? '' : $err['Address'] = 1;
            $this->checkFormRegex('inputStructureId', REGEX_STRUCTURE_ID) ? '' : $err['StructureId'] = 1;

            if(count($err) === 0){
                $updatedStructure = false;
                $updatedUser = false;
                /**
                 * On doit vérifier que le partenaire existe
                 */
                $structure = new Structure();
                $updateStructure = $structure->getStructure($this->request->getParameter('inputStructureId'));

                if($updateStructure->getStructureId() > 0) {
                    /**
                     * La structure existe donc l'utilisateur existe obligatoirement
                     */
                    $_user = new User();
                    $updateUser = $_user->getUser($updateStructure->getUserId());

                    $user2 = new User();
                    $partenaireUser = $user2->getUser($updateStructure->getUserId()); //<-- foirage ici


                    // testons les différences du partenaire, le partenaire à un nom social (en général nom de la boite, et le status actif ou inactif)
                    if($updateStructure->getStructureName() != $this->request->getParameter('inputSocialName')  || // nom de l'entreprise
                        ($updateStructure->getStructureActive() != $this->request->existParameter('inputStructureActive')) // si partner active
                    ) {
                        // au moins une des 2 conditions précédente est vraie alors on doit mettre à jour le partenaire
                        $updateStructure->setStructureName($this->request->getParameter('inputSocialName'));
                        $updateStructure->setStructureActive(($this->request->existParameter('inputStructureActive')) ? 1 : 0);
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
                        $updateUser->isUserActive() != $this->request->existParameter('inputUserActive') || // téléphone différent
                        $updateUser->getUserMail() != $this->request->getParameter('inputEmail')  // mail différent
                    ){
                        // Au moins une différence a été constatée, mise à jour de l'utilisateur
                        $updateUser->setUserFirstname($this->request->getParameter('inputFirstName'));
                        $updateUser->setUserLastname($this->request->getParameter('inputLastName'));
                        $updateUser->setUserMail($this->request->getParameter('inputEmail'));
                        $updateUser->setUserPhone($this->request->getParameter('inputPhone'));
                        $updateUser->setUserAddress($this->request->getParameter('inputAddress'));
                        $updateUser->setUserActive($this->request->existParameter('inputUserActive'));
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
                    ((!empty($err['Email'])) ? 'Désolé l\'adresse mail n\'est pas valide<br />' : '').
                    ((!empty($err['LastName'])) ? 'Désolé le nom doit avoir plus de 3 caractère<br />' : '').
                    ((!empty($err['FirstName'])) ? 'Désolé le prénom doit avoir plus de 2 caractère<br />' : '').
                    ((!empty($err['Phone'])) ? 'Désolé le numéro de téléphone n\'est pas valide<br />' : '').
                    ((!empty($err['Address'])) ? 'Désolé l\'adresse n\'est pas valide<br />' : '').
                    ((!empty($err['StructureId'])) ? 'Désolé l\'id du partenaire n\'est pas valide<br />' : '').
                    ((!empty($err['SocialName'])) ? 'Désolé le nom du partenaire n\'est pas valide<br />' : '');
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
            $this->checkFormRegex('structure_id', REGEX_STRUCTURE_ID) ? '' : $err['StructureId'] = 1;
            $this->checkFormRegex('is_active', REGEX_IS_VALID) ? '' : $err['StructureId'] = 1;
            //$this->checkForm('is_active', 'is_numeric', 0 , 1) ? '': $err['is_active'] = true;
            //$this->checkForm('structure_id', 'is_numeric') ? '': $err['structure_id'] = true;
            if(count($err) == 0) {
                $structure = new Structure();
                //$structure->setStructureId($this->request->getParameter('structure_id'));

                // on récupère les informations de la structure actuelle
                $updateStructure = $structure->getStructure($this->request->getParameter('structure_id'));

                // on vérifie que la structure existe en vérifiant si un partner_id est supérieur à zéro
                if ($updateStructure->getPartnerId() > 0) {

                    // on lui passe le status passer en paramètre
                    $updateStructure->setStructureActive($this->request->getParameter('is_active'));

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

                    $partner = new Partner();
                    $partner->setPartnerName($this->request->getParameter('inputSocialName'));
                    $partner->setUserId($user_id);
                    $partner_id = $partner->addPartner();
                    if($partner_id > 0){

                        $updatedUser = true;
                    }
                    $confirm_hash = md5(bin2hex(random_bytes(32)));

                    $user_confirm = new UserConfirm();
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
    public function editPartner(){
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
                $_updatePartner = new Partner();
                $updatePartner = $_updatePartner->getPartnerByPartnerId($this->request->getParameter('inputPartnerId'));

                if($updatePartner->getPartnerId() > 0) {

                    $partnerActive = $this->request->existParameter('inputPartnerActive');
                    $userActive = $this->request->existParameter('inputUserActive');
                    /**
                     * Le partenaire existe donc l'utilisateur existe obligatoirement
                     */
                    $_user = new User();
                    $updateUser = $_user->getUser($updatePartner->getUserId());

                    // testons les différences du partenaire, le partenaire à un nom social (en général nom de la boite, et le status actif ou inactif)
                    if($updatePartner->getPartnerName() != $this->request->getParameter('inputSocialName')  || // nom de l'entreprise
                        ($updatePartner->getPartnerActive() != $partnerActive) // si partner active
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

            $partner = new Partner();
            $partnerUpdate = $partner->getPartnerByPartnerId($this->request->getParameter('partner_id'));

            if($partnerUpdate->getPartnerId() > 0){

                $partnerUpdate->setPartnerActive($this->request->getParameter('partner_active'));
                $partnerUpdate->partnerSave();

                // vu que l'on doit désactiver les structures si les partenaires sont désactivé fesons le ici
                $structures = new Structure();
                $structureList = $structures->getStructureListByPartnerId($this->request->getParameter('partner_id'));

                $maiList = [];

                $userKey = array_keys($structureList);
                if(count($userKey) >= 1){
                    // on récupère les informations des utilisateurs concernés
                    $users = new User();
                    $usersList = $users->getUserListByUsersId($userKey);
                    // personnellement je ne désactiverais pas les structures, car pour la simple raison que si on doit réactiver le partenaire,
                    foreach ($structureList as $structure){

                        // On ne désactive pas les structures, ainsi si le partenaire retrouve son compte il retrouve ses structures (en cas de paiement en retard par exemple)
                        //$structure->setStructureActive($this->request->getParameter('partner_active'));

                        //Envoi de mail à chaque structure pour prévenir de la désactivation du partenaire et donc de sa structure par affiliation
                        $maiList = $usersList[$structure->getUserId()]->getUserMail();
                    }
                }

                // on envoie les mails
                if(SEND_EMAIL){
                    foreach ($maiList as $mail){
                        Helper::sendMail($mail, 'Bonjour, le partenaire '. $partnerUpdate->getPartnerName().' à été désactiver, par conséquent votre structure est elle aussi désactivé. <br> si cela est temporaire lors de la réactivation du partenaire votre structure reprendra sont status avant désactivation', 'Prime-Fitness : Désactivation de votre compte structure');
                    }
                    Helper::sendMail('mail partner', 'Bonjour, votre access partenaire pour '. $partnerUpdate->getPartnerName().' à été désactivé, par conséquent toutes vos structure s\'en trouve affecté est sont dorénavent aussi désactivé', 'Prime-Fitness : Désactivation de votre compte partenaire');
                }

            }
            else{
                $this->generateView(array('title' => 'Erreur', 'msgError' => 'Oups : Le partenaire n\'existe pas !'), 'error', 'error');
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
                    $this->generateView(array('title' => 'Erreur de mise à jour', 'msgError' => 'Oups : Non mais les services ne sont que pour les structure et partenaire aucune autre option n\'est disponible'), 'error', 'error');
                }

            }
            else{
                //on pourrait afficher un page personnalisé pour l\'erreur, mais manque de temps
                $this->generateView(array('title' => 'Csrf erreur', 'msgError' => 'Oups : les paramètre passer ne sont pas bon...'), 'error', 'error');
            }
        }
        else{
            $this->generateView(array('title' => 'Csrf erreur', 'msgError' => 'Oups : il y à un petit problème avec le code CSRF ;)'), 'error', 'error');
        }
    }

    /**
     * Permet d'ajouter ou supprimer un service sur le partenaire et sur une structure
     */
    public function addRemoveService(){
        if(self::isValidCsrf()){
            $type = ['add','remove'];
            $err['service_id'] = $this->request->existParameter('service_id') && is_numeric($this->request->getParameter('service_id'));
            $err['service_type'] = $this->request->existParameter('service_type') && in_array($this->request->getParameter('service_type'), $type);
            $err['service_type_id'] = $this->request->existParameter('service_type_id') && is_numeric($this->request->getParameter('service_type_id')) > 0;

            if($err['service_id'] && $err['service_type'] && $err['service_type_id']) {

                // On retrouve le partenaire
                $partner = $this->partner->getPartnerByPartnerId($this->request->getParameter('service_type_id'));

                if ($partner->getPartnerId() > 0) {
                    // On retrouve l'utilisateur
                    $_user = new User();
                    $partnerUser = $_user->getUser($partner->getUserId());

                    /**
                     * Suppression d'un service sur le partenaire
                     */
                    if ($this->request->getParameter('service_type') == 'remove') {

                        // On récupère les informations du Partenaire Service
                        $partnerService = new PartnerService();
                        $partner_service = $partnerService->getPartnerService($this->request->getParameter('service_id'));

                        if ($partner_service->getServiceId() > 0) {
                            // On récupère le Service
                            $service = new Service();
                            $formService = $service->getService($partner_service->getServiceId());

                            // on retrouve la liste des structures service du partenaire qui possède la structure et utilisateurs concernés
                            $structureService = new StructureService();
                            $listStructureService = $structureService->getStructureServiceListByPartnerServiceId($this->request->getParameter('service_id'));

                            $structureKey = array_keys($listStructureService);

                            // on va initialisez une liste de mail vide et on les ajoutera s'il y en a
                            $mails = [];

                            // on récupère les informations des structures s'il y en a
                            if (count($structureKey) >= 1) {
                                $structure = new Structure();
                                $structuresEditList = $structure->getStructureListByStructuresId($structureKey);

                                $userKey = array_keys($structuresEditList);

                                // on récupère les informations des utilisateurs concernés
                                $userEdit = new User();
                                $usersEditList = $userEdit->getUserListByUsersId($userKey);

                                /** Étape suivante, on doit supprimer sur les structures la relation avec le service du partenaire */
                                if (count($structuresEditList) >= 1) {
                                    foreach ($structuresEditList as $_structure) {
                                        $struc = $structuresEditList[$_structure->getUserId()]; // la structure
                                        $usr = $usersEditList[$_structure->getUserId()];        // l'user
                                        $structServ = $listStructureService[$struc->getStructureId()]; // la structure service
                                        $this->structurerService->deleteStructureServiceById($structServ->getStructureServiceId());
                                        $mails[] = $usr->getUserMail();
                                    }
                                }
                            }
                            $remove = $this->partnerService->addUpdateRemoveService($this->request->getParameter('service_id'), $this->request->getParameter('service_type_id'), 2);

                            // si la suppression à fonctionner
                            if ($remove) {
                                // On prévient chaque structure et le partenaire par mail
                                if (SEND_EMAIL) {
                                    // Envoi de l'email à toutes les structures
                                    foreach ($mails as $mail) {
                                        Helper::sendMail($mail, sprintf(MAIL_BODY_SERVICE_DELETED_STRUCTURE, $partner->getPartnerName(), $formService->getServiceName()), MAIL_OBJECT_SERVICE_DELETED);
                                    }
                                    // Envoi de l'email au partenaire
                                    Helper::sendMail($partnerUser->getUserMail(), sprintf(MAIL_BODY_SERVICE_DELETED_PARTNER, $partner->getPartnerName(), $formService->getServiceName()), MAIL_OBJECT_SERVICE_DELETED);
                                }
                                $this->redirect('/partner/information/' . $this->request->getParameter('service_type_id'));
                            } else
                                $this->generateView(array('title' => 'Erreur : Suppression refusé', 'msgError' => 'Il reste actuellement des structure avec le service que vous essayer de supprimer actif sur ce partenaire !'), 'error', 'error');

                        } else
                            $this->generateView(array('title' => 'OULALALA', 'msgError' => 'Il se pourrais bien, avec une certitude d\'environ 100% que vous avez modifier le formulaire ;) !'), 'error', 'error');
                    } /**
                     * Ajout d'un nouveau service sur le partenaire
                     */
                    elseif ($this->request->getParameter('service_type') == 'add') {

                        $add = $this->partnerService->addUpdateRemoveService($this->request->getParameter('service_id'), $this->request->getParameter('service_type_id'));

                        // On récupère les informations du service
                        $service = $this->partnerService->getInformationService($this->request->getParameter('service_id'));

                        if ($add) {
                            //Envoie d'un mail au partenaire pour lui signaler que le service a été ajouter
                            if (SEND_EMAIL) {
                                Helper::sendMail($partnerUser->getUserMail(), sprintf(MAIL_BODY_NEW_SERVICE, $service->service_name), MAIL_OBJECT_NEW_SERVICE);
                            }
                            //Redirection vers le partenaire
                            $this->redirect('/partner/information/' . $this->request->getParameter('service_type_id'));
                        }
                        else {
                            $this->generateView(array('title' => 'Erreur : refusé', 'msgError' => 'Oups, ce partenaire possédè déjà ce service !'), 'error', 'error');
                        }
                    }
                    else {
                        $this->generateView(array('title' => 'Erreur : Paramètre incorrect', 'msgError' => 'Oups, certain paramètre passer ne sont pas bon...'), 'error', 'error');
                    }
                }
                else{
                    $this->generateView(array('title' => 'OLA Amigo', 'msgError' => 'Salut, tu as perdu quelque chose est tu essaie de le retrouvez en modifiant un formulaire ? :)'), 'error', 'error');
                }
            }
            else{$this->generateView(array('title' => 'Erreur : Paramètre incorrect', 'msgError' => 'Oups les paramètre passer ne sont pas correct...'), 'error', 'error');}
        }
        else{$this->generateView(array('title' => 'Csrf erreur', 'msgError' => 'Oups : il y à un petit problème avec le code CSRF ;)'), 'error', 'error');}
    }
}