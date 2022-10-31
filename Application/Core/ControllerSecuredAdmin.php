<?php
namespace Application\Core;

/**
 * Simple controller Vérifiant que l'utilisateur soit connecté et si le role de l'utilisateur est bien ADMIN
 */
abstract class ControllerSecuredAdmin extends Controller
{

    public function execAction($action)
    {
        // Si les infos client sont présentes dans la session ...
        if ($this->isAdmin()) {
            // ... l'action s'exécute normalement ...
            parent::execAction($action);
        }
        else {
            $this->redirect('/');
        }
    }

}