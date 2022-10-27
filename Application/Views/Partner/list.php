<?php
$this->titre = "Liste des partenaires";
array_push($this->jsFiles, URI_ROOT.'/Assets/Javascript/application.js');

$this->cssFiles = ['https://getbootstrap.com/docs/5.2/examples/offcanvas-navbar/offcanvas.css'];
include PATH_VIEW.'_header.php';
 
?>
<main>
    <div class="album py-5">
        <div class="container">
            <div class="clearfix  bg-warning p-3 my-3  rounded shadow-sm ">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="me-3 bi bi-person-bounding-box float-start" viewBox="0 0 16 16" >
                    <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5z"/>
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                </svg>
                <div class="float-start">
                    <h1 class="h6 mb-0 lh-1">Liste des Partenaire</h1>
                    <small class="d-none d-sm-inline"">Trouvez ci-dessous la liste complète des partenaires</small>
                </div>
                <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#AddNewPartner" data-bs-csrf="<?php echo $csrf_token?>"><i class="bi bi-plus-square"></i> <span class="d-none d-sm-inline">Ajouter un partenaire</span></button>
            </div>

            <?php
            /**
             * On charge le modèle utilisé pour afficher les liste des structures
             */
            include PATH_VIEW.'Partner/partnerList.php';
            ?>
                  
        </div>
    </div>
</main>

<?php

include PATH_VIEW . 'Modals/SearchModal.php';
// Ajout du modal pour activer/désactiver un partenaire
include PATH_VIEW . 'Modals/EnableDisablePartnerModal.php';
// Ajout du modal pour Ajouter un nouveau partenaire
include PATH_VIEW.'Modals/AddPartnerModal.php';
?>

