<?php
use Application\Core\Controller;

/**
 * Contrôleur de la page Partenaire
 */
class ControllerError extends Controller {

    public function __construct() {
    }
    
    /**
     * Affiche la page d'accueil
     */
    public function index() {
        $this->generateView(array());
    }
}