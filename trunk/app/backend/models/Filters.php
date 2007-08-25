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
    function getAll()
    {
        $filters = array();
        if ($handle = opendir(ROOT.'/filters/')) {
            while (false !== ($file = readdir($handle))) {
                // bug fix: ignore cache file (strating with a .) and both (. and ..)
                if (strpos($file, '.') !== 0) {
                    $filters[] = substr($file, 0, strlen($file)-4);
                }
            }
            closedir($handle);
        }
        return $filters;
    } // getAll

} // Filters
