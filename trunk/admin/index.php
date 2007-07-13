<?php

// ---------------------------------------------------
//  Directories
// ---------------------------------------------------
define('FROG_VERSION', '0.2.1');
define('ROOT', dirname(__FILE__) . '/..');
define('APP_PATH', ROOT . '/app/backend');
define('ENV_PATH', ROOT . '/env');
define('FILES_DIR', ROOT . '/public'); // place where we will upload project files
define('BASE_FILES_DIR', '../public'); // place where we will upload project files (html url)
//define('BASE_URL', 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']));

// ---------------------------------------------------
//  config.php + extended config
// ---------------------------------------------------

define('SESSION_LIFETIME', 3600);
define('REMEMBER_LOGIN_LIFETIME', 1209600); // two weeks

// Defaults
define('DEFAULT_CONTROLLER', 'pages');
define('DEFAULT_ACTION', 'index');

// Default cookie settings...
define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIE_SECURE', false);

// ---------------------------------------------------
//  Init...
// ---------------------------------------------------
include_once ROOT . '/config/config.php';
include_once APP_PATH . '/functions.php';
include_once ENV_PATH . '/frog.php';

// security login
if (is_null(user_id()) && get_controller() != 'login') {
    redirect_to(get_url('login'));
}

// Get controller and action and execute...
Frog::executeAction(get_controller(), get_action());
