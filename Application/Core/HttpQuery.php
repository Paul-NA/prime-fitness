<?php
namespace Application\Core;

/**
 * Classe modélisant une requête HTTP entrante.
 */
class HttpQuery
{
    /**
     * Tableau des paramètres de la requête
     */
    private array $parameters;

    /**
     * Objet session associé à la requête
     */
    private Session $session;

    /**
     * Constructeur
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->session = new Session();
    }

    /**
     * Renvoie l'objet session associé à la requête
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * Renvoie vrai si le paramètre existe dans la requête
     */
    public function existParameter(string $name) : bool
    {
        return (isset($this->parameters[$name]) && $this->parameters[$name] != "");
    }

    /**
     * Renvoie la valeur du paramètre demandé
     */
    public function getParameter(string $name) : string
    {
        if ($this->existParameter($name)) {
            return $this->parameters[$name];
        }
        else {
            throw new \Exception("Paramètre '$name' absent de la requête");
        }
    }
}