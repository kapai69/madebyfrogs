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

Plugin::setInfos(array(
    'id'          => 'page_not_found',
    'title'       => 'Page not found', 
    'description' => 'Provides Page not found page types.', 
    'version'     => '1.0.0', 
    'website'     => 'http://www.madebyfrog.com/',
    'update_url'  => 'http://www.madebyfrog.com/plugin-versions.xml'
));

Behavior::add('page_not_found', '');
Observer::observe('page_not_found', 'behavior_page_not_found');

function behavior_page_not_found()
{
    global $__FROG_CONN__;
    
    $sql = 'SELECT * FROM '.TABLE_PREFIX."page WHERE behavior_id='page_not_found'";
    $stmt = $__FROG_CONN__->prepare($sql);
    $stmt->execute();
    
    if ($page = $stmt->fetchObject())
    {
        $page = find_page_by_uri($page->slug);
        
        // if we fund it, display it!
        if (is_object($page))
        {
            header("HTTP/1.0 404 Not Found");
            header("Status: 404 Not Found");
              
            $page->_executeLayout();
            exit(); // need to exit here otherwise the true error page will be sended
        }
    }
}