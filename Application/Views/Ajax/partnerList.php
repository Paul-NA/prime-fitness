<?php
//Simple vérification que l'on n'accède pas à ce fichier directement depuis le navigateur
if(!defined('URI_ROOT')) die('heu non désolé :)');

/**
 * Ce fichier sert de modèle pour afficher la liste des structure
 */
?>
            <ol class="list-group">
                <?php
                 foreach ($structures_list as $structure) {
                     echo "\r\n".'
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="50" height="50" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#6f42c1"/><text x="50%" y="50%" fill="#6f42c1" dy=".3em">32x32</text></svg>
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">'.$structure->structure_name.' (Tel : <a href="tel:'.$structure->user_phone.'">'.$structure->user_phone.'</a>)</div>
                        Addresse : '.$structure->user_address.' 
                    </div>
                    <div class="row align-items-center p-3">
                        <button class="btn badge btn-'.(($structure->is_active) ? 'success' : 'danger').'" data-bs-toggle="modal" data-bs-target="#EnableDisableStructure" data-bs-name-structure="'.$structure->structure_name.'" data-bs-id-structure="'.$structure->structure_id.'" data-bs-status-structure="'.(($structure->is_active) ? '0' : '1').'">'.(($structure->is_active) ? 'Actif' : 'Inactif').'</button>
                    </div>
                </li>'."\r\n";
                 }
                 
                 ?>
            </ol>
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-end">
    <li class="page-item disabled">
      <a class="page-link">Previous</a>
    </li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item">
      <a class="page-link" href="#">Next</a>
    </li>
  </ul>
</nav>