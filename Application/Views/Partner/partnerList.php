<?php
//Simple vérification que l'on n'accède pas à ce fichier directement depuis le navigateur
if(!defined('URI_ROOT')) die('heu non désolé :)');

/**
 * Ce fichier sert de modèle pour afficher la liste des structures
 */
if(!empty($total_partner) && !empty($partner_list) && !empty($partner_list_user)) :
    $total_page = ceil($total_partner / NUMBER_ITEM_PER_PAGE);
    echo Application\Core\Helper::paginate($total_page, $current_page, URI_ROOT.'/partner/list/');
?>
        <div class="mb-2">
            <ol class="list-group">
                <?php
                if(count($partner_list) >= 1) {
                    foreach ($partner_list as $partner) {
                        $user = $partner_list_user[$partner->getUserId()];
                        echo "\r\n" . '
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <img src="https://www.gravatar.com/avatar/' . md5('partner' . $partner->getPartnerId()) . '?s=50&r=pg&d=robohash" width="50" height="50" />
                    <div class="ms-2 me-auto">
                        <div class="fw-bold"><a href="' . URI_ROOT . '/partner/information/' . $partner->getPartnerId() . '">' . $this->cleanHTML($partner->getPartnerName()) . '</a></div>
                        Addresse : ' . $this->cleanHTML($user->getUserAddress()) . ' 
                    </div>
                    <div class="row align-items-center p-3">
                        <button class="btn badge btn-' . (($partner->getPartnerActive()) ? 'success' : 'danger') . '" id="partnerID-' . $partner->getPartnerId() . '" 
                                data-bs-toggle="modal" 
                                data-bs-target="#EnableDisablePartner" 
                                data-bs-partner-name="' . $this->cleanHTML($partner->getPartnerName()) . '" 
                                data-bs-partner-id="' . $partner->getPartnerId() . '" 
                                data-bs-partner-status="' . (($partner->getPartnerActive()) ? '0' : '1') . '">' . (($partner->getPartnerActive()) ? 'Actif' : 'Inactif') . '</button>
                    </div>
                </li>' . "\r\n";
                    }
                }
                else{
                    echo '<li class="list-group-item d-flex justify-content-between align-items-start">Actuellement Aucun partenaire inscrit</li>';
                }
                 ?>
            </ol>  
        </div>
<?php echo Application\Core\Helper::paginate($total_page, $current_page, URI_ROOT.'/partner/list/');
endif;
