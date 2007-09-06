<?php

/**
 * Initialize environment: load required files, set environment options etc.
 *
 * @version 0.2
 * @package Frog
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */

// Environment path is used by many environment classes. If not defined do it now
if (!defined('DEBUG'))              define('DEBUG', false);
if (!defined('ENV_PATH'))           define('ENV_PATH', dirname(__FILE__));
if (!defined('DEFAULT_CONTROLLER')) define('DEFAULT_CONTROLLER', 'index');
if (!defined('DEFAULT_ACTION'))     define('DEFAULT_ACTION', 'index');
if (!defined('BASE_URL'))           define('BASE_URL', 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']) .'/?');

// Configure PHP
// change this if your server doesn't have the same timezone that you
/*ini_set('date.timezone', 'GMT');
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set('GMT');
} else {
    putenv('TZ=GMT');
}*/

require ENV_PATH.'/constants.php';             // load Frog constants
require ENV_PATH.'/functions/general.php';     // load general functions
require ENV_PATH.'/core/Log.php';        // load Log class and functions
require ENV_PATH.'/core/Router.php';     // load router class and functions
require ENV_PATH.'/core/Frog.php';       // load the Frog environement class
require ENV_PATH.'/core/Flash.php';      // load flash functions and class
require ENV_PATH.'/core/Template.php';   // load Template class
require ENV_PATH.'/core/Controller.php'; // load controller class and functions
require ENV_PATH.'/core/Model.php';      // load model functions and class

// load database classes
// a remake of PDO in php (not all implemented) 
// support of (Mysql, SQLite and PqSQL)
if (!class_exists('PDO')) {
    require ENV_PATH.'/core/database/PDO.php';
}

$_PDO = new PDO(DB_DSN, DB_USER, DB_PASS);
//set the charset to utf-8 ('utf8' for mysql)
$_PDO->exec("set names 'utf8'");

if (DEBUG) {
    ini_set('display_errors', true);
    error_reporting(E_ALL);
    load_library('benchmark');
    Benchmark::getInstance()->start();
} else {
    ini_set('display_errors', false);
    ini_set('log_errors', true);
}

// Start the session
if (!ini_get('session.auto_start') || (strtolower(ini_get('session.auto_start')) == 'off')) {
    session_start();
}

// Remove slashes is magic quotes gpc is on from $_GET, $_POST and $_COOKIE
if (get_magic_quotes_gpc()) {
    fix_input_quotes();
}
