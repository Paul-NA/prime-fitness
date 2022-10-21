<?php

use Application\Core\ControllerSecured;
use Application\Models\Partners;
use Application\Models\PartnersServices;
use Application\Models\Structures;
use Application\Models\StructuresServices;
use Application\Models\User;

/**
 * Contrôleur de la page Partenaire
 */
class ControllerStructure extends ControllerSecured {
    
    /**
     * Page /structure
     * Attention par défaut non accessible on redirige donc vers l'accueil qui va redirigé vers la bonne page
     */
    public function index() {$this->redirect('/');}

    /**
     * Page /structure/information/{structure_id:int}
     */
    public function information(){
        $session = $this->request->getSession();

        $user = new User();
        $user->getUSer($session->getAttribute("user_id"));

        // on vérifie que l'on ait bien un id structure
        if($this->request->existParameter('id') && is_numeric($this->request->getParameter('id'))){

            // On récupère les informations de la structure
            $structure = new Structures();
            $structure->setStructureId($this->request->getParameter('id'));
            $structureInst = $structure->getStructure();
            //$structureInst = $structure->getStructureByStructureId($this->request->getParameter('id'));

            // Si la structure existe l'id est supérieur à zéro  et que (l'utilisateur courant est admin ou que l'user_id de la structure appartient à l'utilisateur c
            if($structureInst->getStructureId() > 0 && ($user->getRoleId() == ROLE_ADMIN || $structureInst->getUserId() == $user->getUserId())){
                // On récupère les informations du partenaire (on doit savoir s'il est actif ou non)
                $partner = new Partners();
                $partnerInst = $partner->getPartnerByPartnerId($structureInst->getPartnerId());
                // User partner
                $structureUser = new User();
                $structureUser->getUser($structureInst->getUserId());

                // Les services du partenaire
                $partnerService = new PartnersServices();
                $servicesInst = $partnerService->getServiceStructure($partnerInst->partner_id, $structureInst->getStructureId());
                $servicesPartner = $partnerService->getServiceListByPartnerId($partnerInst->partner_id);

                // les services de la structure
                $structurerService = new StructuresServices();
                $servicesStructure = $structurerService->getServiceListByStructureId($structureInst->getStructureId());

                // On génère un csrf
                $this->genCsrf();
                // On doit vérifier si l'id de la structure existe sinon on redirigera vers la liste des partenaires
                $this->generateView(
                    // paramètre à envoyé à la vue
                    array(
                        'user_info' => $user,
                        'user_structure_info' => $structureUser, // un User
                        'structure_info' => $structureInst,
                        'partner_info' => $partnerInst,
                        'csrf_token' => $this->request->getSession()->getAttribute('csrf_token'),
                        'action_id' => $this->request->getParameter('id'),
                        'services_list' => $servicesInst,
                        'services_partner' => $servicesPartner,
                        'services_structure' => $servicesStructure
                    ));
            }
            // on redirige vers l'accueil pour une redirection automatique vers la bonne page par exemple une structure qui arriverait sur cette page
            else{
                $this->redirect('/');
            }
        }
        else{
            $this->redirect('/');
        }
    }
}