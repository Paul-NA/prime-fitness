<?php

use Application\Core\ControllerSecured;
use Application\Models\Partners;
use Application\Models\PartnersServices;
use Application\Models\Services;
use Application\Models\Structures;
use Application\Models\User;

/**
 * Contrôleur de la page Partenaire
 */
class ControllerPartner extends ControllerSecured {

    private User $user;

    public function __construct() {
        $this->user = new User();
    }
    
    /**
     * Page /partner
     * Cette page n'est pas accessible par défaut, on va rediriger vers l'index qui redirigera vers la bonne page
     */
    public function index() {$this->redirect('/');}

    /**
     * page /partner/information/{partenaire_id:int}
     */
    public function information(){

        $currentUser = new User();
        $currentUser->getUSer($this->request->getSession()->getAttribute("user_id"));

        // on vérifie que l'on ait bien un id partenaire
        if($this->request->existParameter('id') && is_numeric($this->request->getParameter('id'))){
            $partner = new Partners();
            $infosPartner = $partner->getPartnerByPartnerId($this->request->getParameter('id'));
            // Si le partenaire existe et que l'on est admin ou un partenaire et que c'est notre page
            if($partner->getPartnerId() > 0 && (($currentUser->getRoleId() == ROLE_ADMIN) || ($currentUser->getRoleId() == ROLE_PARTNER && $currentUser->getUserId() == $partner->getUSerId()))){

                // User partner
                $partnerUser = new User();
                $partnerUser->getUser($infosPartner->user_id);

                // On doit vérifier si l'id de la structure existe sinon on redirigera vers la liste des partenaires
                $services = new Services();
                $servicesList = $services->getAllServices();

                // On doit vérifier si l'id de la structure existe sinon on redirigera vers la liste des partenaires
                $partnerService = new PartnersServices();
                $servicesPartner = $partnerService->getServiceListByPartnerId($infosPartner->partner_id);

                // On doit récupérer la liste des structures du partenaire
                $structure = new Structures();
                // On génère un csrf
                $this->genCsrf();

                // On va ajouter les form_session à la vue avant de les reset
                $mess_success = $this->request->getSession()->existAttribute('form_success') ? $this->request->getSession()->getAttribute('form_success') : '';
                $mess_error = $this->request->getSession()->existAttribute('form_error') ? $this->request->getSession()->getAttribute('form_error') : '' ;

                $this->generateView(
                    // paramètre à envoyé à la vue
                    array(
                        'user_info' => $currentUser,
                        'user_partner_info' => $partnerUser,
                        'partner_info' => $infosPartner,
                        'form_success'=> $mess_success,
                        'form_error'=> $mess_error,
                        'action_id' => $this->request->getParameter('id'),
                        'csrf_token' => $this->request->getSession()->getAttribute('csrf_token'),
                        'services_partner' => $servicesPartner,
                        'services_list' => $servicesList,
                        'structures_list' => $structure->getStructureListByPartnerId($infosPartner->partner_id)
                    ));

                $this->request->getSession()->setAttribute("form_success", '');
                $this->request->getSession()->setAttribute("form_error", '');
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
 
    /**
     * /partner/list
     * /partner/list/(int){pageNumber}
     */
    public function list(){
        if($this->request->getSession()->getAttribute('user_role') == ROLE_ADMIN) {

            $partner = new Partners();
            $totalPage = ceil($partner->getTotalPartner() / NUMBER_ITEM_PER_PAGE);

            // On génère un csrf
            $this->genCsrf();

            $page = (($this->request->existParameter('id') && is_numeric($this->request->getParameter('id')) && $this->request->getParameter('id') > 0) ? $this->request->getParameter('id') - 1 : 0);

            if($page+1 <= $totalPage) {
                $this->generateView(
                    // paramètre à envoyé à la vue
                    array(
                        'total_partner' => $partner->getTotalPartner(),
                        'current_page' => $page,
                        'partner_list' => $partner->getAllPartner($page),
                        'csrf_token' => $this->request->getSession()->getAttribute('csrf_token')
                    ));
            }
            else{
                $this->redirect('/'); // on va simplement redirigé
            }
        }
        else{
            $this->redirect('/');
        }
    }
}