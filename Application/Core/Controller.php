<?php
namespace Application\Core;

use Application\Models\Users;

/**
 * Classe abstraite contrôleur. 
 * Fournit des services communs aux classes contrôleurs dérivées.
 */
abstract class Controller
{
    /**
     * Utilisateur courant
     */
    private Users $user;

    /**
     * Action à réaliser
     */
    private string $action;

    private string $flash_message_type;
    private string $flash_message_message;
    /**
     * Requête entrante
     */
    protected HttpQuery $request;

    /**
     * Définit la requête entrante
     */
    public function setRequest(HttpQuery $request)
    {
        $this->request = $request;
    }

    /**
     * Exécute l'action à réaliser en appelant la méthode portant le même nom que l'action sur l'objet Controller courant
     */
    public function execAction($action)
    {
        if (method_exists($this, $action)) {
            $this->action = $action;
            $this->{$this->action}();
        }
        else {
            $this->action = 'index'; // on garde le système par défaut avec index
            $this->generateView(array(), $this->action, CONTROLLER_ERROR);
        }
    }

    /**
     * Méthode abstraite correspondant à l'action par défaut obligent les classes dérivées à implémenter cette action par défaut
     */
    public abstract function index();

    /**
     * Génère la vue associée au contrôleur courant
     */
    protected function generateView(array $donneesVue = array(), string $action = null, $changeController = null, $changeLayout = null)
    {
        // Utilisation de l'action actuelle par défaut
        $actionVue = $this->action;
        if ($action != null) {
            // Utilisation de l'action passée en paramètre
            $actionVue = $action;
        }
        // Utilisation du nom du contrôleur actuel
        $classeController = ($changeController === null ) ? get_class($this) : $changeController;
        $controllerView = str_replace("Controller", "", $classeController);

        // on ajoute l'utilisateur aux données de la vue s'il est connecté
        if($this->isLogged()){
            $user = new Users();
            $this->user = $user->getUserv2($this->request->getSession()->getAttribute("user_id"));
            // vérifie que l'utilisateur est bien réel, et que l'utilisateur est actif
            if($this->user->getUserId() > 0 && $this->user->isUserActive()) {
                $donneesVue['current_user'] = $this->user;
            }
            else{
                $this->redirect('/user/logout');
            }
        }

        // Instanciation et génération de la vue
        $vue = new View($actionVue, $controllerView);
        $vue->generate($donneesVue, $changeLayout);

        $this->cleanFlashMessage();
    }

    /**
     * Effectue une redirection vers une url simplement
     */
    protected function redirect(string $route) : void
    {
        header('Location:' . URI_ROOT . $route);
    }


    /**************************************************************/
    /*                         Csrf                           */
    /**************************************************************/

    /**
     * Vérifie si le token est le bon puis en créer un nouveau
     */
    public function isValidCsrf() : bool{
        $return = $this->request->existParameter('csrf') && strcmp($this->request->getParameter('csrf'), $this->request->getSession()->getAttribute('csrf_token')) == 0;
        self::genCsrf();
        return $return;
    }

    /**
     * Retourne un nouveau token csrf
     */
    public function genCsrf() : void{
        $this->request->getSession()->setAttribute("csrf_token", bin2hex(random_bytes(24)));
    }

    /**
     * Vide les erreurs après l'affichage du site
     */
    public function cleanFlashMessage() : void{
        ($this->request->getSession()->existAttribute('flash_success')) ?? $this->request->getSession()->deleteAttribute('flash_success');
        ($this->request->getSession()->existAttribute('flash_error')) ?? $this->request->getSession()->deleteAttribute('flash_success');
    }
    public function isAdmin() : bool{
        return $this->isLogged() && $this->request->getSession()->getAttribute('user_role') == ROLE_ADMIN;
    }

    public function isPartner() : bool{
        return $this->isLogged() && $this->request->getSession()->getAttribute('user_role') == ROLE_PARTNER;
    }

    public function isStructure() : bool{
        return $this->isLogged() && $this->request->getSession()->getAttribute('user_role') == ROLE_STRUCTURE;
    }

    /**
     * Retourne un bool pour savoir si l'utilisateur est connecté
     */
    public function isLogged() : bool
    {
        return $this->request->getSession()->existAttribute("user_id");
    }
}