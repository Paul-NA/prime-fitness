<?php
/**
 * Ici on doit afficher la liste des services sur la page partenaire et sur la page structure
 */
// on doit séparer les services en 2 liste pour le visuel, on va donc faire un petit système pour check par modulo
if(!empty($services_list) && !empty($user_info) && !empty($csrf_token) ){
    $i = 0;
    $listA = '';
    $listB = '';
    foreach($services_list as $service){

        $service_partner = (!empty($services_partner) && array_key_exists($service->service_id , $services_partner)) ? $services_partner[$service->service_id] : null;
        $text = '
                            <li class="list-group-item d-flex justify-content-between align-items-start fw-semibold " >
                                <div class="float-start">'.$this->cleanHTML($service->service_name).'</div>
                                <div class="justify-content-end">'
                                    .(($user_info->getRoleId() == ROLE_ADMIN)
                                        ? // On est admin, on peut voir les boutons admin sinon simple affichage
                                            (($service_partner != null)
                                                // le service est installé
                                                ? '
                                                    <button class="btn badge btn-'.(($service_partner->is_active) ? 'success' : 'danger').'" id="partnerID-'.$service->service_id.'" data-bs-toggle="modal" data-bs-target="#EnableDisableService"  data-bs-csrf="'.$csrf_token.'" data-bs-service-name="'.$this->cleanHTML($service->service_name).'"  data-bs-service-id="'.$service->service_id.'" data-bs-service-type="partner" data-bs-service-type-id="'.$service_partner->partner_id.'" data-bs-service-status="'.(( $service_partner->is_active) ? 0 : 1).'">'.(($service_partner->is_active) ? 'Actif': 'Inactif').'</button> 
                                                    <button class="btn badge btn-danger" data-bs-toggle="modal" data-bs-target="#AddRemoveService" data-bs-service-name="'.$this->cleanHTML($service->service_name).'" data-bs-csrf="'.$csrf_token.'" data-bs-service-id="'.$service_partner->partner_service_id.'" data-bs-service-type="remove" data-bs-service-type-id="'.$service_partner->partner_id.'" >X</button>'
                                                // le service n'est pas installé
                                                : '<button class="btn badge btn-danger" data-bs-toggle="modal" data-bs-target="#AddRemoveService" data-bs-service-name="'.$this->cleanHTML($service->service_name).'" data-bs-csrf="'.$csrf_token.'" data-bs-service-id="'.$service->service_id.'" data-bs-service-type="add" data-bs-service-type-id="'.$partner_info->partner_id.'">Ajouter ce service</button>'
                                            )
                                        : // on est un utilisateur partenaire, on voit simplement les buttons sans option ou autre
                                            (($service_partner != null)
                                                ? '<button class="btn badge btn-success">Actif</button>'
                                                : '<button class="btn badge btn-danger">Non installer</button>'
                                            )
                                        ). '
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
}