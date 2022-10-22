<?php
/**
 * Ce fichier sert de modèle pour afficher la liste des structures
 */
    $total_page = ceil($total_partner / NUMBER_ITEM_PER_PAGE);
    echo Application\Core\Helper::paginate($total_page, $current_page, URI_ROOT.'/partner/list/');
?>
        <div class="mb-2">
            <ol class="list-group">
                <?php
                 foreach ($partner_list as $partner) {
                     echo "\r\n".'
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <img src="https://www.gravatar.com/avatar/'.md5('partner'.$partner->partner_id).'?s=50&r=pg&d=robohash" width="50" height="50" />
                    <div class="ms-2 me-auto">
                        <div class="fw-bold"><a href="'.URI_ROOT.'/partner/information/'.$partner->partner_id.'">'.$this->cleanHTML($partner->partner_name).'</a></div>
                        Addresse : '.$this->cleanHTML($partner->user_address).' 
                    </div>
                    <div class="row align-items-center p-3">
                        <button class="btn badge btn-'.(($partner->is_active) ? 'success' : 'danger').'" id="partnerID-'.$partner->partner_id.'" data-bs-toggle="modal" data-bs-target="#EnableDisablePartner" data-bs-partner-name="'.$this->cleanHTML($partner->partner_name).'" data-bs-partner-id="'.$partner->partner_id.'" data-bs-partner-status="'.(($partner->is_active) ? '0' : '1').'">'.(($partner->is_active) ? 'Actif' : 'Inactif').'</button>
                    </div>
                </li>'."\r\n";
                 }
                 ?>
            </ol>  
        </div>
<?php echo Application\Core\Helper::paginate($total_page, $current_page, URI_ROOT.'/partner/list/');?>
