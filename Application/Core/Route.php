<?php
namespace Application\Core;

class Route
{
    /**
     * On récupère la route en décomposant
     */
    public function routeRequest(){
        try {
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
    private function createController(HttpQuery $request){

        //On vérifie si l'on a un controller dans la request sitename.com/{Controller}
        $controller = (($request->existParameter('controller')) ? ucfirst($request->getParameter('controller')) : CONTROLLER_DEFAULT);

        // On va vérifier que le controller existe sinon on chargera celui de l'erreur
        $classeController = $this->existController($controller);
        require(PATH_CONTROLLER.$classeController.'.php');

        $controller = new $classeController();
        $controller->setRequest($request);
        return $controller;
    }

    private function existController(string $controller) : string{
        $controllerReturn = (file_exists(PATH_CONTROLLER.CONTROLLER_BASENAME.$controller.'.php')) ? $controller : CONTROLLER_ERROR;
        return CONTROLLER_BASENAME.$controllerReturn;
    }

    /**
     * Détermine l'action à exécuter en fonction de la requête reçue
     */
    private function createAction(HttpQuery $request) : string {
        $action = "index";  // Action par défaut
        if ($request->existParameter('action')) {
            $action = $request->getParameter('action');
        }
        return $action;
    }

    /**
     * Gère une erreur d'exécution (exception)
     */
    private function handleError(string $exception) {
        $vue = new View('error');
        $vue->generate(array('msgErreur' => $exception));
    }

}