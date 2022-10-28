<?php
use Application\Core\Controller;
use Application\Core\Helper;
use Application\Models\Users;
use Application\Models\UserConfirmModel;
use Application\Models\LogsModel;

/**
 * Connexion au site
 */
class ControllerUser extends Controller
{
    private Users $user;

    public function __construct()
    {
        $this->user = new Users();
    }

    /**
     * La page user n'est pas accessible directement, on sera redirigé
     * page /user
     */
    public function index()
    {
        $this->redirect(($this->isLogged()) ? '/' : '/user/login');
    }

    /**
     * Connexion au site
     * page /user/login
     */
    public function login()
    {
        if($this->isLogged()){ // Redirection forcée si on est déjà connecté !
            $this->redirect('/');
        }
        // Si le post mail et password existe
        else if($this->request->existParameter('mail-prime') && $this->request->existParameter('password-prime')){
            //si le mail à un format valide
            if(Helper::validMail($this->request->getParameter('mail-prime'))){

                //Récupération des informations du formulaire
                $mail = $this->request->getParameter('mail-prime');
                $password = $this->request->getParameter('password-prime');

                // si le compte existe
                $user_id = $this->user->login($mail, $password);
                // TODO : a refaire j'aimerai utilisé un autre système
                if($user_id > 0){

                    $currentUser = new Users();
                    // Récupération d'un utilisateur
                    $currentUser->getUser($user_id);

                    // On vérifie que l'utilisateur est actif
                    if($currentUser->isUserActive()){
                        // on crée 2 sessions user_id, et user_role afin de récupérer les informations si nécessaires
                        $this->request->getSession()->setAttribute('user_id', $currentUser->getUserId());
                        $this->request->getSession()->setAttribute('user_role', $currentUser->getRoleId());

                        /**
                         * Ajout d'un log à la connexion
                         */
                        $log = new LogsModel();
                        $log->setLogType('Users');
                        $log->setLogTypeId($currentUser->getUserId()); // $user->setUserId(); ou autre
                        $log->setLogText('Nouvelle Connexion '. $_SERVER['REMOTE_ADDR']);
                        $log->setUserId($currentUser->getUserId()); // $user->setUserId();
                        $log->addLog();

                        // On redirige vers l'index du site
                        $this->redirect('');
                    }
                    else{ $this->generateView(array('msgErreur' => 'Votre compte est actuellement inactif')); }
                }
                // identifiant incorrect
                else{ $this->generateView(array('msgErreur' => 'Mail ou mot de passe incorrects')); }
            }
            //si le mail à un format invalide
            else{ $this->generateView(array('msgErreur' => 'le Mail à un format incorrects')); }
        }
        // pas de post mail/password
        else{ $this->generateView(); }
    }

    /**
     * On doit changer le mot de passe avant la première connexion
     * page /user/confirm/{id}
     */
    public function confirm()
    {

        if($this->isLogged()){ // Redirection forcée si on est déjà connecté !
            $this->redirect('/');
        }
        else if($this->request->existParameter('id')){

            $userConfirm = new UserConfirmModel();
            $userConfirm->getUserConfirm($this->request->getParameter('id'));

            //Si la clé existe l'user_id et obligatoirement supérieur à zéro
            if($userConfirm->getUserId() > 0){

                // On va récupérer l'utilisateur à traiter
                $currentUser = new Users();
                $currentUser->getUser($userConfirm->getUserId());

                // On vérifie que les post "password-prime" et "password-prime-exist" existe
                if($this->request->existParameter('password-prime') && $this->request->existParameter('password-prime-confirm')){
                    // On vérifie que le token est valide
                    if($this->isValidCsrf()){
                        // on vérifie que les post "password-prime" et "password-prime-confirm" soit identique
                        if(strcmp($this->request->getParameter('password-prime'), $this->request->getParameter('password-prime-confirm')) == 0) {

                            // de 8 à X caractère, toute lettre a-z A-Z 0-9 plus caractère spéciaux requis (pour la sécurité)
                            // On peut certainement améliorer cette regex
                            $pattern = '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[^A-Za-z\d]).*$/';
                            // on vérifie le modèle du mot de passe afin d'avoir des mots de passe solide
                            if(preg_match($pattern,$this->request->getParameter('password-prime'))){

                                // Modification du mot de passe et on sauvegarde l'utilisateur
                                $currentUser->setUserPassword($this->request->getParameter('password-prime'));
                                $currentUser->setUserActive(true);
                                $currentUser->updateUser();

                                /**
                                 * Ajout d'un log à la connexion
                                 */
                                $log = new LogsModel();
                                $log->setLogType('Users_confirm');
                                $log->setLogTypeId($userConfirm->getUserId());
                                $log->setLogText('Validation du compte user ( '. $_SERVER['REMOTE_ADDR'].' )');
                                $log->setUserId($userConfirm->getUserId());
                                $log->addLog();

                                // On supprime l'entré user_confirm utilisé
                                $userConfirm->deleteUserConfirm();

                                $this->redirect('');
                            }
                            // mot de passe pas assez sécurisé
                            else {
                                // Génération d'un nouveau token Csrf à chaque fois que l'on affiche la page
                                $this->genCsrf();
                                $this->generateView(array('msgErreur' => 'Le mot de passe est trop faible, le mot de passe doit contenir au moins 8 caractères avec chiffre, majuscule, minuscule, et caractère spéciaux ','csrf' => $this->request->getSession()->getAttribute('csrf_token')));
                            }
                        }
                        // Mot de passe entré non identique
                        else {
                            // Génération d'un nouveau token Csrf à chaque fois que l'on affiche la page
                            $this->genCsrf();
                            $this->generateView(array('msgErreur' => 'Les mot de passe sont différent','csrf' =>  $this->request->getSession()->getAttribute('csrf_token')));
                        }
                    }
                    // Csrf invalide
                    else {
                        // Génération d'un nouveau token Csrf à chaque fois que l'on affiche la page
                        $this->genCsrf();
                        $this->generateView(array('msgErreur' => 'Désolé le jeton de validation n\'est plus valide','csrf' => $this->request->getSession()->getAttribute('csrf_token')));
                    }
                }
                // Creation d'un token & affichage du formulaire
                else if(!$currentUser->isUserActive()) {
                    // Génération d'un nouveau token Csrf à chaque fois que l'on affiche la page
                    $this->genCsrf();
                    $this->generateView(['csrf' => $this->request->getSession()->getAttribute('csrf_token')]);
                }
            }
            // la clé n'existe pas (certainement déjà validé.)
            else { $this->redirect(''); }
        }
        // Aucun paramètre dans la requête
        else{ $this->redirect(''); }
    }

    /**
     * Déconnexion de l'utilisateur
     * page /user/logout
     */
    public function logout() : void
    {
        $this->request->getSession()->destroy();
        $this->redirect('');
    }
}