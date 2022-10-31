<?php

use Application\Core\ControllerSecured;
use Application\Models\Partner;
use Application\Models\Structure;

/**
 * Contrôleur de la page d'accueil
 */
class ControllerHome extends ControllerSecured {

    /**
     * Redirection vers la bonne page
     */
    public function index() : void {

        $session = $this->request->getSession();
        // si l'utilisateur à un role admin
        if($this->isAdmin()){
            // on va récupérer l'id de sa structure et redirigé vers l'id ?
            $this->redirect('/partner/list');
        }
        // si l'utilisateur à un role partenaire
        else if($this->isPartner()){
            // on va récupérer l'id du partenaire et redirigé vers sa page
            $partner = new Partner();
            $partenaire = $partner->getPartnerByUserId($session->getAttribute('user_id'));
            
            $this->redirect('/partner/information/'.$partenaire->getPartnerId());
        }
        // si l'utilisateur à un role structure
        else if($this->isStructure()){
            // on va récupérer l'id de sa structure et redirigé vers sa structure
            $structure = new Structure();
            $returnStructure = $structure->getStructureByUserId($session->getAttribute('user_id'));

            $this->redirect('/structure/information/'.$returnStructure->getStructureId());
        }
        // ni admin, ni partenaire, ni structure il y à un gros soucis
        else{
            $this->generateView(array('title' => 'Erreur bizarre', 'msgError' => 'Oups : il y à comme un problème, l\'utilisateur n\'a pas de roles admin, partenaire, ou structure ...'), 'error', 'error');
        }
    }
}