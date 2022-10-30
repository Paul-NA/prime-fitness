<?php
//Simple vérification que l'on n'accède pas à ce fichier directement depuis le navigateur
if(!defined('URI_ROOT')) die('heu non désolé :)');
?>
            <ol class="list-group">
                <?php
                if(!empty($structures_list) && count($structures_list) >= 1) {
                    foreach ($structures_list as $structure) {
                        $structureUser = (!empty($structures_users) && array_key_exists($structure->getUserId() , $structures_users)) ? $structures_users[$structure->getUserId()] : null;

                        echo "\r\n" . '
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <img src="https://www.gravatar.com/avatar/' . md5('structure' . $structure->getStructureId()) . '?s=50&r=pg&d=robohash" width="50" height="50" />
                    <div class="ms-2 me-auto">
                        <div class="fw-bold"><a href="' . URI_ROOT . '/structure/information/' . $structure->getStructureId() . '">' . $this->cleanHTML($structure->getStructureName()) . '</a></div>
                        Addresse : ' . $this->cleanHTML($structureUser->getUserAddress()) . ' 
                    </div>
                    <div class="row align-items-center p-3">
                        <button class="btn badge btn-' . (($structure->getStructureActive()) ? 'success' : 'danger') . '" 
                            data-bs-toggle="modal" 
                            data-bs-target="#EnableDisableStructure" 
                            data-bs-structureName="' . $this->cleanHTML($structure->getStructureName()) . '" 
                            data-bs-structureId="' . $structure->getStructureId() . '" 
                            data-bs-structureActive="' . (($structure->getStructureActive()) ? '0' : '1') . '" 
                            data-bs-csrf="'.$csrf_token.'">' . (($structure->getStructureActive()) ? 'Actif' : 'Inactif') . '</button>
                    </div>
                </li>' . "\r\n";
                    }
                }
                else{
                    ?>
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        Actuellement aucune structure
                    </div>
                </li>
                <?php
                }
                 ?>
            </ol>
