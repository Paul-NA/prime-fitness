<?php
//Simple vérification que l'on n'accède pas à ce fichier directement depuis le navigateur
if(!defined('URI_ROOT')) die('heu non désolé :)');
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?php echo $titre ?? 'Aucun titre n\'a été fourni !' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Gestionnaire de salles de Sport ECF décembre 2022">

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Style Css -->
    <link href="<?php echo URI_ROOT;?>/Assets/Css/Style.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="icon" href="<?php echo URI_ROOT;?>/Assets/Images/favicon.svg">
    <link rel="apple-touch-icon" href="<?php echo URI_ROOT;?>/Assets/Images/favicon.svg" sizes="180x180">
    <meta name="theme-color" content="#7952b3">

    <base href="<?php echo URI_ROOT;?>" >
    <link rel="canonical" href="<?php echo URI_ROOT;?>">

<?php
/**
 * Inclusion des fichiers css si nécessaire
 */
if(!empty($cssFiles) && count($cssFiles) > 0) {
    foreach ($cssFiles as $value) {
        echo "\r\n" .
            '    <link href="' . $value . '" rel="stylesheet">';
    }
}

/**
 * Inclusion d'un script javascript css si nécessaire
 */
if (!empty($cssText)) {
    echo '<style>'.
            $cssText
       . '</style>';
}
?>
</head>
<body class="bg-image " style="background-image: url('<?= URI_ROOT;?>/Assets/Images/Background/background12.webp'); height: 100vh">

<?php

/**
 * Affichage du contenu du site
 */
echo $contenu ?? 'No content';


echo '<script>const rootPath = "'.URI_ROOT.'";</script>';
/**
 * Inclusion des fichiers javascript si nécessaire
 */
if(!empty($jsFiles) && count($jsFiles) > 0) {
    foreach ($jsFiles as $value) {
        echo "\r\n" . '<script src="' . $value . '"></script>' . "\r\n";
    }
}
/**
 * Inclusion d'un script javascript si nécessaire
 */
if(!empty($jsText)){
    echo '<script>'."\r\n".$jsText."\r\n".'</script>'."\r\n";
}

/**
 * On affiche en text brute les requêtes SQL
 */
if(DEBUG_SQL){
    echo '<pre>';
    print_r(Application\Core\Database::showQuery());
    echo '</pre>';
}
?>

</body>
</html>