/**
 * Gestion de la Modale pour ajouter un nouveau partenaire
 */
const addPartner = document.getElementById('AddNewPartner');
if(addPartner){

    // selection des elements du modal que l'on souhaite
    const modalCsrf             = addPartner.querySelector('.addPartnerCsrf');
    const btnEdit = addPartner.querySelector('.btnEditPartner');
    const btnEditValid = addPartner.querySelector('.btnEditValid');
    const formContent = addPartner.querySelector('.formContent');
    const alertMessage = addPartner.querySelector('.alertMessage');

    /**
     * Assignation pour la modale
     */
    addPartner.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget;


        // Extraction des infos contenue dans les boutons data-* attributes
        const _csrf             = button.getAttribute('data-bs-csrf');

        // Modification du contenue dans le modal
        modalCsrf.value                 = _csrf;


        btnEdit.addEventListener('click', event =>{
            btnEdit.classList.add('d-none');
            btnEditValid.classList.remove('d-none');
            formContent.classList.add('d-none');
            alertMessage.classList.remove('d-none');
        });

        btnEditValid.addEventListener('click', event =>{
            btnEdit.classList.remove('d-none');
            btnEditValid.classList.add('d-none');
            formContent.classList.remove('d-none');
            alertMessage.classList.add('d-none');
        });
    });

    /**
     * Affichage d'origine si on ferme la modale
     */
    addPartner.addEventListener('hidden.bs.modal', event => {
        btnEdit.classList.remove('d-none');
        btnEditValid.classList.add('d-none');
        formContent.classList.remove('d-none');
        alertMessage.classList.add('d-none');
    });

    /**
     * Quand on envoie le formulaire on cache la modale (effet indésirable sinon durant quelque instants)
     */
    addPartner.addEventListener('submit', event => {
        addPartner.classList.add('d-none');
    });

    /**
     * Désactivation de la soumission du formulaire par le bouton entrer
     */
    addPartner.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            e.stopPropagation();
        }
    });
}

/***********************************************************************/
/**
 * Gestion de la Modale pour ajouter un nouveau partenaire
 */
const addStructure = document.getElementById('AddNewStructure');
if(addStructure){

    // selection des elements du modal que l'on souhaite
    const modalPartnerId = addStructure.querySelector('.inputPartnerId');
    const modalCsrf = addStructure.querySelector('.addStructureCsrf');
    const btnEdit = addStructure.querySelector('.btnEditPartner');
    const btnEditValid = addStructure.querySelector('.btnEditValid');
    const formContent = addStructure.querySelector('.formContent');
    const alertMessage = addStructure.querySelector('.alertMessage');

    /**
     * Assignation pour la modale
     */
    addStructure.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget;


        // Extraction des infos contenue dans les boutons data-* attributes
        const _csrf             = button.getAttribute('data-bs-csrf');
        const _partnerId             = button.getAttribute('data-bs-partnerId');

        // Modification du contenue dans le modal
        modalCsrf.value                 = _csrf;
        modalPartnerId.value                 = _partnerId;


        btnEdit.addEventListener('click', event =>{
            btnEdit.classList.add('d-none');
            btnEditValid.classList.remove('d-none');
            formContent.classList.add('d-none');
            alertMessage.classList.remove('d-none');
        });

        btnEditValid.addEventListener('click', event =>{
            btnEdit.classList.remove('d-none');
            btnEditValid.classList.add('d-none');
            formContent.classList.remove('d-none');
            alertMessage.classList.add('d-none');
        });
    });

    /**
     * Affichage d'origine si on ferme la modale
     */
    addStructure.addEventListener('hidden.bs.modal', event => {
        btnEdit.classList.remove('d-none');
        btnEditValid.classList.add('d-none');
        formContent.classList.remove('d-none');
        alertMessage.classList.add('d-none');
    });

    /**
     * Quand on envoie le formulaire on cache la modale (effet indésirable sinon durant quelque instants)
     */
    addStructure.addEventListener('submit', event => {
        addStructure.classList.add('d-none');
    });

    /**
     * Désactivation de la soumission du formulaire par le bouton entrer
     */
    addStructure.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            e.stopPropagation();
        }
    });
}


/******************************************************************/





/**
 * Gestion de la Modale pour activer/désactiver un partenaire
 */
const partnerEnableDisable = document.getElementById('EnableDisablePartner');
if(partnerEnableDisable){
    /**
     * Assignation pour la modale etc
     */
    partnerEnableDisable.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget;
        // Extraction des infos contenue dans les boutons data-* attributes
        const _partnerId = button.getAttribute('data-bs-partner-id');
        const _partnerName = button.getAttribute('data-bs-partner-name') ;
        const _partnerActive = button.getAttribute('data-bs-partner-status');

        // If necessary, you could initiate an AJAX request here
        // and then do the updating in a callback.

        // selection des elements du modal que l'on souhaite
        const modalPartnerTitle     = partnerEnableDisable.querySelector('.modalTitleLabel');
        const modalPartnerId        = partnerEnableDisable.querySelector('.partnerId');
        const modalPartnerActive    = partnerEnableDisable.querySelector('.partnerActive');

        // Modification du contenue dans le modal
        modalPartnerTitle.innerHTML = "Attention :  vous modifier un partenaire !<br /> voulez vous Valider "+((_partnerActive === '1') ? 'l\'activation' : 'la désactivation' ) +" du partenaire <b>"+_partnerName+"</b>.<br />"+"\r\n"+"Le partenaire recevra un mail pour lui notifier la modification, ainsi que toutes les structure de celui-ci<br />";
        modalPartnerId.value = _partnerId;
        modalPartnerActive.value = _partnerActive;
    });

    /**
     * Traitement en ajax
     */
    partnerEnableDisable.addEventListener('submit', function(e){
        e.preventDefault();
        const name = document.getElementById('partnerId').value;
        const buttonModifieID = document.getElementById('partnerID-'+name);
        const _partnerActive = buttonModifieID.getAttribute('data-bs-partner-status');

        fetch(rootPath + "/form/enableDisablePartner", {
            method: 'POST',
            body:
                    "partner_id="+name+ // on passe le partenaire id
                    "&partner_active="+_partnerActive+  // on passe le nouveau status
                    "&type=partner_update_status", // on passe le type de requêtes (pour appeler la fonction adéquate
            headers: {
              "Content-Type": "application/x-www-form-urlencoded"
            }
         })
        .then(function(response){
            return response.text();
        })
        .then(function(data){
            //alert(data);
            if(data !== 'false'){
                // si le partenaire est inactif on l'active
                if(_partnerActive === '1'){
                    buttonModifieID.textContent = 'Actif';
                    buttonModifieID.classList.remove('btn-danger');
                    buttonModifieID.classList.add('btn-success');
                    buttonModifieID.setAttribute('data-bs-partner-status', "0");
                }
                // sinon le partenaire est actif on le désactive
                else{
                    buttonModifieID.textContent = 'Inactif';
                    buttonModifieID.classList.remove('btn-success');
                    buttonModifieID.classList.add('btn-danger');
                    buttonModifieID.setAttribute('data-bs-partner-status', "1");
                }
            }
        }).catch(error => console.error('Error:', error)); 
    });
}

/**
 * Edition d'un partenaire
 * FINI
 */
const partnerEdit = document.getElementById('EditPartner');
if(partnerEdit) {

    // selection des elements du modal que l'on souhaite
    const modalCsrf = partnerEdit.querySelector('.editPartnerCsrf');
    const modalLastName = partnerEdit.querySelector('.inputLastName');
    const modalFirstName = partnerEdit.querySelector('.inputFirstName');
    const modalMail = partnerEdit.querySelector('.inputMail');
    const modalPhone = partnerEdit.querySelector('.inputPhone');
    const modalAddress = partnerEdit.querySelector('.inputAddress');
    const modalSocialName = partnerEdit.querySelector('.inputSocialName');
    const modalPartnerId = partnerEdit.querySelector('.inputPartnerId');
    const modalPartnerActive = partnerEdit.querySelector('.inputPartnerActive');
    const modalUserActive = partnerEdit.querySelector('.inputUserActive');

    const btnEdit = partnerEdit.querySelector('.btnEditPartner');
    const btnEditValid = partnerEdit.querySelector('.btnEditValid');
    const formContent = partnerEdit.querySelector('.formContent');
    const alertMessage = partnerEdit.querySelector('.alertMessage');

    /**
     * Assignation pour la modale etc
     */
    partnerEdit.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget;

        // Extraction des infos contenue dans les boutons data-* attributes
        const _csrf = button.getAttribute('data-bs-csrf');
        const _partnerId = button.getAttribute('data-bs-partnerId');
        const _firstName = button.getAttribute('data-bs-firstname');
        const _lastName = button.getAttribute('data-bs-lastname');
        const _mail = button.getAttribute('data-bs-mail');
        const _phone = button.getAttribute('data-bs-phone');
        const _address = button.getAttribute('data-bs-address');
        const _socialName = button.getAttribute('data-bs-socialName');
        const _userStatus = button.getAttribute('data-bs-userStatus');
        const _partnerStatus = button.getAttribute('data-bs-partnerStatus');

        // Modification du contenu dans le modal
        modalCsrf.value = _csrf;
        modalPartnerId.value = _partnerId;
        modalLastName.value = _lastName;
        modalFirstName.value = _firstName;
        modalMail.value = _mail;
        modalPhone.value = _phone;
        modalAddress.value = _address;
        modalSocialName.value = _socialName;
        ((_userStatus === "1") ? modalUserActive.setAttribute('checked', 'checked') : modalUserActive.removeAttribute('checked'));
        ((_partnerStatus === "1") ? modalPartnerActive.setAttribute('checked', 'checked') : modalPartnerActive.removeAttribute('checked'));

        btnEdit.addEventListener('click', event =>{
            btnEdit.classList.add('d-none');
            btnEditValid.classList.remove('d-none');
            formContent.classList.add('d-none');
            alertMessage.classList.remove('d-none');
        });

        btnEditValid.addEventListener('click', event =>{
            btnEdit.classList.remove('d-none');
            btnEditValid.classList.add('d-none');
            formContent.classList.remove('d-none');
            alertMessage.classList.add('d-none');
        });
    });

    /**
     * Affichage d'origine si on ferme la modale
     */
    partnerEdit.addEventListener('hidden.bs.modal', event => {
        btnEdit.classList.remove('d-none');
        btnEditValid.classList.add('d-none');
        formContent.classList.remove('d-none');
        alertMessage.classList.add('d-none');
    });

    /**
     * Quand on envoie le formulaire on cache la modale (effet indésirable sinon durant quelque instants)
     */
    partnerEdit.addEventListener('submit', event => {
        partnerEdit.classList.add('d-none');
    });

    /**
     * Désactivation de la soumission du formulaire par le bouton entrer
     */
    partnerEdit.addEventListener('keydown', function(e) {
        if (e.key == 'Enter') {
            e.preventDefault();
            e.stopPropagation();
        }
    });
}


/**
 * Edition d'une structure
 * FINI
 */
const structureEdit = document.getElementById('EditStructure');
if(structureEdit) {

    // selection des elements du modal que l'on souhaite
    const modalCsrf = structureEdit.querySelector('.editStructureCsrf');
    const modalLastName = structureEdit.querySelector('.inputLastName');
    const modalFirstName = structureEdit.querySelector('.inputFirstName');
    const modalPhone = structureEdit.querySelector('.inputPhone');
    const modalAddress = structureEdit.querySelector('.inputAddress');
    const modalMail = structureEdit.querySelector('.inputEmail');
    const modalSocialName = structureEdit.querySelector('.inputSocialName');
    const modalStructureId = structureEdit.querySelector('.inputStructureId');
    const modalStructureActive = structureEdit.querySelector('.inputStructureActive');
    const modalUserActive = structureEdit.querySelector('.inputUserActive');

    const btnEdit = structureEdit.querySelector('.btnEditStructure');
    const btnEditValid = structureEdit.querySelector('.btnEditValid');
    const formContent = structureEdit.querySelector('.formContent');
    const alertMessage = structureEdit.querySelector('.alertMessage');

    /**
     * Assignation pour la modale etc
     */
    structureEdit.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget;

        // Extraction des infos contenue dans les boutons data-* attributes
        const _csrf = button.getAttribute('data-bs-csrf');
        const _structureId = button.getAttribute('data-bs-structureId');
        const _firstName = button.getAttribute('data-bs-firstname');
        const _lastName = button.getAttribute('data-bs-lastname');
        const _email = button.getAttribute('data-bs-email');
        const _phone = button.getAttribute('data-bs-phone');
        const _address = button.getAttribute('data-bs-address');
        const _socialName = button.getAttribute('data-bs-socialName');
        const _userStatus = button.getAttribute('data-bs-userStatus');
        const _structureStatus = button.getAttribute('data-bs-structureStatus');

        // Modification du contenu dans le modal
        modalCsrf.value = _csrf;
        modalStructureId.value = _structureId;
        modalLastName.value = _lastName;
        modalMail.value = _email;
        modalFirstName.value = _firstName;
        modalPhone.value = _phone;
        modalAddress.value = _address;
        modalSocialName.value = _socialName;
        ((_userStatus === "1") ? modalUserActive.setAttribute('checked', 'checked') : modalUserActive.removeAttribute('checked'));
        ((_structureStatus === "1") ? modalStructureActive.setAttribute('checked', 'checked') : modalStructureActive.removeAttribute('checked'));

        btnEdit.addEventListener('click', event =>{
            btnEdit.classList.add('d-none');
            btnEditValid.classList.remove('d-none');
            formContent.classList.add('d-none');
            alertMessage.classList.remove('d-none');
        });

        btnEditValid.addEventListener('click', event =>{
            btnEdit.classList.remove('d-none');
            btnEditValid.classList.add('d-none');
            formContent.classList.remove('d-none');
            alertMessage.classList.add('d-none');
        });
    });

    /**
     * Affichage d'origine si on ferme la modale
     */
    structureEdit.addEventListener('hidden.bs.modal', event => {
        btnEdit.classList.remove('d-none');
        btnEditValid.classList.add('d-none');
        formContent.classList.remove('d-none');
        alertMessage.classList.add('d-none');
    });

    /**
     * Quand on envoie le formulaire on cache la modale (effet indésirable sinon durant quelque instants)
     */
    structureEdit.addEventListener('submit', event => {
        structureEdit.classList.add('d-none');
    });

    /**
     * Désactivation de la soumission du formulaire par le bouton entrer
     */
    structureEdit.addEventListener('keydown', function(e) {
        if (e.key == 'Enter') {
            e.preventDefault();
            e.stopPropagation();
        }
    });
}

// TODO suppression d'un partenaire oui ou non ?

/******************************************************************/

/**
 * Gestion de la Modale pour activer/désactiver un service (partenaire et structure)
 */
const serviceEnableDisable = document.getElementById('EnableDisableService');
if(serviceEnableDisable){
    /**
     * Assignation pour la modale
     */
    serviceEnableDisable.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget;
        // Extraction des infos contenue dans les boutons data-* attributes
        const _serviceId        = button.getAttribute('data-bs-service-id');
        const _serviceName      = button.getAttribute('data-bs-service-name') ;
        const _serviceActive    = button.getAttribute('data-bs-service-status');
        const _serviceType      = button.getAttribute('data-bs-service-type');
        const _serviceTypeId    = button.getAttribute('data-bs-service-type-id');
        const _csrf             = button.getAttribute('data-bs-csrf');

        // selection des elements du modal que l'on souhaite
        const modalServiceTitle     = serviceEnableDisable.querySelector('.modalTitleLabel');
        const modalServiceId        = serviceEnableDisable.querySelector('.serviceId');
        const modalServiceActive    = serviceEnableDisable.querySelector('.serviceActive');
        const modalServiceType      = serviceEnableDisable.querySelector('.serviceType');
        const modalServiceTypeId    = serviceEnableDisable.querySelector('.serviceTypeId');
        const modalCsrf             = serviceEnableDisable.querySelector('.serviceCsrf');

        // Modification du contenue dans le modal
        modalServiceTitle.innerHTML     = "Attention : vous modifier un service !<br /> voulez vous Valider "+((_serviceActive === '1') ? 'l\'activation' : 'la désactivation' ) +" du service <b>"+_serviceName+"</b>.";
        modalServiceId.value            = _serviceId;
        modalServiceActive.value        = _serviceActive;
        modalServiceType.value          = _serviceType;
        modalServiceTypeId.value          = _serviceTypeId;
        modalCsrf.value                 = _csrf;
    });

    /**
     * Si on utilise Ajax pour rafraichir la liste, on pourrait ajouter un script dessous en ajoutant un listener sur le submit
     */
}


/**
 * Gestion de la Modale pour activer/désactiver un service (partenaire et structure)
 */
const serviceAddRemove = document.getElementById('AddRemoveService');
if(serviceAddRemove){
    /**
     * Assignation pour la modale
     */
    serviceAddRemove.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget;
        // Extraction des infos contenue dans les boutons data-* attributes
        const _serviceId            = button.getAttribute('data-bs-service-id');
        const _serviceName          = button.getAttribute('data-bs-service-name') ;
        const _servicePartnerName   = button.getAttribute('data-bs-service-partner-name') ;
        const _serviceType          = button.getAttribute('data-bs-service-type');
        const _serviceTypeId        = button.getAttribute('data-bs-service-type-id');
        const _csrf                 = button.getAttribute('data-bs-csrf');

        // selection des elements du modal que l'on souhaite
        const modalServiceTitle     = serviceAddRemove.querySelector('.modalTitleLabel');
        const modalServiceId        = serviceAddRemove.querySelector('.serviceId');
        const modalServiceType      = serviceAddRemove.querySelector('.serviceType');
        const modalServiceTypeId    = serviceAddRemove.querySelector('.serviceTypeId');
        const modalCsrf             = serviceAddRemove.querySelector('.serviceCsrf');

        // Modification du contenue dans le modal
        modalServiceTitle.innerHTML     = "Attention : vous aller <b>"+((_serviceType == "add") ? 'ajouter' : 'supprimer' ) +"</b> un service !<br /> êtes vous sûr de vouloir <b>"+((_serviceType == "add") ? 'ajouter' : 'supprimer' ) +"</b> le service <b>"+_serviceName+"</b>";
        modalServiceId.value            = _serviceId;
        modalServiceType.value          = _serviceType;
        modalServiceTypeId.value        = _serviceTypeId;
        modalCsrf.value                 = _csrf;
    });

    /**
     * Si on utilise Ajax pour rafraichir la liste, on pourrait ajouter un script dessous en ajoutant un listener sur le submit
     */
}


/**
 * Gestion de la Modale pour activer/désactiver une structure
 */
const structureEnableDisable = document.getElementById('EnableDisableStructure');
if(structureEnableDisable){
    structureEnableDisable.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget;
        // Extraction des infos contenue dans les boutons data-* attributes
        const _structureId          = button.getAttribute('data-bs-structureId');
        const _structureActive      = button.getAttribute('data-bs-structureActive');
        const _structureName      = button.getAttribute('data-bs-structureName');
        const _csrf                 = button.getAttribute('data-bs-csrf');

        // selection des elements du modal que l'on souhaite
        const modalServiceTitle     = structureEnableDisable.querySelector('.modalTitleLabel');
        const modalStructureId      = structureEnableDisable.querySelector('.inputStructureId');
        const modalStructureActive  = structureEnableDisable.querySelector('.inputStructureActive');
        const modalCsrf             = structureEnableDisable.querySelector('.inputCsrf');

        // Modification du contenue dans le modal
        modalServiceTitle.innerHTML     = "Attention : vous modifier une structure !<br /> voulez vous Valider "+((_structureActive === '1') ? 'l\'activation' : 'la désactivation' ) +" de la structure <b>"+_structureName+"</b>.";
        modalStructureId.value          = _structureId;
        modalStructureActive.value      = _structureActive;
        modalCsrf.value                 = _csrf;
    });
}

