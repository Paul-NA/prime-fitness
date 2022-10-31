<?php

use Application\Core\ControllerSecured;
use Application\Models\Partner;
use Application\Models\PartnerService;
use Application\Models\Service;
use Application\Models\Structure;
use Application\Models\StructureService;
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
        $currentUser = $user->getUser($session->getAttribute("user_id"));

        // on vérifie que l'on ait bien un id structure
        if($this->request->existParameter('id') && is_numeric($this->request->getParameter('id'))){

            // On récupère les informations de la structure
            $structure = new Structure();
            $structureInst = $structure->getStructure($this->request->getParameter('id'));

            if($structureInst->getStructureId() > 0 && ($currentUser->getRoleId() == ROLE_ADMIN || $structureInst->getUserId() == $currentUser->getUserId())){
                // On récupère les informations du partenaire (on doit savoir s'il est actif ou non)
                $partner = new Partner();
                $partnerInst = $partner->getPartnerByPartnerId($structureInst->getPartnerId());
                // User partner
                $userStruc = new User();
                $structureUser = $userStruc->getUser($structureInst->getUserId());

                // On doit vérifier si l'id de la structure existe sinon on redirigera vers la liste des partenaires
                $services = new Service();
                $servicesList = $services->getAllServices();

                // Les services du partenaire
                $partnerService = new PartnerService();
                $servicesPartner = $partnerService->getPartnerServiceListByPartnerId($partnerInst->getPartnerId());

                // les services de la structure
                $structurerService = new StructureService();
                $servicesStructure = $structurerService->getStructureServiceListByStructureId($structureInst->getStructureId());

                // On génère un csrf
                $this->genCsrf();
                // On doit vérifier si l'id de la structure existe sinon on redirigera vers la liste des partenaires
                $this->generateView(
                    // paramètre à envoyé à la vue
                    array(
                        'user_structure_info' => $structureUser, // un User
                        'structure_info' => $structureInst,
                        'partner_info' => $partnerInst,
                        'csrf_token' => $this->request->getSession()->getAttribute('csrf_token'),
                        'action_id' => $this->request->getParameter('id'),

                        'services_list' => $servicesList,
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