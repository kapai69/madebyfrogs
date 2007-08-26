<?php

/**
 * class Page
 *
 * apply methodes for page, layout and snippet of a page
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @link http://www.philworks.com/frog/documentation/
 * @since  0.1
 *
 * -- TAGS --
 * id()
 * title()
 * breadcrumb()
 * author()
 * slug()
 * url()
 *
 * link([label], [class])
 * date([format])
 *
 * content([part_name], [inherit])
 * breadcrumbs([between])
 *
 * children([arguments :limit :offset :order :by])
 * find(url)
 **/

class Page
{

    function Page($object)
    {
        foreach ($object as $key => $value) {
            $this->$key = $value;
        }
    } // Page

    function id() { echo $this->id; }
    function title() { echo $this->title; }
    function breadcrumb() { echo $this->breadcrumb; }
    function author() { echo $this->created_by_name; }
    function slug() { echo $this->slug; }
    function url() { echo BASE_URL . $this->url; }

    function link($label=null, $class=null)
    {
        if (is_null($label)) {
            $label = $this->title;
        }
        
        if ( ! is_null($class)) {
            $class = ' class="'.$class.'"';
        }
        
        printf('<a%s href="%s" title="%s">%s</a>',
               $class,
               BASE_URL . $this->url,
               $this->title,
               $label);
    } // link

    /**
     * http://php.net/date
     * exemple (can be useful):
     *  'D, j M Y'      -> Wed, 20 Dec 2006 <- (default)
     *  'l, j F Y'      -> Wednesday, 20 December 2006
     *  'F j, Y, g:i a' -> December 20, 2006, 8:30 pm
     */
    function date($format='D, j M Y')
    {
        echo date($format, strtotime($this->created_on));
    } // date

    function breadcrumbs($separator='&gt;')
    {
        $url = BASE_URL;
        $out = '<ul class="breadcrumb">';
        foreach ($this->paths as $path) {
            $url .= ($path['slug']=='/') ? $path['slug'] : $path['slug'].'/';
            $out .= '<li><a href="'.$url.'" title="'.$path['breadcrumb'].'">'.$path['breadcrumb'].'</a></li><span class="separator">'.$separator.'</span>';
        } // foreach

        $out .= '<li>'.$this->breadcrumb.'</li></ul>'."\n";
        echo $out;
    } // breadcrumbs

    function content($part='body', $inherit=false)
    {
        // if part exist we generate the content en execute it!
        if (isset($this->part->$part)) {
            $part = $this->part->$part;

            // apply filter if one is set
            if (trim($part->filter_id) != '') {
                global $filters;
                $filter = $filters->get($part->filter_id);
                $part->content = $filter->apply($part->content);
            } //if
        
            eval('?>'.$part->content);
        } else if ($inherit && isset($this->parent)) {
            $this->parent->content($part, true);
        } // if
    } // content

    function children($arguments=null)
    {
        global $_PDO;
        
        // Collect attributes...
        $where   = array_var($arguments, 'where', '');
        $order   = array_var($arguments, 'order', 'position, id');
        $offset  = (int) array_var($arguments, 'offset', 0);
        $limit   = (int) array_var($arguments, 'limit', 0);

        // Prepare query parts
        $whereString = trim($where) == '' ? '' : "and ".$where;
        $limitString = $limit > 0 ? "limit $offset, $limit" : '';

        // Prepare SQL
        $sql = 'select pages.*, creator.name as created_by_name, updator.name as updated_by_name '
             . 'from '.TABLE_PREFIX.'pages as pages '
             . 'left join '.TABLE_PREFIX.'users as creator on creator.id = pages.created_by_id '
             . 'left join '.TABLE_PREFIX.'users as updator on updator.id = pages.updated_by_id '
             . 'where parent_id = '.$this->id.' and (status_id=50 or status_id=100 or status_id=101) '
             . "$whereString order by $order $limitString";


        $pages = array();

        // Run!
        if ($stmt = $_PDO->prepare($sql)) {
            $stmt->execute();
            while ($object = $stmt->fetchObject()) {
                $object->url = $this->url . '/' . $object->slug;
                $page = new Page($object);
                // assignParts
                $page->part = assignParts($page->id);
                $pages[] = $page;
            } // while
        } // if

        return $pages;
    } // children

    function find($uri, $includeParents=false)
    {
        // return all pages finded
        return findPageByUrls($uri, $includeParents);
    } // find

    function includeSnippet($name)
    {
        global $_PDO;
        
        $sql = 'select * from '.TABLE_PREFIX.'snippets where name like :name';
        $stmt = $_PDO->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        
        if ($snippet = $stmt->fetchObject()) {
            // apply filter if one is set
            if (trim($snippet->filter_id) != '') {
                global $filters;
                $filter = $filters->get($snippet->filter_id);
                $snippet->content = $filter->apply($snippet->content);
            } //if
            // run it!!
            eval('?>'.$snippet->content); /*eval('?>'.$snippet->content.'<?');*/
        } // if
    } // includeSnippet

    /**
     * EXTRAS
     */

    function executionTime()
    {
        Benchmark::getInstance()->display();
    } // executionTime

    /**
     * PRIVATES
     */

    function _executeLayout()
    {
        global $_PDO;
        
        $sql = 'select * from '.TABLE_PREFIX.'layouts where id = :id';
        $stmt = $_PDO->prepare($sql);
        $stmt->bindParam(':id', $this->_getLayoutId());
        $stmt->execute();
        
        if ($layout = $stmt->fetchObject()) {

            // if content-type not set, we set html as default
            if ($layout->content_type == '') {
                $layout->content_type = 'text/html';
            }

            // set content-type and charset of the page
            header('Content-Type: '.$layout->content_type.'; charset='.DEFAULT_CHARSET);

            // execute the layout code
            eval('?>'.$layout->content.'<?');
        } // if
    } // _executeLayout

    /**
     * find the layoutId of the page where the layout is set
     */
    function _getLayoutId()
    {
        if ($this->layout_id) {
            return $this->layout_id;
        } else if (!empty($this->parent)) {
            return $this->parent->_getLayoutId();
        } else {
            exit ('You need to set a layout!');
        }
    } // _getLayoutId

} // Page
