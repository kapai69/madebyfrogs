<?php

/*
 * Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @package frog
 * @subpackage views
 *
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/agpl.html AGPL License
 * @copyright Martijn van der Kleijn, 2008
 */

// Prevent any possible caching
header('Content-type: text/plain');
header("Cache-Control: no-cache, must-revalidate");     // HTTP/1.1
header("Expires: Tue, 05 Dec 2000 00:00:01 GMT");       // Date in the past

// Init
$current = null;
$pluginname = null;

// Do actual work
foreach ($files as $file => $strings) {
    $file = substr($file, strpos($file, '/plugins/') + 9);
    $file = substr($file, 0, strpos($file, '/'));

    if ($current == null) {
        $current = $file;
        $pluginname = $file;
        $tmp = array();
    }

    if ($current == $file) {
        foreach ($strings as $string)
            $tmp[] = $string;
    }
    else {
        writeTemplate($pluginname, $tmp);

        $current = $file;
        $pluginname = $file;
        $tmp = array();
        foreach ($strings as $string)
            $tmp[] = $string;
    }
}

writeTemplate($pluginname, $tmp);

// End work

/**
 * Outputs the plugin template.
 *
 * @param string $pluginname
 * @param array  $strings
 */
function writeTemplate($pluginname, $strings) {
    echo '<?php

    /**
     * YourLanguage file for plugin '.$pluginname.'
     *
     * @package frog
     * @subpackage plugin.'.$pluginname.'.translations
     *
     * @author Your Name <email@domain.something>
     * @version Frog x.y.z
     */

    return array(
    ';

    $strings = removeDoubles($strings);
    sort($strings);

    foreach ($strings as $string) {
        echo "\t'".$string."' => '',\n";
    }    

    echo "    );\n\n\n\n\n\n";
}

/**
 * Removes any double entries in the array.
 *
 * @param array $array
 * @return array 
 */
function removeDoubles($array) {
    $result = array();
        
    foreach ($array as $string) {
        if (!in_array($string, $result))
        $result[] = $string;
    }

    return $result;
}