<?php
// Affichages des erreurs
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// Quelle erreur reportée (par défaut toutes les erreurs en mode DEV)
error_reporting(E_ALL);

// Email
const EMAIL = [
    'MAIL' => 'no-reply@prime-fitness.site',
    'NAME' => 'Prime-Fitness'
];

// En mode dev false, en condition réelle on passe à true (on ne veut pas réellement envoyé les mails.)
const SEND_EMAIL = false;

/**
 * Url du site par défaut
 */
const URI_ROOT = 'https://prime-fitness.site';

/**
 * Paramétrage de connexion à la base de donnée
 */
const DATABASE = [
    'DBNAME' => 'c0primefitnesssql',
    'HOST' => '51.158.144.204',
    'USER' => 'c0primefitness',
    'PASSWORD' => 'knguiFD@DWW28'
];

const DEBUG_SQL = false;

/**
 * Paramétrage des controllers
 */
const CONTROLLER_BASENAME = 'Controller';
const CONTROLLER_DEFAULT = 'Home';
const CONTROLLER_ERROR = 'Error';

/**
 * Paramétrage du système de session
 */

const HTTP_SECURE = true; // if you only want to receive the cookie over HTTPS
const HTTP_ONLY = true; // prevent JavaScript access to session cookie
const MAX_LIFETIME = (60*60*24); // 24 heures
const COOKIE_SESSION_NAME = 'PRIME_SESSION'; // on donne un petit nom à notre cookie de session
session_name(COOKIE_SESSION_NAME);
//session_set_cookie_params(MAX_LIFETIME, null, $_SERVER['HTTP_HOST'], HTTP_SECURE, HTTP_ONLY);
/**
 * Nombre d'item par page (dans /partner/list)
 */
const NUMBER_ITEM_PER_PAGE = 5;

/**
 * Donnons un petit nom au rôle (plus jolie que les id 1,2,3), mais surtout plus lisible dans le code
 */
const ROLE_ADMIN = 1;
const ROLE_PARTNER = 2;
const ROLE_STRUCTURE = 3;