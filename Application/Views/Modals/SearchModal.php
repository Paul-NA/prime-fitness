
<!-- Modale Activation/désactivation des services -->
<div class="modal fade modal-lg" id="SearchModal" tabindex="-1" aria-labelledby="DeleteUserLabel" aria-hidden="true">
    <div class="modal-dialog  modal-xl modal-fullscreen-xl-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3>Rechercher sur le site</h3>
                <span>Terme de la recherche</span>
                <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="SearchBar" placeholder="Votre recherche ici" aria-label="Votre recherche ici" aria-describedby="addon-wrapping">
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-check">
                                <span>Je recherche :</span><br>
                                <input class="form-check-input" type="radio" name="searchRole" id="searchRolePartner" checked>
                                <label class="form-check-label" for="searchRolePartner"> un Partenaire </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="searchRole" id="searchRoleStructure">
                                <label class="form-check-label" for="searchRoleStructure"> une structure </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <span>avec le status :</span><br>
                                <input class="form-check-input" type="radio" name="SearchStatus" id="searchStatusActif" checked>
                                <label class="form-check-label" for="searchStatusActif">Actif</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SearchStatus" id="searchStatusInactif">
                                <label class="form-check-label" for="searchStatusInactif">Inactif</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="SearchStatus" id="searchStatusAll">
                                <label class="form-check-label" for="searchStatusAll">Tous</label>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <h6>Résultat de la recherche</h6>
                        <div class="searchResult">Veuillez lancé une recherche</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer la recherche</button>
            </div>
        </div>
    </div>
</div>
