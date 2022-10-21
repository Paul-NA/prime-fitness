<?php
namespace Application\Core;

/**
 * Simple controller Vérifiant que l'utilisateur soit connecté
 */
abstract class ControllerSecured extends Controller
{
    public function execAction($action)
    {
        // Si les infos client sont présentes dans la session ...
        if ($this->isLogged()) {
            // ... l'action s'exécute normalement ...
            parent::execAction($action);
        }
        // ... ou l'utilisateur est redirigé vers la page de connexion
        else {
            $this->redirect("/user/login");
        }
    }

}