<?php
//Simple vérification que l'on n'accède pas à ce fichier directement depuis le navigateur
if(!defined('URI_ROOT')) die('heu non désolé :)');
?>
<!-- Modale Activation/désactivation des structures -->
<div class="modal fade modal-lg" id="EnableDisableStructure" tabindex="-1" aria-labelledby="StructureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?php echo URI_ROOT;?>/form/enableDisableStructure">
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="modalTitleLabel">text</span>
                        <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" class="inputStructureId" name="structure_id">
                        <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" class="inputStructureActive" name="is_active">
                        <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" class="inputCsrf" name="csrf">
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
