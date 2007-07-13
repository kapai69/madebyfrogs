<?php

/**
 * class Behaviors
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1.5
 */

class Behaviors
{
    function getAll()
    {
        $behaviors = array();
        $dir = ROOT.'/behaviors/';
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (is_dir($dir.$file)) {
                        $behaviors[] = $file;
                    }
                }
            }
            closedir($handle);
        }
        return $behaviors;
    } // getAll

} // Behaviors
