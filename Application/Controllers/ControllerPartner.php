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

            // Si le partenaire existe et que l'on est admin ou un partenaire ou que c'est notre page
            if($infosPartner->getPartnerId() > 0 &&(($currentUser->getRoleId() == ROLE_ADMIN) || ($currentUser->getRoleId() == ROLE_PARTNER && $currentUser->getUserId() == $infosPartner->getUSerId()))){

                // User partner
                $partnerUser = new User();
                $partnerUser->getUser($infosPartner->getUserId());

                // On doit vérifier si l'id de la structure existe sinon on redirigera vers la liste des partenaires
                $services = new Services();
                $servicesList = $services->getAllServices();

                // On doit vérifier si l'id de la structure existe sinon on redirigera vers la liste des partenaires
                $partnerService = new PartnersServices();
                $servicesPartner = $partnerService->getPartnerServiceListByPartnerId($infosPartner->getPartnerId());

                // On doit récupérer la liste des structures du partenaire
                $structure = new Structures();
                $structureList = $structure->getStructureListByPartnerId($infosPartner->getPartnerId());

                //On récupère les clées des structures (structure_id)
                $userKey = array_keys($structureList);

                // Ici on va récupérer la liste des utilisateurs de chaque structure
                $structureUser = new User();
                $structureUserList = (count($userKey) >= 1) ? $structureUser->getUSerByUsersid($userKey) : [];

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
                        'services_list' => $servicesList,
                        'partner_services' => $servicesPartner,

                        'structures_list' => $structureList,
                        'structures_users' => $structureUserList
                    ));

                $this->request->getSession()->setAttribute("form_success", '');
                $this->request->getSession()->setAttribute("form_error", '');
            }
            // on redirige vers l'accueil pour une redirection automatique vers la bonne page par exemple une structure qui arriverait sur cette page
            else{ $this->generateView(array('title' => 'Hola', 'msgError' => 'Bien le bonjour aventurier, vous semblez un peu perdu, pour une raison sont toutes normale nous ne pouvons vous afficher cette page !'), 'error', 'error'); }
        }
        else{ $this->generateView(array('title' => 'Bin ça alors', 'msgError' => 'Il semblerait que le paramètre que vous avez entré ne soit pas un nombre numérique bizarre ;) !'), 'error', 'error'); }
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
            if(!$this->request->existParameter('id') || $this->request->existParameter('id') && is_numeric($this->request->getParameter('id'))){
                $page = (($this->request->existParameter('id') && $this->request->getParameter('id') > 0) ? $this->request->getParameter('id') - 1 : 0);

                /**
                 * On a récupéré notre liste de partenaire et on à retourné la liste avec comme clé de tableau les user_id pour selectioner les users
                 */
                $listPartner = $partner->searchB('', $page);

                /**
                 * On récupère les clés sur la liste
                 */
                $userKey = array_keys($listPartner);

                $u = new User();
                $userList = (count($userKey) > 0) ? $u->getUSerByUsersid($userKey) : [];

                if($page+1 <= $totalPage || $partner->getTotalPartner() == 0) {
                    $this->generateView(
                        // paramètre à envoyé à la vue
                        array(
                            'total_partner' => $partner->getTotalPartner(),
                            'current_page' => $page,
                            'partner_list' => $listPartner,
                            'partner_list_user' => $userList,
                            'csrf_token' => $this->request->getSession()->getAttribute('csrf_token')
                        ));
                }
                else{ $this->generateView(array('title' => 'Humm', 'msgError' => 'Mathématiquement il n\'est pas possible que cette url vous ai été proposé ;) '), 'error', 'error'); }
            }
            else{ $this->generateView(array('title' => 'Hop hop hop', 'msgError' => 'Houla ça ne ressemble pas trop à un chiffre/nombre passer en paramètre non ?'), 'error', 'error'); }
        }
        else{ $this->generateView(array('title' => 'Oh non!', 'msgError' => 'cette page ne vous est pas accessible !'), 'error', 'error'); }
    }
}