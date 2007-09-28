<?php

/**
 * class Filter
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class Filter
{
    private $filters_loaded = array();

    public static function get($filter_id)
    {
        if (isset(self::$filters_loaded[$filter_id])) {
            return self::$filters_loaded[$filter_id];
        } else {
            $file = CORE_ROOT . 'filters/'.$filter_id.'.php';
            if (file_exists($file)) {
                include $file;
                self::$filters_loaded[$filter_id] = new $filter_id;
                return self::$filters_loaded[$filter_id];
            }
        }
        return false;
    }

} // Filter
