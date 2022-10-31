<?php
//Simple vérification que l'on n'accède pas à ce fichier directement depuis le navigateur
if(!defined('URI_ROOT')) die('heu non désolé :)');

$this->titre = 'Prime-Fitness - '. $this->cleanHTML($structure_info->getStructureName()) . ' information';
/**
 * Cette page require un fichier Css personnel
 */
if($current_user->getRoleId() == ROLE_ADMIN)
    array_push($this->jsFiles, URI_ROOT.'/Assets/Javascript/application.js');

include PATH_VIEW.'_header.php';
?>

<main>
    <div class="album py-5">
        <div class="container">
            <div class="clearfix  bg-warning p-3 my-3 rounded shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="me-3 bi bi-person-bounding-box float-start" viewBox="0 0 16 16" >
                    <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5z"/>
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                </svg>
                <div class="float-start">
                    <h1 class="h6 mb-0 lh-1"><?php echo $this->cleanHTML($structure_info->getStructureName());?></h1>
                    <small class="d-none d-sm-inline"">Information partenaire</small>
                </div>
                <?php
                if($current_user->getRoleId() == ROLE_ADMIN) :?>
                    <button type="button" class="btn btn-danger display-inline float-end mx-1" style="float: right;"
                            data-bs-toggle="modal"
                            data-bs-target="#DeleteUser"
                            data-bs-csrf="<?php echo $csrf_token?>"
                            data-bs-userId="<?php echo $user_structure_info->getUserId();?>">
                        <i class="bi bi-x-square"></i><span class="d-none d-sm-inline"> Supprimer</span>
                    </button>
                    <button type="button" class="btn btn-primary display-inline" style="float: right;"
                            data-bs-toggle="modal"
                            data-bs-target="#EditStructure"
                            data-bs-csrf="<?php echo $csrf_token?>"
                            data-bs-structureId="<?php echo $structure_info->getStructureId();?>"
                            data-bs-firstname="<?php echo $this->cleanHTML($user_structure_info->getUserFirstName());?>"
                            data-bs-lastname="<?php echo $this->cleanHTML($user_structure_info->getUserLastName());?>"
                            data-bs-phone="<?php echo $this->cleanHTML($user_structure_info->getUserPhone());?>"
                            data-bs-address="<?php echo $this->cleanHTML($user_structure_info->getUserAddress());?>"
                            data-bs-email="<?php echo $this->cleanHTML($user_structure_info->getUserMail());?>"
                            data-bs-socialName="<?php echo $this->cleanHTML($structure_info->getStructureName());?>"
                            data-bs-userStatus="<?php echo (($user_structure_info->isUserActive()) ? 1 : 0);?>"
                            data-bs-structureStatus="<?php echo (($structure_info->getStructureActive()) ? 1 : 0);?>">
                        <i class="bi bi-pencil-square"></i><span class="d-none d-sm-inline"> éditer la structure</span>
                    </button>
                <?php endif; ?>
            </div>

            <?php
            if(!empty($form_error)){
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-square-fill"></i> <strong>Une erreur est survenue!</strong>  '.$_SESSION['form_error'].' 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
            if(!empty($form_success)){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-square-fill"></i> '.$_SESSION['form_success'].' 
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
            ?>

            <div clas="row">
                <div class="col"> 
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <h5 class="card-title">Contact Information</h5>
                                        <p class="card-text">
                                            <b>Nom :</b> <?php echo $this->cleanHTML($user_structure_info->getUserLastName());?><br />
                                            <b>Prénom :</b> <?php echo $this->cleanHTML($user_structure_info->getUserFirstName());?><br />
                                            <b>Téléphone :</b> <?php echo $this->cleanHTML($user_structure_info->getUserPhone());?><br />
                                            <b>Mail :</b> <?php echo $this->cleanHTML($user_structure_info->getUserMail());?><br />
                                            <b>Status user partner: </b> <?php echo (($user_structure_info->isUserActive()) ? '<b style="color: green">Actif</b>' : '<b   style="color: red">Inactif</b>');?><br />
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <h5 class="card-title">Structure Information</h5>
                                        <p class="card-text">
                                            <b>Nom :</b> <?php echo $this->cleanHTML($structure_info->getStructureName());?><br />
                                            <b>Adresse :</b> <?php echo $this->cleanHTML($user_structure_info->getUserAddress());?><br />
                                            <b>Status partner: </b> <?php echo (($structure_info->getStructureActive()) ? '<b style="color: green">Actif</b>' : '<b   style="color: red">Inactif</b>');?><br />
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>

            <div class="d-flex align-items-center p-3 my-3 bg-warning rounded shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="me-3 bi bi-boxes float-start" viewBox="0 0 16 16">
                    <path d="M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434L7.752.066ZM4.25 7.504 1.508 9.071l2.742 1.567 2.742-1.567L4.25 7.504ZM7.5 9.933l-2.75 1.571v3.134l2.75-1.571V9.933Zm1 3.134 2.75 1.571v-3.134L8.5 9.933v3.134Zm.508-3.996 2.742 1.567 2.742-1.567-2.742-1.567-2.742 1.567Zm2.242-2.433V3.504L8.5 5.076V8.21l2.75-1.572ZM7.5 8.21V5.076L4.75 3.504v3.134L7.5 8.21ZM5.258 2.643 8 4.21l2.742-1.567L8 1.076 5.258 2.643ZM15 9.933l-2.75 1.571v3.134L15 13.067V9.933ZM3.75 14.638v-3.134L1 9.933v3.134l2.75 1.571Z"/>
                </svg>
                <div class="lh-1s">
                    <h1 class="h6 mb-0 lh-1"> Vos Services</h1>
                    <small> Liste des services que possède votre structure</small>
                </div>
            </div>

            <?php
            /**
             * On charge le modèle utilisé pour afficher la liste des services de la structure
             */
            include PATH_VIEW.'Services/structureServicesList.php';
            ?>
    </div>
  </div>

</main>

<?php
/**
 * On charge le modèle utilisé pour afficher la liste des services de la structure
 */
if($current_user->getRoleId() == ROLE_ADMIN) {
    include PATH_VIEW . 'Modals/SearchModal.php';
    include PATH_VIEW . 'Modals/EnableDisableServiceModal.php';
    include PATH_VIEW . 'Modals/EnableDisableStructureModal.php';
    include PATH_VIEW . 'Modals/DeleteUserModal.php';
    include PATH_VIEW . 'Modals/EditStructureModal.php';
}
?>
