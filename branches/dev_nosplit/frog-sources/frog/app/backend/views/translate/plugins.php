<?php
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
     * Translated by: Your Name <email@domain.something>
     * Plugin       : '.$pluginname.'
     * Frog version : x.y.z
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