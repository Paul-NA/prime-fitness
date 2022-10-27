<?php
/**
 * Utilisation pour afficher/cacher les champs hidden
 */
const FORM_DEBUG = true;


const REGEX_FIRSTNAME = '[\p{L}\s-]{3,40}';
const REGEX_FIRSTNAME_TEXT = '3 à 40 lettres et le tiret - sont autorisée';
const REGEX_FIRSTNAME_PLACEHOLDER = 'Prénom de l\'utilisateur';

const REGEX_LASTNAME = '[\p{L}\s-]{3,40}';
const REGEX_LASTNAME_TEXT = '3 à 40 lettres et le tiret - sont autorisée';
const REGEX_LASTNAME_PLACEHOLDER = 'Nom de l\'utilisateur';

const REGEX_PHONE = '[\p{N}]{9,10}';
const REGEX_PHONE_TEXT = ' de 9 à 10 chiffre';
const REGEX_PHONE_PLACEHOLDER = 'Exemple 555555555 ou 0555555555';

const REGEX_ADDRESS = '[\p{L}\p{N}\s&,\'-]{3,80}';
const REGEX_ADDRESS_TEXT = 'De 3 à 80 caractère, lettre, chiffre, les sigles & , \' -  et l\'espace sont autorisé';
const REGEX_ADDRESS_PLACEHOLDER = '15 Avenue des Champs-Élysées, 75008 Paris';

const REGEX_MAIL = '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,6}';
const REGEX_MAIL_TEXT = 'vous devez entré un format d\'email valide !';
const REGEX_MAIL_PLACEHOLDER = 'email@exemple.com';

const REGEX_SOCIAL_NAME = '[\p{L}\p{N}\s&\'-]{3,60}';
const REGEX_SOCIAL_NAME_TEXT = 'De 3 à 60 caractère, seul les lettre, les chiffre, les sigles  & , \' - et l\'espace sont autorisé';
const REGEX_SOCIAL_NAME_PLACEHOLDER = 'Nom de l\'entreprise';

const REGEX_IS_VALID = '[0-1]{1}';
const REGEX_IS_VALID_TEXT = 'seul le 0 ou le 1 sont autorisée';
const REGEX_IS_VALID_PLACEHOLDER = 'entité active = 1, inactive = 0';


/**
 * Ainsi si un nous vous voulons changer de type d'id par un du genre : PTN_6545548
 * On aurait juste à modifier la regex afin qu'elle se répercute dans tout le site.
 */
const REGEX_USER_ID = '[\p{N}]{1,11}';
const REGEX_PARTNER_ID = '[\p{N}]{1,11}';
const REGEX_STRUCTURE_ID = '[\p{N}]{1,11}';


/*
// Test des regex
function testRegex($regex, $value) {
    return (preg_match('#^'.$regex.'$#u', $value)) ? "TRUE" : "FALSE";
}
echo testRegex(REGEX_FIRSTNAME , $_GET['test']);
*/