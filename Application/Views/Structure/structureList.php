
            <ol class="list-group">
                <?php
                if(!empty($structures_list) && count($structures_list) >= 1) {
                    foreach ($structures_list as $structure) {
                        echo "\r\n" . '
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <img src="https://www.gravatar.com/avatar/' . md5('structure' . $structure->structure_id) . '?s=50&r=pg&d=robohash" width="50" height="50" />
                    <div class="ms-2 me-auto">
                        <div class="fw-bold"><a href="' . URI_ROOT . '/structure/information/' . $structure->structure_id . '">' . $this->cleanHTML($structure->structure_name) . '</a></div>
                        Addresse : ' . $this->cleanHTML($structure->user_address) . ' 
                    </div>
                    <div class="row align-items-center p-3">
                        <button class="btn badge btn-' . (($structure->is_active) ? 'success' : 'danger') . '" data-bs-toggle="modal" data-bs-target="#EnableDisableStructure" data-bs-structureName="' . $this->cleanHTML($structure->structure_name) . '" data-bs-structureId="' . $structure->structure_id . '" data-bs-structureActive="' . (($structure->is_active) ? '0' : '1') . '" data-bs-csrf="'.$csrf_token.'">' . (($structure->is_active) ? 'Actif' : 'Inactif') . '</button>
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
