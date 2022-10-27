<?php
// Affichages des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Quelle erreur reportée (par défaut toutes les erreurs en mode DEV)
error_reporting(E_ALL);

/**
 * Paramétrage des controllers
 */
const CONTROLLER_BASENAME = 'Controller';
const CONTROLLER_DEFAULT = 'Home';
const CONTROLLER_ERROR = 'Error';

/**
 * Donnons un petit nom au rôle (plus jolie que les id 1,2,3), mais surtout plus lisible dans le code
 */
const ROLE_ADMIN = 1;
const ROLE_PARTNER = 2;
const ROLE_STRUCTURE = 3;

/**
 * Paramétrage du système de session
 */

const HTTP_SECURE = false; // pour utiliser https only (attention en localhost)
const HTTP_ONLY = true; // prevent empêche javascript d'accédé au cookie de session
const MAX_LIFETIME = (60*60*24); // 24 heures
const COOKIE_SESSION_NAME = 'PRIME_SESSION'; // on donne un petit nom à notre cookie de session
session_name(COOKIE_SESSION_NAME);
