<?php

/**
 * Language library
 *
 * @version 0.2
 * @package Frog
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 */

function __($string)
{
    if (!empty($GLOBALS['langs'][$string])) return $GLOBALS['langs'][$string];
    return $string;
}
