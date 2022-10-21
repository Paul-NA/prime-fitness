<?php
/**
 * Front Controller
 */
use Application\Core\Route;
require_once 'Application/Bootstrap.php';

$route = new Route();
$route->routeRequest();


/**
 * Notre application à besoins de peu de page
 * 
 * 1 : /                                -FINI- (redirige vers la page du client "structure ou partenaire" ou sur la liste des partenaires pour le(s) admin(s))
 * 2 : /user/logout                     -FINI-  (obligatoire)           (ADMIN/STRUCTURE/PARTNER)       (pour se déconnecter)
 * 3 : /user/login                      -FINI-  (obligatoire)           (ADMIN/STRUCTURE/PARTNER)       (page de connexion)
 * 4 : /user/confirm/{id}               -FINI-  (obligatoire)           (ADMIN/STRUCTURE/PARTNER)       (sert à la validation d'un compte)
 * 
 * 5 : /structure/information/{id}              (obligatoire)           (ADMIN/STRUCTURE)               (pour voir les informations de la structure)
 * 6 : /partner/information/{id}        -WIP-   (obligatoire)           (ADMIN/PARTNER)                 (pour voir les informations du partenaire avec ces structures)
 *
 * **************************************************************************************************
 *                                      ADMIN ONLY
 * **************************************************************************************************
 *
 * 7 : /partner/list                    -FINI-  (obligatoire)           (ADMIN)                         (Liste tous les partenaires)
 * 10: /API/search                      -WIP-   (obligatoire)           (ADMIN)                         (ce n'est pas réellement une page, c'est pour voir les recherche dynamique)
 * 11: /post/* tous les form admin
 *
 *
 *
 * 12: /services/list (en admin permet d'ajouter/désactiver des services)
 * 13: /services/post       (soumission du formulaire pour ajouter un service)
 * 14: /user/profil         (pas obligatoire) (pour voir ses information
 * 15: /user/logs           (pas obligatoire) (voir les logs de connexion)

 */