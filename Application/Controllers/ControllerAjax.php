<?php

use Application\Core\ControllerSecured;
use Application\Models\Partners;
use Application\Models\Structures;
use Application\Models\User;

/**
 * Contrôleur des requêtes en ajax 
 */
class ControllerAjax extends ControllerSecured {

    private $users;
    private Partners $partners;
    private Structures $structures;
    
    /**
     * Pour la recherche
     * @var type
     */
    private $page;
    private $search;
    
    public function __construct() {
    
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
        {    
            $this->users = new User();
            $this->partners = new Partners();
            $this->structures = new Structures();
        }
        else{
           die("Sorry this is not allowed");
        }
    }
    
    /**
     * Index est obligatoire de Controller, qui a une méthode abstract index afin d'être sûr de toujours l'avoir
     */
    public function index(){}

    
    public function listing(){
         
    }
    
    /**
     * Tout le système de recherche passe par se controller
     */
    public function search() {
        // On vérifie qu'un type de recherche à bien été passé
        if($this->request->existParameter('type')){
            
            $this->search = ($this->request->existParameter('search') && $this->request->getParameter('search')) ? $this->request->getParameter('search') : '';
            $this->page = ($this->request->existParameter('page') && is_numeric($this->request->getParameter('page'))) ? $this->request->getParameter('page') : 0;
            $this->active = ($this->request->existParameter('active') && is_numeric($this->request->getParameter('active'))) ? (($this->request->getParameter('active') >=1) ? true : false) : null;
            
            $type = $this->request->getParameter('type');
            if($type == 'partner'){
                self::searchPartner();
            }
            else if ($type == 'structure'){
                self::searchStructure();
            }
            else{
                echo 'no no rien';
            }
        }
        // On à pas de type dans les recherches
        else{
            $this->redirect('/');
        }
    }

    private function searchPartner() : string {
        // Requête pour rechercher tous les partenaires avec les options possibles (terme de la recherche, la page, actif/non actif/ et null pour ignorer)
        $result = $this->partners->search($this->search, $this->page, $this->active);
        $this->generateView(
            // paramètre à envoyé à la vue
            array(
                'partnerList' => $result
            )  
            // vue à affiché     
            , 'Partner'
            // On ne recherche pas la vue dans le dossier vue du Controller on lui passe le dossier de la vue que l'on souhaite.
            , 'Partner'
            // on change aussi de layout pour un autre complètement vide
            , 'LayoutEmpty'
        );
    
    }
    
    private function searchStructure() {
        // Requête pour rechercher toutes les structures avec les options possibles (terme de la recherche, la page, actif/non actif/ et null pour ignorer)
        $result = $this->structures->search($this->search, $this->page, $this->active);
        $this->generateView(
            // parameter à envoyé à la vue
            array(
                'structures_list' => $result
            )  
            // vue à affiché     
            , 'structureList'
            // On ne recherche pas la vue dans le dossier vue du Controller on lui passe le dossier de la vue que l'on souhaite.
            , 'Structure'
            // on change aussi de layout pour un autre complètement vide
            , 'LayoutEmpty'
        );
    }
}