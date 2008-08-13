<?php

/**
   Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
   Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as
   published by the Free Software Foundation, either version 3 of the
   License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
   Class PluginController

   Plugin controller to dispatch to all plugins controllers

   Since  0.9
 */

class PluginController extends Controller
{
    public $plugin;
    
    function __construct()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
            redirect(get_url('login'));
    }
    
    public function render($view, $vars=array())
    {
        if ($this->layout)
        {
            $this->layout_vars['content_for_layout'] = new View('../../../plugins/'.$view, $vars);
            return new View('../layouts/'.$this->layout, $this->layout_vars);
        }
        else return new View('../../../plugins/'.$view, $vars);
    }
    
    public function execute($action, $params)
    {
        if (isset(Plugin::$controllers[$action]))
        {
            $plugin = Plugin::$controllers[$action];
            if (file_exists($plugin->file))
            {
                include $plugin->file;
                
                $plugin_controller = new $plugin->class_name;
                
                $action = count($params) ? array_shift($params): 'index';
                
                call_user_func_array(
                    array($plugin_controller, $action),
                    $params
                );
            }
            else throw new Exception("Plugin controller file '{$plugin->file}' was not found!");
        }
        else throw new Exception("Action '{$action}' is not valid!");
    }
    
} // end PluginController class