<?php

use Application\Core\ControllerSecuredAdmin;
use Application\Models\Partner;
use Application\Models\Structure;
use Application\Models\User;

/**
 * Contrôleur des requêtes en ajax pour administrateur uniquement
 */
class ControllerAjax extends ControllerSecuredAdmin {

    private $users;
    private Partner $partners;
    private Structure $structures;

    public function __construct() {
        if(!empty($_SERVER['HTTP_SEARCH_HEADER']) && $_SERVER['HTTP_SEARCH_HEADER'] == 'AjaxSearchRequest' )
        {    
            $this->users = new User();
            $this->partners = new Partner();
            $this->structures = new Structure();
        }
        else{
           die("Sorry this is not allowed");
        }

    }
    
    /**
     * Index est obligatoire de Controller, qui a une méthode abstract index afin d'être sûr de toujours l'avoir
     */
    public function index(){}

    
    /**
     * Tout le système de recherche passe par se controller
     */
    public function search() {
        // On vérifie qu'un type de recherche à bien été passé
        //var_dump($_POST);

        $search = $this->request->existParameter('search');
        $status = $this->request->existParameter('status');
        $type = $this->request->existParameter('type') ;

        if($search && $status && $type){

            $_search = $this->request->getParameter('search');
            $_status = $this->request->getParameter('status');
            $_type = $this->request->getParameter('type');

            if($_type == 'partner'){
                self::searchPartner( $_status, $_search);
            }
            else if ($_type == 'structure'){
                self::searchStructure( $_status, $_search);
            }
            else if ($_type == 'user'){
                self::searchStructure();
            }
            else{
                echo 'désolé je n\'ai pas compris la question';
            }
        }
        // On à pas de type dans les recherches
        else{
            echo '<div>Désolé le formulaire n\'est pas valide';
        }
    }

    private function searchPartner(string $status, string $search) {
        $page = 0;
        /**
         * On a récupéré notre liste de partenaire et on à retourné la liste avec comme clé de tableau les user_id pour selectioner les users
         */
        $partner = new Partner();
        $listPartner = $partner->search($search, $page, (($status == 'actif') ? true : (($status == 'inactif') ? false : null)));

        /**
         * On récupère les clés sur la liste
         */
        $userKey = array_keys($listPartner);
        $u = new User();
        $userList = (count($userKey) > 0) ? $u->getUserListByUsersId($userKey) : [];

        $this->generateView(
            // paramètre à envoyé à la vue
            array(
                'current_page' => $page,
                'partner_list' => $listPartner,
                'partner_list_user' => $userList,
            )
            // vue à affiché     
            , 'searchListPartner'
            // On ne recherche pas la vue dans le dossier vue du Controller on lui passe le dossier de la vue que l'on souhaite.
            , 'Ajax'
            // on change aussi de layout pour un autre complètement vide
            , 'LayoutEmpty'
        );
    }
    
    private function searchStructure(string $status, string $search) {
        $page = 0;
        /**
         * On a récupéré notre liste de partenaire et on à retourné la liste avec comme clé de tableau les user_id pour selectioner les users
         */
        $structures = new Structure();
        $listStructures = $structures->search($search, $page, (($status == 'actif') ? true : (($status == 'inactif') ? false : null)));

        /**
         * On récupère les clés sur la liste
         */
        $userKey = array_keys($listStructures);
        $u = new User();
        $usersList = (count($userKey) > 0) ? $u->getUserListByUsersId($userKey) : [];

        $this->generateView(
        // paramètre à envoyé à la vue
            array(
                'current_page' => $page,
                'structures_list' => $listStructures,
                'structures_list_users' => $usersList,
            )
            // vue à affiché
            , 'searchListStructure'
            // On ne recherche pas la vue dans le dossier vue du Controller on lui passe le dossier de la vue que l'on souhaite.
            , 'Ajax'
            // on change aussi de layout pour un autre complètement vide
            , 'LayoutEmpty'
        );
    }
}