
<!-- Modal Ajout d'un Partenaire -->
<div class="modal fade" id="AddNewPartner" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Ajouter un nouveau partenaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?php echo URI_ROOT;?>/form/addPartner">
                <div class="modal-body">
                    <div class="row g-3 formContent">
                        <h6 class="text-secondary">Information Partenaire</h6>
                        <div class="col-md-6">
                            <label for="inputLastName" class="form-label text">Nom du gérant</label>
                            <input type="text" class="form-control inputLastName" name="inputLastName"
                                   pattern="<?php echo REGEX_LASTNAME;?>" title="<?php echo REGEX_LASTNAME_TEXT;?>" placeholder="<?php echo REGEX_LASTNAME_PLACEHOLDER;?>"
                                   id="inputLastName" autocomplete="off" required>

                            <label for="inputFirstName" class="form-label">Prenom du gérant</label>
                            <input type="text" class="form-control inputFirstName" name="inputFirstName"
                                   pattern="<?php echo REGEX_FIRSTNAME;?>" title="<?php echo REGEX_FIRSTNAME_TEXT;?>" placeholder="<?php echo REGEX_FIRSTNAME_PLACEHOLDER;?>"
                                   id="inputFirstName" autocomplete="off" required>

                            <label for="inputEmail" class="form-label text">Email de contact</label>
                            <input type="email" class="form-control" name="inputEmail"
                                   pattern="<?php echo REGEX_MAIL;?>" title="<?php echo REGEX_MAIL_TEXT;?>" placeholder="<?php echo REGEX_MAIL_PLACEHOLDER;?>"
                                   autocomplete="off" required>
                        </div>
                        <div class="col-md-6 top">

                            <label for="inputPhone" class="form-label text">Téléphone de contact</label>
                            <input type="tel" class="form-control inputPhone" name="inputPhone"
                                   pattern="<?php echo REGEX_PHONE;?>" title="<?php echo REGEX_PHONE_TEXT;?>" placeholder="<?php echo REGEX_PHONE_PLACEHOLDER;?>"
                                   autocomplete="off" required>

                            <label for="inputSocialName" class="form-label">Raison sociale</label>
                            <input type="text" class="form-control inputSocialName" name="inputSocialName"
                                   pattern="<?php echo REGEX_SOCIAL_NAME;?>" title="<?php echo REGEX_SOCIAL_NAME_TEXT;?>" placeholder="<?php echo REGEX_SOCIAL_NAME_PLACEHOLDER;?>"
                                   autocomplete="off" required>

                            <label for="inputAddress" class="form-label">Address complete</label>
                            <input type="text" class="form-control inputAddress" name="inputAddress"
                                   pattern="<?php echo REGEX_ADDRESS;?>" title="<?php echo REGEX_ADDRESS_TEXT;?>" placeholder="<?php echo REGEX_ADDRESS_PLACEHOLDER;?>"
                                   autocomplete="off" required>

                            <input type="hidden" name="type" value="new_partner">
                            <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" name="csrf" class="addPartnerCsrf">
                        </div>
                    </div>
                    <div class="row g-3 d-none alertMessage">
                        <h6>Attention vous allez ajouter un nouveau partenaire</h6>
                        <div class="col-md-6 top">
                            <p>Le partenaire recevra un mail à l'adresse indiqué dans le formulaire afin de valider son compte !<br /></p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Annuler</button>
                    <button  type="button" class="btn btn-warning btnEditPartner" data-bs-editpartner>Créer le partenaire</button>
                    <button  type="submit" class="btn btn-success btnEditValid d-none">Confirmer le nouveau partenaire</button>
                </div>
            </form>
        </div>
    </div>
</div>