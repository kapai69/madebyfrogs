<?php

/**
 * Controller class
 *
 * @version 0.2
 * @package Frog
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */

class Controller
{

    private $_view = false;
    private $_layout = false;

    /**
     * Execute specific controller action
     *
     * @param string $action
     *
     * @return void
     */
    public function execute($action)
    {
        // Prepare action name
        $action = trim($action);

        // If we have valid action execute and done ... Else die
        if ($this->_isValidAction($action)) {
            // old version was $this->$action();
            // now call method AND sending all params to him
            call_user_func_array(array($this, $action), get_params());
        } else {
            exit("<strong> {$action} </strong> is not a valid action from <strong>". get_class($this) .'</strong>');
        }
    } // execute
    
    /**
     * Forward execution from action you are in to specific controller action
     * 
     * This function lets controller actions to forword to other action without need to redirect
     * user to that specific action using URL or new console request. $action_name is required
     * argument, but $controller_name can be left out. If $controller_name is empty or it is the
     * same name of the current controller $this will be used to execute $action_name
     *
     * @param string $action_name
     * @param string $controller_name
     *
     * @return void
     */
    public function forward($action_name, $controller_name=null)
    {
        if ((trim($controller_name) == '') || ($controller_name == get_class($this))) {
            $this->execute($action_name);
        } else {
            Frog::executeAction($controller_name, $action_name);
        }
    } // forward

    /**
     * Check if specific $action is valid controller action (method exists and it is not "private")
     *
     * @param string $action
     *
     * @return boolean
     */
    private function _isValidAction($action)
    {
        // it's a private method of the class return false
        if (substr($action, 0, 1) == '_') return false;

        // is not a method of the class return false
        if (!method_exists($this, $action)) return false;

        return true;
    } // validAction()

    // ---------------------------------------------------
    //  Model methods
    // ---------------------------------------------------

    /**
     * Load a model class
     *
     * @param string $model model to load
     *
     * @return void
     */
    public function loadModel($model, $var_name=null)
    {
        $camelize_model = camelize($model);
        $var_name = is_null($var_name) ? $model : $var_name;
        
        $file = APP_PATH . '/models/' . $camelize_model . '.php';
        // by default file must be: app/models/ModelName.php, 
        // but it can be in: app/models/model_names/ModelName.php
        if (file_exists($file)) {
            require $file;
            $this->$var_name = new $camelize_model();
        } else {
            $file = APP_PATH . '/models/' . pluralize($model) . '/' . $camelize_model . '.php';
            if (file_exists($file)) {
                require $file;
                $this->$var_name = new $camelize_model();
            }
        }
    } // loadModel

    // ---------------------------------------------------
    //  View methods
    // ---------------------------------------------------

    /**
     * Define the view to load as the template
     *
     * @param string $view controller/viewfile to load
     *
     * @return void
     */
    public function setView($view)
    {
        $this->_view = new Template(APP_PATH . '/views/' . $view . '.php');
    } // setView

    /**
     * Assign specific variable to the view template
     *
     * @param string $name Variable name
     * @param mixed $value Variable value
     *
     * @return boolean
     */
    public function assignToView($name, $value=null)
    {
        $this->_view->assign($name, $value);
    } // assignToView

    /**
     * Fetch view template and return output as string
     *
     * @return string
     */
    public function fetchView()
    {
        return $this->_view->fetch();
    } // fetchView

    /**
     * Display view template
     *
     * @return boolean
     */
    public function displayView()
    {
        return $this->_view->display();
    } // displayView
    
    
    // ---------------------------------------------------
    //  Layout methods
    // ---------------------------------------------------

    /**
     * Define the layout to load as the template
     *
     * @param string $layout file to load (view)
     *
     * @return void
     */
    public function setLayout($layout)
    {
        $this->_layout = new Template(APP_PATH . '/layouts/' . $layout . '.php');
    } // setLayout

    /**
     * Assign specific variable to the layout template
     *
     * @param string $name Variable name
     * @param mixed $value Variable value
     *
     * @return void
     */
    public function assignToLayout($name, $value=null)
    {
        $this->_layout->assign($name, $value);
    } // assignToLayout

    /**
     * Fetch layout template and return output as string
     *
     * @return string
     */
    public function fetchLayout()
    {
        return $this->_layout->fetch();
    } // fetchLayout

    /**
     * Display layout template
     *
     * @return boolean
     */
    public function displayLayout()
    {
        return $this->_layout->display();
    } // displayLayout
    
    // ---------------------------------------------------
    //  Rendering related methods
    // ---------------------------------------------------
    
    /**
     * Render content of specific view / layout combination, default layout var 'content'
     * 
     * If $exit is true script will exit when rendering is finished. True by default
     *
     * @param boolean $exit Exit when rendering is done, true by default
     *
     * @return void
     */
    public function render($exit=true)
    {
        if ($this->_layout) {
            // Assign content
            $this->assignToLayout('content_for_layout', $this->fetchView());
            // Render layout
            $this->displayLayout();
        } else {
            $this->displayView();
        }
        // exit ?
        if ($exit) exit();
    } // render

} // Controller
