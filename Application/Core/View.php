<?php
namespace Application\Core;

/**
 * Classe modélisant une vue.
 */
class View
{
    /** Nom du fichier associé à la vue */
    private string $file;

    /** Titre de la vue (défini dans le fichier vue) */
    private string $titre;

    /** Ajour text js */
    private $jsText = null;
    
    /** Require js file */
    private array $jsFiles = [];
    
    private $cssText = null;

    /** Require css file */ 
    private array $cssFiles= [];

    /**
     * Constructeur :
     * Détermination du nom du fichier vue à partir de l'action et du constructeur
     * @param string $action Action à laquelle la vue est associée
     * @param string $controller Nom du contrôleur auquel la vue est associée
     */
    public function __construct(string $action, string $controller = '')
    {
        // La convention de nommage des fichiers vues est : Views/<$controller>/<$action>.php
        $file = PATH_VIEW;
        if ($controller != '') {
            $file = $file.$controller.'/';
        }
        $this->file = $file.$action.'.php';
    }

    /**
     * Génère et affiche la vue
     * 
     * @param array $donnees Données nécessaires à la génération de la vue
     */
    public function generate(array $donnees, $changeLayout = null)
    {
        // Génération de la partie spécifique de la vue
        $contenu = $this->genRender($this->file, $donnees);
        $cssFiles = $this->cssFiles;
        $jsFiles = $this->jsFiles;
        $jsText = $this->jsText;
        $cssText = $this->cssText;

        // On définit une variable locale accessible par la vue pour la racine Web.
        // Il s'agit du chemin vers le site sur le serveur Web.
        // Nécessaire pour les URI de type controller/action/id
        $rootWeb = URI_ROOT;// Configuration::get("rootWeb", "/");
        // Génération du gabarit commun utilisant la partie spécifique
        $view = $this->genRender(PATH_VIEW.((($changeLayout != null) ? $changeLayout : 'Layout').'.php'),
                [
                    'titre' => $this->titre, 
                    'contenu' => $contenu, 
                    'cssText' => $cssText, 
                    'cssFiles' => $cssFiles, 
                    'jsText' => $jsText, 
                    'jsFiles' => $jsFiles, 
                    'rootWeb' => $rootWeb
                ]);
        // Renvoi de la vue générée au navigateur
        echo $view;
    }

    /**
     * Génère un fichier vue et renvoie le résultat produit
     *
     * @param string $fichier Chemin du fichier vue à générer
     * @param array $donnees Données nécessaires à la génération de la vue
     * @return string Résultat de la génération de la vue
     * @throws Exception Exception Si le fichier vue est introuvable
     */
    private function genRender(string $fichier, array $donnees): string
    {
        if (file_exists($fichier)) {
            // Rend les éléments du tableau $donnees accessibles dans la vue
            extract($donnees);
            // Démarrage de la temporisation de sortie
            ob_start();
            // Inclut le fichier vue
            // Son résultat est placé dans le tampon de sortie
            require $fichier;
            // Arrêt de la temporisation et renvoi du tampon de sortie
            return ob_get_clean();
        }
        else {
            throw new \Exception("Fichier '$fichier' introuvable");
        }
    }

    /**
     * Nettoie une valeur insérée dans une page HTML
     * Doit être utilisée à chaque insertion de données dynamique dans une vue
     * Permet d'éviter les problèmes d'exécution de code indésirable (XSS) dans les vues générées
     *
     * @param string $value
     * @return string Valeur nettoyée
     */
    private function cleanHtml(string $value): string
    {
        // Convertit les caractères spéciaux en entités HTML
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }
}