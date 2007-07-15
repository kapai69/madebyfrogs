<?php

  /**
   * Add helper to page (Controller and View)
   * Need more explication here...
   *
   * @version 0.1
   * @package Frog
   * @author Philippe Archambault <philippe.archambault@gmail.com>
   * @license http://www.opensource.org/licenses/mit-license.html MIT License
   */

  $GLOBALS['_page_helper_'] = array('even_odd' => false);

  function page_set_title($title)
  {
    $GLOBALS['_page_helper_']['title'] = $title;
  }

  function page_title()
  {
    return array_var($GLOBALS['_page_helper_'], 'title', '');
  }

  function page_add_action($label, $url)
  {
    if (!isset($GLOBALS['_page_helper_']['actions'])) {
      $GLOBALS['_page_helper_']['actions'] = array();
    }
    $GLOBALS['_page_helper_']['actions'][$label] = $url;
  }

  function page_actions()
  {
    return array_var($GLOBALS['_page_helper_'], 'actions');
  }

  function page_add_bread_crumb($label, $url=null)
  {
    if (!isset($GLOBALS['_page_helper_']['bread_crumbs'])) {
      $GLOBALS['_page_helper_']['bread_crumbs'] = array();
    }
    $GLOBALS['_page_helper_']['bread_crumbs'][] = array('label' => $label, 'url' => $url);
  }

  function page_bread_crumbs()
  {
    return array_var($GLOBALS['_page_helper_'], 'bread_crumbs', array());
  }
  
  function even_odd()
  {
    $GLOBALS['_page_helper_']['even_odd'] = !$GLOBALS['_page_helper_']['even_odd'];
    return $GLOBALS['_page_helper_']['even_odd'] ? 'even': 'odd';
  }
  // alias
  function odd_even()
  {
    return even_odd();
  }
