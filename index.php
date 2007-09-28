<?php

// ---------------------------------------------------
//  Directories
// ---------------------------------------------------

define('CORE_ROOT', dirname(__FILE__) . '/');

define('APP_PATH', CORE_ROOT . 'app/');
//define('BASE_URL', 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']).'/');

// ---------------------------------------------------
//  Init...
// ---------------------------------------------------

require CORE_ROOT . 'config/config.php';

// if you have installed frog and see this line, you can comment it or delete it :)
if (!defined('DEBUG')) { header('Location: install/'); exit(); }

$__FROG_CONN__->exec("set names 'utf8'");

// run everything!
require APP_PATH . 'frontend/main.php';
