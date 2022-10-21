<?php
$this->titre = 'Erreur 404 - page introuvable';
?>
<div class="modal modal-lg modal-alert position-static d-block py-5" tabindex="-1" role="dialog" id="modalChoice">
  <div class="modal-dialog" role="document">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-body p-4 text-center">
        <h5 class="mb-0">Désolé cette page est introuvable</h5>
        <p class="mb-0">Vous vous êtes perdu ?<br />Pas de problème on connais la route</p>
      </div>
      <div class="modal-footer flex-nowrap p-0">
        <a href="<?php echo URI_ROOT; ?>" type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end"><strong>Ramnez moi sur la route !</strong></a>
        <a  href="https://google.fr" type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0" data-bs-dismiss="modal">Non, merci, je vais aller voir google</a>
      </div>
    </div>
  </div>
</div>
<?php //echo $msgError;?>