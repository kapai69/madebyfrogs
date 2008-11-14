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
 * The Archive plugin provides an Archive pagetype behaving similar to a blog or news archive.
 *
 * @package frog
 * @subpackage plugin.archive
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @version 1.0
 * @since Frog version 0.9.0
 * @license http://www.gnu.org/licenses/agpl.html AGPL License
 * @copyright Philippe Archambault, 2008
 */

/**
 * The Archive class...
 */
class Archive
{
    public function __construct(&$page, $params)
    {
        $this->page =& $page;
        $this->params = $params;
        
        switch(count($params))
        {
            case 0: break;
            case 1:
                if (strlen((int) $params[0]) == 4)
                    $this->_archiveBy('year', $params);
                else
                    $this->_displayPage($params[0]);
            break;
            
            case 2:
                $this->_archiveBy('month', $params);
            break;
            
            case 3:
                $this->_archiveBy('day', $params);
            break;
            
            case 4:
                $this->_displayPage($params[3]);
            break;
            
            default:
                page_not_found();
        }
    }
    
    private function _archiveBy($interval, $params)
    {
        $this->interval = $interval;
        
        global $__FROG_CONN__;
        
        $page = $this->page->children(array(
            'where' => "behavior_id = 'archive_{$interval}_index'",
            'limit' => 1
        ), array(), true);
        
        if ($page)
        {
            $this->page = $page;
            $month = isset($params[1]) ? (int)$params[1]: 1;
            $day = isset($params[2]) ? (int)$params[2]: 1;

            $this->page->time = mktime(0, 0, 0, $month, $day, (int)$params[0]);
        }
        else
        {
            page_not_found();
        }
    }
    
    private function _displayPage($slug)
    {
        if ( ! $this->page = find_page_by_slug($slug, $this->page))
            page_not_found();
    }
    
    function get()
    {
        $date = join('-', $this->params);
        
        $pages = $this->page->parent->children(array(
            'where' => "page.created_on LIKE '{$date}%'",
            'order' => 'page.created_on DESC'
        ));
        return $pages;
    }
    
    function archivesByYear()
    {
        global $__FROG_CONN__;
        
        $out = array();

        $sql = "SELECT DISTINCT(DATE_FORMAT(created_on, '%Y')) FROM ".TABLE_PREFIX."page WHERE parent_id=? AND status_id != ".Page::STATUS_HIDDEN." ORDER BY created_on DESC";
        
        $stmt = $__FROG_CONN__->prepare($sql);
        $stmt->execute(array($this->page->id));
        
        while ($date = $stmt->fetchColumn())
            $out[] = $date;
        
        return $out;
    }
    
    function archivesByMonth($year='all')
    {
        global $__FROG_CONN__;
        
        $out = array();
        
        $sql = "SELECT DISTINCT(DATE_FORMAT(created_on, '%Y/%m')) FROM ".TABLE_PREFIX."page WHERE parent_id=? AND status_id != ".Page::STATUS_HIDDEN." ORDER BY created_on DESC";
        
        $stmt = $__FROG_CONN__->prepare($sql);
        $stmt->execute(array($this->page->id));
        
        while ($date = $stmt->fetchColumn())
            $out[] = $date; // str_replace('-', '/', substr($date, 0, 7));
        
        return $out;
    }
    
    function archivesByDay($year='all')
    {
        global $__FROG_CONN__;
        
        $out = array();
        
        if ($year == 'all') $year = '';
        
        $sql = "SELECT DISTINCT(DATE_FORMAT(created_on, '%Y/%m/%d')) FROM ".TABLE_PREFIX."page WHERE parent_id=? AND status_id != ".Page::STATUS_HIDDEN." ORDER BY created_on DESC";
        
        $stmt = $__FROG_CONN__->prepare($sql);
        $stmt->execute(array($this->page->id));
        
        while ($date = $stmt->fetchColumn())
            $out[] = $date;
        
        return $out;
    }
}

class PageArchive extends Page
{
    protected function setUrl()
    {
        $this->url = trim($this->parent->url . date('/Y/m/d/', strtotime($this->created_on)). $this->slug, '/');
    }
    
    public function title() { return isset($this->time) ? strftime($this->title, $this->time): $this->title; }
    
    public function breadcrumb() { return isset($this->time) ? strftime($this->breadcrumb, $this->time): $this->breadcrumb; }
}
