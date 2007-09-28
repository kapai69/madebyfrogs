<?php

/**
 * class Filter
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.5
 */

class Filter
{
    private static $filters_loaded = array();
    
    public static function findAll()
    {
        $filters = array();
        if ($handle = opendir(CORE_ROOT.'filters')) {
            while (false !== ($file = readdir($handle))) {
                // bug fix: ignore hidden files (strating with a .) and both (. and ..)
                if (strpos($file, '.') !== 0) {
                    $filters[] = substr($file, 0, strlen($file)-4);
                }
            }
            closedir($handle);
        }
        return $filters;
    } // findAll
    
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

} // end Filter class
