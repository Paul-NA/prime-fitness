<?php
/**
 * Ce fichier sert de vue pour les structures
 */
?>
        <div class="mb-2">
            <ol class="list-group">
                <?php
                if(count($structures_list) >= 1) {
                    foreach ($structures_list as $structure) {
                        $user = $structures_list_users[$structure->getUserId()];
                        echo "\r\n" . '
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <img src="https://www.gravatar.com/avatar/' . md5('partner' . $structure->getStructureId()) . '?s=50&r=pg&d=robohash" width="50" height="50" />
                    <div class="ms-2 me-auto">
                        <div class="fw-bold"><a href="' . URI_ROOT . '/partner/information/' . $structure->getStructureId() . '">' . $this->cleanHTML($structure->getStructureName()) . '</a></div>
                        Addresse : ' . $this->cleanHTML($user->getUserAddress()) . ' 
                    </div>
                    <div class="row align-items-center p-3"><b>' . (($structure->getStructureActive()) ? '<span class="text-bg-success p-2 rounded-3">Actif</span>' : '<span class="text-bg-danger p-2 rounded-3">Inactif</span>') . '</b></div>
                </li>' . "\r\n";
                    }
                }
                else{
                    echo '<li class="list-group-item d-flex justify-content-between align-items-start">Aucun partenaire trouvez</li>';
                }
                 ?>
            </ol>  
        </div>