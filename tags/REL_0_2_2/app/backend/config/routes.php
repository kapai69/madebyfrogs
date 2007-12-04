<?php

/**
 * Uri routing
 * -------------------------------------------------------------------------
 * This file lets you re-map URI requests to specific controller functions.
 *
 * Normaly the pattern is like this:
 *
 * http://www.exemple.com/controller/action/id
 *
 * But sometime you will want to remap this relationship to a differente one
 * -------------------------------------------------------------------------
 * Note: Routes will run in the order they are defined.
 *       Higher routes will always take precedence over lower ones.
 * -------------------------------------------------------------------------
 *
 * Syntax:
 *  :num will match a segment containing only numbers.
 *  :any will match a segment containing any character.
 *  and regex (Regular Expressions)
 *
 *  examples: 
 *      $route['product/:num'] = "catalog/product_lookup";
 *      $route['journals'] = "blogs";
 *      $route['blog/joe'] = "blogs/users/34";
 *      $route['products/([a-z]+)/(\d+)'] = "$1/id_$2";
 */

// Setting your own route rules here...
//$routes['page/:any'] = 'pages/view/$1';

$routes['logout'] = "login/logout";
