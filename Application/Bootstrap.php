<?php
require_once 'Core/Autoloader.php';
require_once 'Config/env.php';
require_once 'Config/envDatabase.php';
require_once 'Config/envWebsite.php';
require_once 'Config/Form.php';
require_once 'Lang/Fr.php';

/**
 * Constant PATH
 */
const DS = DIRECTORY_SEPARATOR;
define('PATH_ROOT', realpath(dirname(__FILE__) . DS. '../'));
const PATH_APP = PATH_ROOT . DS . 'Application';
const PATH_CONTROLLER = PATH_APP . DS . 'Controllers' . DS;
const PATH_VIEW = PATH_APP . DS . 'Views' . DS;
const PATH_CONF = PATH_APP . DS . 'Config' . DS;