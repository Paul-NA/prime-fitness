<?php
namespace Application\Core;

class Route
{
    /**
     * On récupère la route en décomposant
     */
    public function routeRequest()
    {
        try {
            // TODO ce serait bien de ne pas merge GET et POST, mais de les traiter séparément (bientôt)
            $request = new HttpQuery(array_merge($_GET, $_POST));
            $controller = $this->createController($request);
            $action = $this->createAction($request);
            $controller->execAction($action);
        }
        catch (Exception $e) {
            $this->handleError($e);
        }
    }

    /**
     * Instancie le contrôleur approprié en fonction de la requête reçue
     */
    private function createController(HttpQuery $request)
    {
        // Grâce à la redirection, toutes les URL entrantes sont du type :
        // login.php?controller=XXX&action=YYY&id=ZZZ

        //Default Controller
        $controller = CONTROLLER_DEFAULT;
        //Base controller name
        $baseControllerName = 'Controller';

        //On vérifie si l'on à un controller dans la request sitename.com/{Controller}
        if ($request->existParameter('controller')) {
            $controller = $request->getParameter('controller');
            // Première lettre en majuscules
            $controller = ucfirst(strtolower($controller));
        }
        
        // Création du nom du fichier du contrôleur
        // La convention de nommage des fichiers controllers est : Controller/Controller<$controller>.php
        $classeController = CONTROLLER_BASENAME.$controller;
        // Si le controller existe, c'est que la page existe
        if (file_exists(PATH_CONTROLLER.$classeController.'.php')) {
            // Instanciation du contrôleur adapté à la requête
            require(PATH_CONTROLLER.$classeController.'.php');
        }
        // la page n'existe pas !
        else if  (file_exists(PATH_CONTROLLER.CONTROLLER_BASENAME.ucfirst(CONTROLLER_ERROR).'.php')){
            $classeController = CONTROLLER_BASENAME.ucfirst(CONTROLLER_ERROR);
            require(PATH_CONTROLLER.$classeController.'.php');
        }
        else{
            die('Ola même la page d\'erreur est pas bonne');
        }

        //die($classeController);
        $controller = new $classeController();
        $controller->setRequest($request);
        return $controller;
    }

    /**
     * Détermine l'action à exécuter en fonction de la requête reçue
     */
    private function createAction(HttpQuery $request) : string
    {
        $action = "index";  // Action par défaut
        if ($request->existParameter('action')) {
            $action = $request->getParameter('action');
        }
        return $action;
    }

    /**
     * Gère une erreur d'exécution (exception)
     */
    private function handleError(string $exception)
    {
        $vue = new View('error');
        $vue->generate(array('msgErreur' => $exception));
    }

}