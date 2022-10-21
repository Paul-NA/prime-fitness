
<!-- Modale Activation/désactivation des services -->
<div class="modal fade modal-lg" id="EnableDisableService" tabindex="-1" aria-labelledby="StructureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?php echo URI_ROOT;?>/form/enableDisableService" >
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="modalTitleLabel">text</span>
                        <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" class="serviceId" name="service_id">
                        <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" class="serviceActive" name="service_active">
                        <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" class="serviceType" name="service_type">
                        <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" class="serviceTypeId" name="service_type_id">
                        <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" class="serviceCsrf" name="csrf">
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
