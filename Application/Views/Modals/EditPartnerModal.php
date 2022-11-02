<?php
//Simple vérification que l'on n'accède pas à ce fichier directement depuis le navigateur
if(!defined('URI_ROOT')) die('heu non désolé :)');
?>
<!-- Modal Edition d'un Partenaire -->
<div class="modal fade" id="EditPartner" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">éditer le partenaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?php echo URI_ROOT;?>/form/editPartner">
                <div class="modal-body">
                    <div class="row g-3 formContent">
                        <h6 class="text-secondary">Information Partenaire</h6>
                        <div class="col-md-6">
                            <label for="inputLastName" class="form-label text">Nom du gérant </label>
                            <input type="text" class="form-control inputLastName"
                                   pattern="<?php echo REGEX_LASTNAME;?>" title="<?php echo REGEX_LASTNAME_TEXT;?>" placeholder="<?php echo REGEX_LASTNAME_PLACEHOLDER;?>"
                                   name="inputLastName" autocomplete="off" required>

                            <label for="inputFirstName" class="form-label">Prénom du gérant</label>
                            <input type="text" class="form-control inputFirstName"
                                   pattern="<?php echo REGEX_FIRSTNAME;?>" title="<?php echo REGEX_FIRSTNAME_TEXT;?>" placeholder="<?php echo REGEX_FIRSTNAME_PLACEHOLDER;?>"
                                   name="inputFirstName" autocomplete="off" required>

                            <label for="inputMail" class="form-label">Mail de l'utilisateur</label>
                            <input type="email" class="form-control inputMail"
                                   pattern="<?php echo REGEX_MAIL;?>" title="<?php echo REGEX_MAIL_TEXT;?>" placeholder="<?php echo REGEX_MAIL_PLACEHOLDER;?>"
                                   name="inputMail" autocomplete="off" required>

                            <label for="inputFirstName" class="form-label">Status de l'utilisateur</label>
                            <div  class="m-2 form-check form-switch">
                                <input class="form-check-input inputUserActive" type="checkbox" id="SwitchUserActive"  name="inputUserActive">
                                <label class="form-check-label" for="SwitchUserActive"> Activer/désactiver l'utilisateur </label>
                            </div>


                        </div>
                        <div class="col-md-6 top">

                            <label for="inputPhone" class="form-label text">Téléphone de contact</label>
                            <input type="tel" class="form-control inputPhone"
                                   pattern="<?php echo REGEX_PHONE;?>" title="<?php echo REGEX_PHONE_TEXT;?>" placeholder="<?php echo REGEX_PHONE_PLACEHOLDER;?>"
                                   name="inputPhone" autocomplete="off" required>

                            <label for="inputSocialName" class="form-label">Raison sociale</label>
                            <input type="text" class="form-control inputSocialName"
                                   pattern="<?php echo REGEX_SOCIAL_NAME;?>" title="<?php echo REGEX_SOCIAL_NAME_TEXT;?>" placeholder="<?php echo REGEX_SOCIAL_NAME_PLACEHOLDER;?>"
                                   name="inputSocialName" autocomplete="off" required>

                            <label for="inputAddress" class="form-label">Address complete</label>
                            <input type="text" class="form-control inputAddress"
                                   pattern="<?php echo REGEX_ADDRESS;?>" title="<?php echo REGEX_ADDRESS_TEXT;?>" placeholder="<?php echo REGEX_ADDRESS_PLACEHOLDER;?>"
                                   name="inputAddress" autocomplete="off" required>

                            <label for="inputFirstName" class="form-label">Status du partneraire</label>
                            <div class="m-2 form-check form-switch">
                                <input class="form-check-input inputPartnerActive" type="checkbox" id="SwitchPartnerActive" name="inputPartnerActive">
                                <label class="form-check-label" for="SwitchPartnerActive"> Activer/désactiver le partenaire </label>
                            </div>

                            <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" name="type" value="edit_partner">
                            <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>"  class="inputPartnerId" name="inputPartnerId">
                            <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" name="csrf" value="" class="editPartnerCsrf">
                        </div>
                    </div>
                    <div class="row g-3 d-none alertMessage">
                        <h6>Attention vous allez modifier le partenaire</h6>
                        <div class="col-md-6 top">
                            <p>Le partenaire va recevoir un mail l'informant que des informations ont été changer sur son compte !</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                    <button  type="button" class="btn btn-warning btnEditPartner" data-bs-editpartner>éditer le partenaire</button>
                    <button  type="submit" class="btn btn-success btnEditValid d-none">Confirmer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>