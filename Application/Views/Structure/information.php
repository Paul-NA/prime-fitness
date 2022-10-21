<?php

$this->titre = 'Prime-Fitness - '. $this->cleanHTML($structure_info->getStructureName()) . ' information';
/**
 * Cette page require un fichier Css personnel
 */

array_push($this->jsFiles, URI_ROOT.'/Assets/Javascript/application.js');

$this->cssFiles = ['https://getbootstrap.com/docs/5.2/examples/offcanvas-navbar/offcanvas.css'];
include PATH_VIEW.'_header.php';
?>

<main>
    <div class="album py-5">
        <div class="container">
            <div class="d-flex align-items-center p-3 my-3 bg-warning rounded shadow-sm">
                <i class="bi bi-person-bounding-box fs-1 px-2"></i>
                <div class="lh-1 col-md-9" >
                    <h1 class="h6 mb-0 lh-1"> <?php echo $this->cleanHTML($structure_info->getStructureName());?></h1>
                    <small> Information partenaire</small>
                </div>
                <?php
                if($user_info->getRoleId() == ROLE_ADMIN) :?>
                    <div class="col col-md-2">
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
                                data-bs-structureStatus="<?php echo (($structure_info->getIsActive()) ? 1 : 0);?>">
                            <i class="bi bi-pencil-square"></i><span class="d-none d-sm-inline"> éditer la structure</span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
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
                                            <b>Status partner: </b> <?php echo (($structure_info->getIsActive()) ? '<b style="color: green">Actif</b>' : '<b   style="color: red">Inactif</b>');?><br />
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
              
            <div class="d-flex align-items-center p-3 my-3 bg-warning rounded shadow-sm">
                <i class="bi bi-boxes fs-1 px-2"></i>
                <div class="lh-1 col-md-9">
                    <h1 class="h6 mb-0 lh-1">Services</h1>
                    <small>Voici la liste des services que possède votre compte</small>
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
if($user_info->getRoleId() == ROLE_ADMIN) {
    include PATH_VIEW . 'Modals/EnableDisableServiceModal.php';
    include PATH_VIEW . 'Modals/EnableDisableStructureModal.php';
    include PATH_VIEW . 'Modals/EditStructureModal.php';
}
?>
