<?php
//Simple vérification que l'on n'accède pas à ce fichier directement depuis le navigateur
if(!defined('URI_ROOT')) die('heu non désolé :)');

/**
 * Ici on doit afficher la liste des services sur la page partenaire et sur la page structure
 */

// on doit séparer les services en 2 liste pour le visuel, on va donc faire un petit système pour check par modulo
if(!empty($services_list)){
$i = 0;
$listA = '';
$listB = '';

    foreach($services_list as $service){
        $text = '
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                '.$this->cleanHTML($service->service_name).'
                                <div class="form-check form-switch form-check-reverse col-4">
                                    <label class="form-check-label" for="flexSwitchCheck'.$service->service_id.'">Active : </label>
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheck'.$service->service_id.'" '.(($service->is_active) ? 'checked' : '').'>
                                </div>
                            </li>';
        ($i%2) ? $listB .=$text : $listA .= $text;
        ++$i;
    }
    echo '
            <div class="row g-3">
                <div class="col-lg-6">
                    <ul class="list-group mb-3 ">
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
echo '<pre>';
print_r(Application\Core\Database::showQuery());
echo '</pre>';