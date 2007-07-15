<?php

// ---------------------------------------------------
//  Directories
// ---------------------------------------------------

define('ROOT', dirname(__FILE__));

define('APP_PATH', ROOT . '/app');
define('ENV_PATH', ROOT . '/env');
//define('BASE_URL', 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']));

define('DEFAULT_CHARSET', 'utf-8');

// ---------------------------------------------------
//  Init...
// ---------------------------------------------------
require ROOT . '/config/config.php';

// if you have installed frog and see this line, you can comment it or delete it :)
if (!defined('DEBUG')) { header('Location: install/'); exit(); }

require ENV_PATH . '/constants.php';
require ENV_PATH . '/functions/general.php';
require ENV_PATH . '/core/Log.php';

load_library('benchmark');
Benchmark::getInstance()->start();

if (!class_exists('PDO')) {
    require ENV_PATH.'/core/database/PDO.php';
}

$_PDO = new PDO(DB_DSN, DB_USER, DB_PASS);

if ($_PDO->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') {
    $_PDO->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    $_PDO->exec("set names 'utf8'");
}

// run everything!
require APP_PATH . '/frontend/main.php';
