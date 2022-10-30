<?php
//Simple vérification que l'on n'accède pas à ce fichier directement depuis le navigateur
if(!defined('URI_ROOT')) die('heu non désolé :)');
/**
 * Inclusion du contenu
 */
echo $contenu ?? 'empty content';
