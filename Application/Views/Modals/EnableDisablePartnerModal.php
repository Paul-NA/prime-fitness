<?php
//Simple vérification que l'on n'accède pas à ce fichier directement depuis le navigateur
if(!defined('URI_ROOT')) die('heu non désolé :)');
?>
<!-- Modale Activation/désactivation des partenaires -->
<div class="modal fade modal-lg" id="EnableDisablePartner" tabindex="-1" aria-labelledby="PartnerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" >
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="modalTitleLabel">text</span>
                        <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" class="partnerId" name="partner_id" id="partnerId">
                        <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" class="partnerActive" name="is_active" id="partnerActive">
                        <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" class="csrf" name="csrf" value="<?php echo $csrf_token;?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning"  data-bs-dismiss="modal">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
