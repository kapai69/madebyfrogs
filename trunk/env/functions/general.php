<?php

/**
 * Generals functions
 *
 * @version 0.1
 * @package Frog
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */

function load_library($library)
{
    static $libraries = array();
    $library = camelize($library);
    
    // if already loaded, do nothing
    if (in_array($library, $libraries)) return;
    
    $file = ENV_PATH.'/libraries/'.$library.'.php';
    if (file_exists($file)) {
        require $file;
        $libraries[] = $library;
    }
}

function use_helper($helper)
{
    static $helpers = array();
    $helper = strtolower($helper);
    
    // if already loaded, do nothing
    if (in_array($helper, $helpers)) return;
    
    $file = ENV_PATH.'/helpers/'.$helper.'.php';
    if (file_exists($file)) {
        require $file;
        $helpers[] = $helper;
    }
}

// alias to use_helper
function load_helper($helper)
{
    use_helper($helper);
}

/**
 * Check if $object is valid $class instance
 *
 * @param mixed $object Variable that need to be checked agains classname
 * @param string $class Classname
 * @return boolean
 */
function instance_of(&$object, $class)
{
    return is_object($object) && is_a($object, $class);
} // instance_of()

/**
 * Return variable from hash (associative array). If value does not exists 
 * return default value
 *
 * @param array $from Hash
 * @param string $name
 * @param mixed $default
 * @return mixed
 */
function array_var(&$from, $name, $default = null)
{
    return isset($from[$name]) ? $from[$name] : $default;
} // array_var()


/**
 * Equivalent to htmlspecialchars(), but allows &#[0-9]+ (for unicode)
 * 
 * This function was taken from punBB codebase <http://www.punbb.org/>
 *
 * @param string $str
 * @return string
 */
function clean_string($str)
{
    $str = preg_replace('/&(?!#[0-9]+;)/s', '&amp;', $str);
    $str = str_replace(array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $str);
    return $str;
} // clean()

//
// date function
//

/**
 * Ajuste the date to fit the timezone 
 * will try to find a timezone in session if no timezone in param
 * else will give the GMT 0000 timezone date
 *
 * @param string $options default option from function date()
 * @param string $timezone beetween -12 and 13
 * @return date
 */
function tz_date($options, $timezone=null)
{
    if (is_null($timezone)) $timezone = array_var($_SESSION, 'timezone', 0);
    return gmdate($options, time() + 3600*($timezone+date("I")));
}

function day_from_now($date)
{
    $time_now = strtotime(tz_date('Y-m-d'));
    $time_date = strtotime($date);

    $diff = floor(($time_date - $time_now) / 86400);

    // today
    if ($diff == 0) return _('Totay');
    else if ($diff == 1) return _('Tomorrow');
    else if ($diff == -1) return _('Yesterday');
    else if ($diff > 1) return $diff .' '. _('days left');
    else return abs($diff) .' '. _('days late');
}

//
// camelize, underscrore, humanize
//

/**
 * Returns something like: ClassNameController from: class_name_controller
 *
 * @param string $lower_case_and_underscored_word Word to camelize
 * @return string Camelized word. LikeThis.
 */
function camelize($lower_case_and_underscored_word)
{
    return str_replace(" ","",ucwords(str_replace("_"," ",$lower_case_and_underscored_word)));
} // camelize()

/**
 * Returns an underscore-syntaxed ($like_this_dear_reader) version of the $camel_cased_word.
 *
 * @param string $camel_cased_word Camel-cased word to be "underscorized"
 * @return string Underscore-syntaxed version of the $camel_cased_word
 */
function underscore($camel_cased_word)
{
    $camel_cased_word = preg_replace('/([A-Z]+)([A-Z])/','\1_\2', $camel_cased_word);
    return strtolower(preg_replace('/([a-z])([A-Z])/','\1_\2', $camel_cased_word));
} // underscore()

/**
 * Returns a human-readable string from $lower_case_and_underscored_word,
 * by replacing underscores with a space, and by upper-casing the initial characters.
 *
 * @param string $lower_case_and_underscored_word String to be made more readable
 * @return string Human-readable string
 */
function humanize($lower_case_and_underscored_word)
{
    return ucwords(str_replace("_"," ",$lower_case_and_underscored_word));
} // humanize()

/**
 * Return $word in plural form.
 *
 * @param string $word Word in singular
 * @return string Word in plural
 */
function pluralize($word)
{
    $plural_rules = array(
        '/(s)tatus$/i'             => '\1\2tatuses',
        '/^(ox)$/i'                => '\1\2en',     # ox
        '/([m|l])ouse$/i'          => '\1ice',      # mouse, louse
        '/(matr|vert|ind)ix|ex$/i' => '\1ices',     # matrix, vertex, index
        '/(x|ch|ss|sh)$/i'         => '\1es',       # search, switch, fix, box, process, address
        '/([^aeiouy]|qu)y$/i'      => '\1ies',      # query, ability, agency
        '/(hive)$/i'               => '\1s',        # archive, hive
        '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',    # half, safe, wife
        '/sis$/i'                  => 'ses',        # basis, diagnosis
        '/([ti])um$/i'             => '\1a',        # datum, medium
        '/(p)erson$/i'             => '\1eople',    # person, salesperson
        '/(m)an$/i'                => '\1en',       # man, woman, spokesman
        '/(c)hild$/i'              => '\1hildren',  # child
        '/(buffal|tomat)o$/i'      => '\1\2oes',    # buffalo, tomato
        '/(bu)s$/i'                => '\1\2ses',    # bus
        '/(alias)/i'               => '\1es',       # alias
        '/(octop|vir)us$/i'        => '\1i',        # octopus, virus - virus has no defined plural (according to Latin/dictionary.com), but viri is better than viruses/viruss
        '/(ax|cri|test)is$/i'      => '\1es',       # axis, crisis
        '/s$/'                     =>  's',         # no change (compatibility)
        '/$/'                      =>  's');

    foreach ($plural_rules as $rule => $replacement) {
        if (preg_match($rule, $word)){
            return preg_replace($rule, $replacement, $word);
        }
    }
    return $word;
} // pluralize

/**
* Return $word in singular form.
*
* @param string $word Word in plural
* @return string Word in singular
*/
function singularize($word)
{
    $singular_rules = array(
        '/(s)tatuses$/i'        => '\1\2tatus',
        '/(matr)ices$/i'        =>'\1ix',
        '/(vert|ind)ices$/i'    => '\1ex',
        '/^(ox)en/i'            => '\1',
        '/(alias)es$/i'         => '\1',
        '/([octop|vir])i$/i'    => '\1us',
        '/(cris|ax|test)es$/i'  => '\1is',
        '/(shoe)s$/i'           => '\1',
        '/(o)es$/i'             => '\1',
        '/(bus)es$/i'           => '\1',
        '/([m|l])ice$/i'        => '\1ouse',
        '/(x|ch|ss|sh)es$/i'    => '\1',
        '/(m)ovies$/i'          => '\1\2ovie',
        '/(s)eries$/i'          => '\1\2eries',
        '/([^aeiouy]|qu)ies$/i' => '\1y',
        '/([lr])ves$/i'         => '\1f',
        '/(tive)s$/i'           => '\1',
        '/(hive)s$/i'           => '\1',
        '/([^f])ves$/i'         => '\1fe',
        '/(^analy)ses$/i'       => '\1sis',
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
        '/([ti])a$/i'           => '\1um',
        '/(p)eople$/i'          => '\1\2erson',
        '/(m)en$/i'             => '\1an',
        '/(c)hildren$/i'        => '\1\2hild',
        '/(n)ews$/i'            => '\1\2ews',
        '/s$/i'                 => '');

    foreach ($singular_rules as $rule => $replacement) {
        if (preg_match($rule, $word)) {
            return preg_replace($rule, $replacement, $word);
        }
    }

    // should not return false is not matched
    return $word;
} // singularize

//
// validation email and url, redirect
//

/**
 * Check if selected email has valid email format
 *
 * @param string $user_email Email address
 * @return boolean
 */
function is_valid_email($user_email)
{
    $chars = EMAIL_FORMAT;
    if(strstr($user_email, '@') && strstr($user_email, '.')) {
        return (boolean) preg_match($chars, $user_email);
    } else {
        return false;
    } // if
} // is_valid_email

/**
 * Verify the syntax of the given URL.
 *
 * @param $url The URL to verify.
 * @return boolean
 */
function is_valid_url($url)
{
    return preg_match(URL_FORMAT, $url);
} // is_valid_url 

/**
* Redirect to specific URL (header redirection)
*
* @param string $to Redirect to this URL
* @param boolean $exit Die when finished
* @return void
*/
function redirect_to($to, $exit = true)
{
    $to = trim($to);
    
    // if need to replace '&amp;' to '&', replace it
    if (strpos($to, '&amp;') !== false) $to = str_replace('&amp;', '&', $to);
    
    // change header to redirect to the new location
    header('Location: ' . $to);
    
    // exit ?
    if ($exit) exit();
} // redirect_to

/**
* Redirect to refered url (header redirection)
*
* @param none
* @return void
*/
function redirect_to_referer()
{
    redirect_to(get_url_referer());
} // redirect_to_refered

// 
//  POST and GET
// 

/**
 * This function will strip slashes if magic quotes is enabled so 
 * all input data ($_GET, $_POST, $_COOKIE) is free of slashes
 *
 * @param none
 * @return void
 */
function fix_input_quotes()
{
    if (get_magic_quotes_gpc()) {
        $in = array(&$_GET, &$_POST, &$_COOKIE);
        while (list($k,$v) = each($in)) {
            foreach ($v as $key => $val) {
                if (!is_array($val)) {
                     $in[$k][$key] = stripslashes($val); continue;
                }
                $in[] =& $in[$k][$key];
            } // foreach
        } // while
        unset($in);
    }
} // fix_input_quotes

/**
 * This function will walk recursivly through array and strip slashes from scalar values
 *
 * @param array $array
 * @return void
 */
function array_stripslashes(&$array)
{
    if (!is_array($array)) return;
    foreach ($array as $k => $v) {
        if (is_array($array[$k])) {
            array_stripslashes($array[$k]);
        }
        else {
            $array[$k] = stripslashes($array[$k]);
        } // if
    } // foreach
} // array_stripslashes


//
// conversion functions
//

function convert_size($num)
{
    if ($num >= 1073741824) $num = round($num / 1073741824 * 100) / 100 .' gb';
    else if ($num >= 1048576) $num = round($num / 1048576 * 100) / 100 .' mb';
    else if ($num >= 1024) $num = round($num / 1024 * 100) / 100 .' kb';
    else $num .= ' b';
    return $num;
}

// information about time and memory

function memory_usage()
{
    return convert_size(memory_get_usage());
}

function execution_time()
{
    return Benchmark::getInstance()->display();
}

// Usage: upload_file($_FILE['file']['name'],'temp/',$_FILE['file']['tmp_name'])
function upload_file($origin, $dest, $tmp_name, $overwrite=false)
{
    $origin = strtolower(basename($origin));
    $full_dest = $dest.$origin;
    $file_name = $origin;
    for ($i=1; file_exists($full_dest); $i++) {
        if ($overwrite) {
            unlink($full_dest);
            continue;
        }
        $file_ext = (strpos($origin, '.') === false ? '': '.'.substr(strrchr($origin, '.'), 1));
        $file_name = substr($origin, 0, strlen($origin) - strlen($file_ext)).'_'.$i.$file_ext;
        $full_dest = $dest.$file_name;
    }

    if (move_uploaded_file($tmp_name, $full_dest)) {
        // change mode of the dire to 0644 by default
        chmod($full_dest, 0644);
        return $file_name;
    }
    return false;
} // upload_file

// recursiv rmdir
function rrmdir($dirname)
{
    if (is_dir($dirname)) { // Operate on dirs only
        if (substr($dirname,-1)!='/') { $dirname.='/'; } // Append slash if necessary
        $handle = opendir($dirname);
        while (false !== ($file = readdir($handle))) {
            if ($file!='.' && $file!= '..') { // Ignore . and ..
                $path = $dirname.$file;
                if (is_dir($path)) { // Recurse if subdir, Delete if file
                    rrmdir($path);
                } else {
                    unlink($path);
                }
            }
        }
        closedir($handle);
        rmdir($dirname); // Remove dir
        return true; // Return array of deleted items
    } else {
        return false; // Return false if attempting to operate on a file
    }
} // rrmdir
