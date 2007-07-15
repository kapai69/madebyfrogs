<?php

/**
 * Parses the request URI into controller, action, and parameters.
 *
 * @version 0.1
 * @package Frog
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */

class Router
{

    var $controller_name;
    var $action_name;
    var $params;
    public $query_string;

    function __construct()
    {
        $this->query_string = $_SERVER['QUERY_STRING'];

        log_info('Route original: '.$this->query_string);
        
        // Explode the URI params
        $this->params = self::explodeUri($this->query_string);

        // Load the routes.php file
        $this->loadRouteFile();

        // Now make the parsing things
        $this->parse();

        // Assign controller name
        $this->controller_name = isset($this->params[0]) ? $this->params[0]: DEFAULT_CONTROLLER;

        // Assign action name
        $this->action_name = isset($this->params[1]) ? $this->params[1]: DEFAULT_ACTION;

        // remove controller and action from params
        if (is_array($this->params) && count($this->params) > 2) {
            $this->params = array_slice($this->params, 2);
        } else {
            $this->params = array();
        } // if
    } //__construct

    /**
     * Explode an URI and make a array of params
     *
     * @param string $uri Uri string to explode
     * @return array
     */
    public static function explodeUri($uri)
    {
        // split a uri by / in a array without empty param
        return preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);
    } // explodeUri

    /**
     * Load the routes.php file from app. config dir
     *
     * @param string $uri Uri string to explode
     * @return array
     */
    public function loadRouteFile()
    {
        // File containing redefination route
        $file = APP_PATH . '/config/routes.php';

        // Load the routes.php file
        if (file_exists($file)) {
            require $file;
        }

        // check if there's route, if so save it as property class
        $this->routes = (!isset($routes) || !is_array($routes)) ? array() : $routes;

        // clearing
        unset($routes);
    }

    /**
     * Parse the route from the redefined route if there's one
     * or just use the default routing
     *
     * @param none
     * @return void
     */
    public function parse()
    {
        // Do we even have any custom routing to deal with?
        if (count($this->routes) == 0) {
            return;
        }
        
        // Turn the segment array into a URI string
        $uri_string = join('/', $this->params);
        
        // Is there a literal match?    If so we're done
        if (isset($this->routes[$uri_string])) {
            $this->params = self::explodeUri($this->routes[$uri_string]);
            
            // add some info to logs
            log_info('Route used: '.$this->routes[$uri_string]);
            
            return;
        }
        
        // Loop through the route array looking for wildcards
        foreach ($this->routes as $route => $uri) {
            // Convert wildcards to RegEx
            if (strpos($route, ':') !== false) {
                $route = str_replace(':any', '(.+)', str_replace(':num', '([0-9]+)', $route));
            }
            
            // Does the RegEx match?
            if (preg_match('#^'.$route.'$#', $uri_string)) {
                // Do we have a back-reference?
                if (strpos($uri, '$') !== false && strpos($route, '(') !== false) {
                    $uri = preg_replace('#^'.$route.'$#', $uri, $uri_string);
                }
                $this->params = self::explodeUri($uri);
                
                // add some info to logs
                log_info('Route used: '.$uri);
                
                return;
            } // if
        } // foreach
        // nothing is found ... default routing is used
    } // parse

    public function getParams($pos=null)
    {
        if (is_null($pos)) {
            return $this->params;
        } else if (isset($this->params[$pos])) {
            return $this->params[$pos];
        }
        return null;
    }
    
    /**
     * Return a singleton instance of this class
     *
     * @param none
     * @return object this instance by reference
     */
    public static function &getInstance()
    {
        static $instance;
        
        if (!$instance) {
            $instance = new Router();
        }
        return $instance;
    }

} // End Router Class

//
// Router functions
//

/**
 * create a real nice url like http://yoursite.com/controller/action/params#anchor
 *
 * you can put many prams as you want,
 * if a params start with # it is considerated a Anchor,
 * so hope it's the last one ...
 *
 * @param string conrtoller, action, param and/or #anchor
 * @return string
 */
function get_url()
{
    $params = func_get_args();
    $url = '';
    foreach ($params as $param) {
        if (strlen($param)) {
            $url .= $param{0} == '#' ? $param: '/'. $param;
        }
    }
    return BASE_URL.$url;
}

function get_query_string()
{
    return join('/', Router::getInstance()->params);
}

function get_controller()
{
    return Router::getInstance()->controller_name;
}

function get_action()
{
    return Router::getInstance()->action_name;
}

function get_params($pos=null)
{
    return Router::getInstance()->getParams($pos);
}

function get_id()
{
    return Router::getInstance()->getParams(0);
}
