
<!-- Modale Activation/désactivation des services -->
<div class="modal fade modal-lg" id="DeleteUser" tabindex="-1" aria-labelledby="DeleteUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="<?php echo URI_ROOT;?>/form/removeUser" >
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="modalTitleLabel">
                            <b>Attention vous allez supprimer un utilisateur</b> <br />
                            En supprimant l'utilisateur vous supprimer toutes ses données quel que soit son status.<br />
                            Que se soit un partenaire ou une structure toutes les informations seront supprimer de la base de donnée !
                        </span>
                        <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" class="userId" name="user_id">
                        <input type="<?php echo ((FORM_DEBUG === true) ? 'text' : 'hidden');?>" class="userCsrf" name="csrf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">oui je confirme la suppression</button>
                </div>
            </form>
        </div>
    </div>
</div>
