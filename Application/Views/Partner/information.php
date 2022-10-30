<?php

$this->titre = 'Prime-Fitness - '. $this->cleanHTML($partner_info->getPartnerName()) . ' information';

array_push($this->jsFiles, URI_ROOT.'/Assets/Javascript/application.js');


include PATH_VIEW.'_header.php';

if(!empty($user_partner_info)) :
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
                    <h1 class="h6 mb-0 lh-1"><?php echo $this->cleanHTML($partner_info->getPartnerName());?></h1>
                    <small class="d-none d-sm-inline"">Information partenaire</small>
                </div>
                <?php
                if($user_info->getRoleId() == ROLE_ADMIN) :?>

                    <button type="button" class="btn btn-danger display-inline float-end mx-1" style="float: right;"
                            data-bs-toggle="modal"
                            data-bs-target="#DeleteUser"
                            data-bs-csrf="<?php echo $csrf_token?>"
                            data-bs-userId="<?php echo $partner_info->getUserId();?>">
                        <i class="bi bi-x-square"></i><span class="d-none d-sm-inline"> Supprimer</span>
                    </button>
                <button type="button" class="btn btn-primary display-inline  float-end" style="float: right;"
                        data-bs-toggle="modal"
                        data-bs-target="#EditPartner"
                        data-bs-csrf="<?php echo $csrf_token?>"
                        data-bs-partnerId="<?php echo $partner_info->getPartnerId();?>"
                        data-bs-firstname="<?php echo $this->cleanHTML($user_partner_info->getUserFirstName());?>"
                        data-bs-lastname="<?php echo $this->cleanHTML($user_partner_info->getUserLastName());?>"
                        data-bs-mail="<?php echo $this->cleanHTML($user_partner_info->getUserMail());?>"
                        data-bs-phone="<?php echo $this->cleanHTML($user_partner_info->getUserPhone());?>"
                        data-bs-address="<?php echo $this->cleanHTML($user_partner_info->getUserAddress());?>"
                        data-bs-socialName="<?php echo $this->cleanHTML($partner_info->getPartnerName());?>"
                        data-bs-userstatus="<?php echo (($user_partner_info->isUserActive()) ? 1 : 0);?>"
                        data-bs-partnerStatus="<?php echo (($partner_info->getPartnerActive()) ? 1 : 0);?>">
                    <i class="bi bi-pencil-square"></i><span class="d-none d-sm-inline"> éditer le partenaire</span>
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
                                            <b>Nom :</b> <?php echo $this->cleanHTML($user_partner_info->getUserLastName());?><br />
                                            <b>Prénom :</b> <?php echo $this->cleanHTML($user_partner_info->getUserFirstName());?><br />
                                            <b>Téléphone :</b> <?php echo $this->cleanHTML($user_partner_info->getUserPhone());?><br />
                                            <b>Mail :</b> <?php echo $this->cleanHTML($user_partner_info->getUserMail());?><br />
                                            <b>Status user partner: </b> <?php echo (($user_partner_info->isUserActive()) ? '<b style="color: green">Actif</b>' : '<b   style="color: red">Inactif</b>');?><br />
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <h5 class="card-title">Partenaire Information</h5>
                                        <p class="card-text">
                                            <b>Nom :</b> <?php echo $this->cleanHTML($partner_info->getPartnerName());?><br />
                                            <b>Adresse :</b> <?php echo $this->cleanHTML($user_partner_info->getUserAddress());?><br />
                                            <b>Status partner: </b> <?php echo (($partner_info->getPartnerActive()) ? '<b style="color: green">Actif</b>' : '<b   style="color: red">Inactif</b>');?><br />
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
                    <small> Liste des services que possède votre compte partenaire</small>
                </div>
            </div>

            <?php
            /**
             * On charge le modèle utilisé pour afficher les liste des structures
             */
            include PATH_VIEW.'Services/partnerServicesList.php';
            ?>

            <div class="clearfix  bg-warning p-3 my-3  rounded shadow-sm ">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="me-3 bi bi-shop float-start" viewBox="0 0 16 16">
                    <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976l2.61-3.045zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0zM1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5zM4 15h3v-5H4v5zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3zm3 0h-2v3h2v-3z"/>
                </svg>
                <div class="float-start">
                    <h1 class="h6 mb-0 lh-1"> Vos structure</h1>
                    <small> Voici la liste de vos structures</small>
                </div>
                <?php
                if($user_info->getRoleId() == ROLE_ADMIN) :?>
                    <button type="button" class="btn btn-primary display-inline  float-end" style="float: right;"
                            data-bs-toggle="modal"
                            data-bs-target="#AddNewStructure"
                            data-bs-csrf="<?php echo $csrf_token?>"
                            data-bs-partnerId="<?php echo $partner_info->getPartnerId();?>">
                        <i class="bi bi-plus-square"></i><span class="d-none d-sm-inline"> Ajouter une structure</span>
                    </button>
                <?php endif; ?>
            </div>

            
            <?php
            /**
             * On charge le modèle utilisé pour afficher les liste des structures
             */
            include PATH_VIEW.'Structure/structureList.php';
            ?>
             
    </div>
  </div>

</main>

<?php
/**
 * Inclusion de chaque modal nécessaire seulement pour les administrateurs
 */
if($user_info->getRoleId() == ROLE_ADMIN) {
    include PATH_VIEW . 'Modals/SearchModal.php';
    include PATH_VIEW . 'Modals/EnableDisableStructureModal.php';
    include PATH_VIEW . 'Modals/EnableDisableServiceModal.php';
    include PATH_VIEW . 'Modals/AddDeleteServiceModal.php';
    include PATH_VIEW . 'Modals/DeleteUserModal.php';
    include PATH_VIEW . 'Modals/AddStructureModal.php';
    include PATH_VIEW . 'Modals/EditPartnerModal.php';
}
// On pourrait afficher une modale pour afficher le contacter le service technique pour ajouter une option et autre
endif;
?>
