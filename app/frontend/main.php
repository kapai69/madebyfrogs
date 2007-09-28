<?php

require APP_PATH . 'frontend/classes/Page.php';
require APP_PATH . 'frontend/classes/Filter.php';
require APP_PATH . 'frontend/classes/Behavior.php';

if (!defined('BASE_URL')) define('BASE_URL', 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']).'/?');

// global vars
$pages_loaded = array();

/**
 * Explode an URI and make a array of params
 */
function explodeUri($uri)
{
    return preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);
}

function find_page_by_slug($slug)
{
    global $__FROG_CONN__, $pages_loaded;
    
    $slug = trim($slug, '/');
    
    if (isset($pages_loaded[$slug])) {
        return $pages_loaded[$slug];
    }
    
    $sql = 'SELECT page.*, creator.name AS created_by_name, updator.name AS updated_by_name '
         . 'FROM '.TABLE_PREFIX.'page AS page '
         . 'LEFT JOIN '.TABLE_PREFIX.'user AS creator ON creator.id = page.created_by_id '
         . 'LEFT JOIN '.TABLE_PREFIX.'user AS updator ON updator.id = page.updated_by_id '
         . 'WHERE slug = ? AND (status_id='.Page::STATUS_REVIEWED.' OR status_id='.Page::STATUS_PUBLISHED.' OR status_id='.Page::STATUS_HIDDEN.')';
    
    $stmt = $__FROG_CONN__->prepare($sql);
    $stmt->execute(array($slug));
    
    if ($page = $stmt->fetchObject()) {
        
        // create the object page
        $page = new Page($page);
        
        // assign all is parts
        $page->part = get_parts($page->id);
        
        // check for behavior
        if ($page->behavior_id != '') {
            // add a instance of the behavior with the name of the behavior 
            $behavior_id = $page->behavior_id;
            $params = explodeUri(substr($uri, strlen($url)));
            $page->$behavior_id = Behavior::load($behavior_id, $page, $params);
        }
        
        $pages_loaded[$slug] = $page;
        
    } else {
        return null;
    }
    
    return $page;
} // findPagesByUrls

function get_parts($page_id)
{
    global $__FROG_CONN__;
    
    $objPart = new stdClass;

    $sql = 'SELECT name, content_html FROM '.TABLE_PREFIX.'page_part WHERE page_id = ?';
    
    if ($stmt = $__FROG_CONN__->prepare($sql)) {
        $stmt->execute(array($page_id));
        while ($part = $stmt->fetchObject()) {
            $objPart->{$part->name} = $part;
        }
    }
    
    return $objPart;
}

function url_match($url)
{
    $url = trim($url, '/');
    
    if (CURRENT_URI == $url) return true;
    
    return false;
}
  
function url_start_with($url)
{
    $url = trim($url, '/');

    if (CURRENT_URI == $url) return true;
    
    if (strpos(CURRENT_URI, $url) === 0) {
        return true;
    }
    return false;
}
  
function main()
{
    // get the uri string from the query
    $uri = $_SERVER['QUERY_STRING'];
    
    // real integration of GET
    if (strpos($uri, '?') !== false) {
        list($uri, $get_var) = explode('?', $uri);
        $exploded_get = explode('&', $query);
        if (count($exploded_get)) {
            foreach ($exploded_get as $get) {
                list($key, $value) = explode('=', $get);
                $_GET[$key] = $value;
            }
        }
    }
    
    define ('CURRENT_URI', trim($uri, '/'));

    // this is where 80% of the things is done 
    $page = find_page_by_slug($uri);

    // if we fund it, display it!
    if (is_object($page)) {
        $page->_executeLayout();
    } else {
        // load the not fund page from database
        include CORE_ROOT.'/404.php';
        exit;
    }
} // main

// ok come on! let's go! (movie: Hacker's)
main();
