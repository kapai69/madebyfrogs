<?php

require APP_PATH . '/frontend/classes/Page.php';
require APP_PATH . '/frontend/classes/Filters.php';
require APP_PATH . '/frontend/classes/Behaviors.php';

if (!defined('BASE_URL')) define('BASE_URL', 'http://'.dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']).'/?');

// global filters object
$filters = new Filters;

/**
 * Explode an URI and make a array of params
 */
function explodeUri($uri)
{
    return preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);
}

function findPageByUrls($uri, $includeParents=true)
{
    global $_PDO;
    
    // first index alaways need to be a /
    $urls = array_merge(array('/'), explodeUri($uri));
    $url = '';
    $pages = array();
    $paths = array();
    $page = new stdClass;
    $page->id = 0;
    
    foreach ($urls as $slug) {

        $sql = 'select pages.*, creator.name as created_by_name, updator.name as updated_by_name '
             . 'from '.TABLE_PREFIX.'pages as pages '
             . 'left join '.TABLE_PREFIX.'users as creator on creator.id = pages.created_by_id '
             . 'left join '.TABLE_PREFIX.'users as updator on updator.id = pages.updated_by_id '
             . 'where slug = :slug and parent_id = :parent_id and (status_id=50 or status_id=100 or status_id=101)';
    
        $stmt = $_PDO->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->bindParam(':parent_id', $page->id);
        $stmt->execute();
    
        if ($page = $stmt->fetchObject()) {
            // add the new slug to the current url
            if ($slug != '/') $url .= '/' . $slug;
            
            $page->url = $url;
            $page->paths = $paths;
        
            // create the object page
            $page = new Page($page);
        
            // assign all is parts
            $page->part = assignParts($page->id);
        
            // check for behavior
            if ($page->behavior_id != '') {
                // add a instance of the behavior with the name of the behavior 
                $behavior_id = $page->behavior_id;
                $params = explodeUri(substr($uri, strlen($url)));
                $page->$behavior_id = Behaviors::load($behavior_id, $page, $params);

                //$pages[] = $page;
                //break; // end foreach. behavior have to take the lead
            }
        
            $pages[] = $page;
            // add the new path to the current paths
            $paths[] = array('slug' => $page->slug, 'breadcrumb' => $page->breadcrumb);
        } else {
            return null;
        }
    } // foreach
    
    // get the slug page
    $page = array_pop($pages);

    // if don't need parents, return only the slug page
    if ( ! $includeParents) return $page;

    // page pointer like the varname said
    $page_pointer = $page;

    // make the reverse hierarchical structure 
    while (count($pages)) {
        $page_pointer->parent = array_pop($pages);
        $page_pointer = $page_pointer->parent;
    }
    return $page;
} // findPagesByUrls

function assignParts($page_id)
{
    global $_PDO;
    
    $objPart = new stdClass;

    $sql = 'select * from '.TABLE_PREFIX.'page_parts where page_id = :page_id';
    $stmt = $_PDO->prepare($sql);
    $stmt->bindParam(':page_id', $page_id);
    
    if ($stmt->execute()) {
        while ($part = $stmt->fetchObject()) {
            $objPart->{$part->name} = $part;
        }
    }
    
    return $objPart;
}

function url_match($url)
{
    if (CURRENT_URI == $url || CURRENT_URI . '/' == $url) return true;
    return false;
}
  
function url_start_with($url)
{
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

    // here is a small hack to add more flexibility for those who need get
    // and named variable from the query. simply use :var_name=var_value
    // and $_GET['var_name'] = var_value; gona be avalaible like magic
    /*if (strpos($uri, ':') !== false) {
        $exploded_uri = explode(':', $uri);
        $uri = array_shift($exploded_uri);
        if (count($exploded_uri)) {
            foreach ($exploded_uri as $get) {
                list($key, $value) = explode('=', $get);
                $_GET[$key] = $value;
            }
        }
    }*/
    
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
    
    define ('CURRENT_URI', $uri);

    // this is where 80% of the things is done 
    $page = findPageByUrls($uri);

    // if we fund it, display it!
    if (is_object($page)) {
        $page->_executeLayout();
    } else {
        // load the not fund page from database
        include ROOT.'/404.php';
        exit;
    }
} // main

// ok come on! let's go! (movie: Hacker's)
main();
