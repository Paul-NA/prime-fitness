<?php
//Simple vérification que l'on n'accède pas à ce fichier directement depuis le navigateur
if(!defined('URI_ROOT')) die('heu non désolé :)');

/**
 * Ici on doit afficher la liste des services sur la page partenaire et sur la page structure
 */

// on doit séparer les services en 2 liste pour le visuel, on va donc faire un petit système pour check par modulo
if(!empty($services_partner)){
    $i = 0;
    $listA = '';
    $listB = '';
    foreach($services_partner as $partnerService){

        //on regarde si la structure possède l'option
        $service = (array_key_exists($partnerService->getServiceId() , $services_list)) ? $services_list[$partnerService->getServiceId()] : null;

        //on regarde si la structure possède l'option
        $service_structure = (array_key_exists($partnerService->getPartnerServiceId() , $services_structure)) ? $services_structure[$partnerService->getPartnerServiceId()] : null;


        $partner_active = $partnerService->getPartnerServiceActive();
        $active_alert = (!$partner_active);
        $alert = (($active_alert) ? '<i class="bi bi-exclamation-triangle" title="cette option est désactivé dans le partner"> </i> ' : '' );
        $text = '
                            <li class="list-group-item d-flex justify-content-between align-items-start fw-semibold " >
                                <div class="float-start">'.$this->cleanHTML($service->getServiceName()).'</div>
                                <div class="justify-content-end">
                                    <button class="btn badge btn-'.(($service_structure !== null && $partner_active) ? 'success' : ((!$partner_active) ? 'warning' : 'danger')).'"'.
                                    (($current_user->getRoleId() == ROLE_ADMIN)
                                            ?
                                        ' id="partnerID-'.$service->getServiceId().'" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#EnableDisableService" 
                                        data-bs-csrf="'.$csrf_token.'"
                                        data-bs-service-name="'.$this->cleanHTML($service->getServiceName()).'" 
                                        data-bs-service-id="'.$partnerService->getPartnerServiceId().'" 
                                        data-bs-service-type="structure"
                                        data-bs-service-type-id="'.$structure_info->getStructureId().'"
                                        data-bs-service-status="'.( $service_structure !== null ? '0' : '1').'"' : '').'>'.$alert.(($service_structure) ? ((!$partner_active) ? 'Désactivé' : 'Actif') : 'Inactif').'
                                    </button>
                                </div>
                            </li>';
        ($i%2) ? $listB .=$text : $listA .= $text;
        ++$i;
    }
    echo '
            <div class="row g-3">
                <div class="col-lg-6">
                    <ul class="list-group mb-3  ">
                        '.$listA.'
                    </ul>
                </div>
                
                <div class="col-lg-6">
                    <ul class="list-group mb-3">
                        '.$listB.'
                    </ul>
                </div>
            </div>';
}
else{
?>
            <div class="row g-3">
                <div class="col">
                    <ul class="list-group mb-3 ">
                        <li class="list-group-item d-flex justify-content-between align-items-center">Actuellement aucun service</li>
                    </ul>
                </div>
            </div>

<?php
}?>
