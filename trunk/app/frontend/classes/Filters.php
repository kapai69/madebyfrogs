<?php

/**
 * class Filters
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class Filters
{
    var $filter_loaded = array();

    function get($filter_name)
    {
        if (isset($this->filter_loaded[$filter_name])) {
            log_debug($filter_name. ' is return by Filter class!');
            return $this->filter_loaded[$filter_name];
        } else {
            $file = ROOT . '/filters/'.$filter_name.'.php';
            if (file_exists($file)) {
                include $file;
                $this->filter_loaded[$filter_name] = new $filter_name;
                log_debug($filter_name . ' is now loaded!');
                return $this->filter_loaded[$filter_name];
            }
        }
        return false;
    } // get

} // Filters
