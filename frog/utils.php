<?php
/**
   Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
   Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as
   published by the Free Software Foundation, either version 3 of the
   License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * This file contains some usefull utility functions which we would like to be
 * available outside the Framework.
 */

/**
 * Tests whether a text ends with the given string or not.
 *
 * @param     string
 * @param     string
 * @return    bool
 */
function endsWith($haystack, $needle){
    return strrpos($haystack, $needle) === strlen($haystack)-strlen($needle);
}


?>
