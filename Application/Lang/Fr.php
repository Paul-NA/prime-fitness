<?php
/**
 * Nous créons ici tous les textes nécessaires pour l'envoie de mail
 * (les titres et le contenu.)
 * 
 * avant d'envoyer nous utiliseront sprintf() pour remplacer pas la bonne valeur du texte entre {}
 * 
 * on se réfère à la doc PHP pour savoir quoi mettre comme spécificateur
 * https://www.php.net/manual/fr/function.sprintf.php
 */
const MAIL_TITLE_NEW_STRUCTURE = 'Votre Structure vient d\'être créé chez Prime-Fitness';
const MAIL_TITLE_NEW_PARTNER = 'Votre compte Partenaire vient d\'être créé chez Prime-Fitness';
const MAIL_TITLE_NEW_PARTNER_STRUCTURE = 'Une Structure vient d\'être ajouté sur votre compte  Prime-Fitness';

const MAIL_TITLE_SERVICE_EDITED_STRUCTURE = '';
const MAIL_TITLE_SERVICE_EDITED_PARTNER = '';

const MAIL_BODY_NEW_PARTNER = '';

// Creation d'un nouveau compte Structure
const MAIL_BODY_NEW_STRUCTURE = '<div style="width: 100%;">Bonjour et bienvenue chez prime-Fitness<br />'
    . '<br /> '
    . 'Afin de validé votre compte vous devez suivre le lien ci-dessous pour créer votre mot de passe !<br /><br />'
    . '<b>Attention :</b> nous ne pouvont pas retrouvez votre pas le mot de passe, veillez à noté quelque part afin de ne pas le perdre !'
    . '<br />'
    . '<b>Lien pour créer votre mot de passe :</b>  <a href="' . URI_ROOT . '/user/confirm/%1$s"> ' . URI_ROOT . '/user/confirm/%1$s</a><br /> '
    . '<b>Page de connexion :</b> <a href="' . URI_ROOT . '/user/login">' . URI_ROOT . '/user/login</a><br />'
    . '<br />'
    . 'Merci de votre attention,'
    . 'l\'équipe Prime-Fitness.'
    . '</div>';


/**
 * Contenue des mails pour les services
 */
const MAIL_OBJECT_NEW_SERVICE = 'Ajout d\'un service sur votre compte';
const MAIL_OBJECT_SERVICE_DELETED = 'Suppression d\'un service sur votre compte';
const MAIL_OBJECT_SERVICE_EDITED = 'Edition d\'un service sur votre compte';
const MAIL_OBJECT_SERVICE_EDITED_ON_STRUCTURE = 'Edition d\'un service sur une de vos structure';
const MAIL_OBJECT_SERVICE_EDITED_ON_PARTNER = 'Edition d\'un service sur votre partenaire';


// Contenue du mail pour les partenaires si un service est édité sur leur compte
const MAIL_BODY_SERVICE_EDITED_PARTNER = '<div style="width: 100%;">Bonjour, '
    . '<br />'
    . '<br />'
    . 'Le services <b>%1$s</b> vient d\'être modifié sur votre compte, son nouveau status est maintenant <b>%2$s</b>'
    . '<br />'
    . 'Merci de votre attention,'
    . 'l\'équipe Prime-Fitness.'
    . '</div>';

// Contenue du mail pour les partenaires si un service est édité sur leur compte
const MAIL_BODY_SERVICE_EDITED_STRUCTURE = '<div style="width: 100%;">Bonjour, '
    . '<br />'
    . '<br />'
    . 'Le services <b>%1$s</b> vient d\'être modifié sur votre partenaire, cela prend effet aussi sur votre compte'
    . '<br />'
    . '<br />'
    . 'Merci de votre attention,'
    . 'l\'équipe Prime-Fitness.'
    . '</div>';


// Creation d'un nouveau compte Structure
const MAIL_BODY_NEW_SERVICE = '<div style="width: 100%;">Bonjour,<br />'
    . '<br /> '
    . 'Suite à notre entretien téléphonique nous vous confirmons l\'activation de service <b>%1$s</b> !<br /><br />'
    . 'Ce service est donc maintenant accessible à toutes vos structures !'
    . '<br />'
    . '<br />'
    . 'Merci de votre attention,'
    . 'l\'équipe Prime-Fitness.'
    . '</div>';



// Contenue du mail pour les partenaires si un service est édité sur leur compte
const MAIL_BODY_SERVICE_DELETED_PARTNER = '<div style="width: 100%;">Bonjour, '
    . '<br />'
    . '<br />'
    . 'Le services <b>%1$s</b> vient d\'être supprimé de votre compte, toutes vos structures affiliées ont donc par la même occasion perdue la possibilité d\'utilisé à ce service'
    . 'Ce service est donc supprimé aussi de chacune de vos structures<br />'
    . '<br />'
    . 'Merci de votre attention,'
    . 'l\'équipe Prime-Fitness.'
    . '</div>';

// Contenue du mail pour les partenaires si un service est édité sur leur compte
const MAIL_BODY_SERVICE_DELETED_STRUCTURE = '<div style="width: 100%;">Bonjour, '
    . '<br />'
    . '<br />'
    . 'votre Maison mère <b>%1$s</b> à décidé de supprimer le services <b>%2$s</b>. '
    . 'De ce fait, ce service est supprimé de sur votre compte a compté de la reception de ce mail<br />'
    . 'Merci de votre attention,'
    . '<br />'
    . 'l\'équipe Prime-Fitness.'
    . '</div>';
