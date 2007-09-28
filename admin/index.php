<?php

// ---------------------------------------------------
//  Directories
// ---------------------------------------------------
define('FROG_VERSION', '0.5.8');

define('CORE_ROOT', dirname(__FILE__) . '/../');

define('APP_PATH',  CORE_ROOT . 'app/backend/');
define('FILES_DIR', CORE_ROOT . 'public'); // place where we will upload project files

define('BASE_URL',  'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']).'/?/');
//define('BASE_URL', 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']).'/');
define('BASE_FILES_DIR', trim(BASE_URL, '?/').'/../public'); // place where we will upload project files (html url)

// ---------------------------------------------------
//  config.php + extended config
// ---------------------------------------------------

define('SESSION_LIFETIME', 3600);
define('REMEMBER_LOGIN_LIFETIME', 1209600); // two weeks

// Defaults
define('DEFAULT_CONTROLLER', 'page');
define('DEFAULT_ACTION', 'index');

// Default cookie settings...
define('COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIE_SECURE', false);

// ---------------------------------------------------
//  Init...
// ---------------------------------------------------
include CORE_ROOT . 'config/config.php';
include CORE_ROOT . 'Framework.php';

//Record::connection(new PDO(DB_DSN, DB_USER, DB_PASS));
Record::connection($__FROG_CONN__);
Record::getConnection()->exec("set names 'utf8'");

// security login
//if (is_null($_SESSION['user_id']) && get_controller() != 'login') {
//    redirect(get_url('login'));
//}

use_helper('I18n');
I18n::setLocale(LANGUAGE);

// set route
//Dispatcher::addRoute('/logout', '/login/logout');

// Get controller and action and execute...
Dispatcher::dispatch();
