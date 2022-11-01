<?php
namespace Application\Core;

/**
 * Classe modélisant la session.
 */
class Session
{
    /**
     * Constructeur
     */
    public function __construct()
    {

        session_set_cookie_params(MAX_LIFETIME, null, $_SERVER['HTTP_HOST'], HTTP_SECURE, HTTP_ONLY);
        session_start();
        if(session_name() == 'PHPSESSID'){
            session_destroy();
        }
    }

    /**
     * Détruit la session actuelle
     */
    public function destroy()
    {
        session_destroy();
    }

    /**
     * Ajoute un attribut à la session
     */
    public function setAttribute(string $name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function deleteAttribute(string $name) : void{
        unset($_SESSION[$name]);
    }

    /**
     * Renvoie vrai si l'attribut existe dans la session
     */
    public function existAttribute(string $name) : bool{
        return (isset($_SESSION[$name]) && $_SESSION[$name] != "");
    }

    /**
     * Renvoie la valeur de l'attribut demandé
     * @throws \Exception
     */
    public function getAttribute(string $name)
    {
        if ($this->existAttribute($name)) {
            return $_SESSION[$name];
        }
        else {
            throw new \Exception("Attribut '$name' absent de la session");
        }
    }
}