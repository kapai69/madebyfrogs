<?php

/**
 * class Behavior
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1.4
 */

class Behavior
{
    public static function load($behavior_name, $page)
    {
        $behavior_class_name = Inflector::camelize($behavior_name);
        $file = CORE_ROOT.'behaviors/'.$behavior_name.'/'.$behavior_class_name.'.php';
        
        if (file_exists($file)) {
            include $file;
            //log_debug($behavior_name . ' is loaded!');
            return new $behavior_class_name($page);
        } else {
            exit ("Behavior $behavior_name not found!");
        }
    }

} // Behavior
