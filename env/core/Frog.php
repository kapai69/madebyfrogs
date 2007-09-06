<?php

/**
 * Frog Environment class
 *
 * @version 0.1
 * @package Frog
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */

class Frog
{
    /**
     * Contruct controller and execute specific action
     *
     * @param string $controller_name
     * @param string $action
     * @return void
     */
    public static function executeAction($controller_name, $action_name)
    {
        if ( ! self::_useController($controller_name)) {
            exit("Controller '$controller_name' not found!");
        }

        $controller_class = self::_getControllerClass($controller_name);
        if ( ! class_exists($controller_class)) {
            exit("Controller class '$controller_class' doesn't exist!");
        }

        $controller = new $controller_class();
        if ( ! instance_of($controller, 'Controller')) {
            exit("Controller '$controller_name' is not a valid controller!");
        }

        $controller->execute($action_name);
    } // executeAction

    /**
     * Find and include specific controller based on controller name
     *
     * @param string $controller_name
     * @return boolean
     */
    private static function _useController($controller_name)
    {
        $controller_class = self::_getControllerClass($controller_name);
        if (class_exists($controller_class)) return true;

        $controller_file = APP_PATH."/controllers/{$controller_class}.php";
        if (file_exists($controller_file)) {
            require $controller_file;
            return true;
        }
        return false;
    } // useController

    /**
     * Return controller class based on controller name
     *
     * @param string $controller_name
     * @return string
     */
    private static function _getControllerClass($controller_name)
    {
        return camelize($controller_name).'Controller';
    } // getControllerClass

} // End Frog class
