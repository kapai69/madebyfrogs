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
 * hasContent(part_name)
 * content([part_name], [inherit])
 * breadcrumbs([between])
 *
 * children([arguments :limit :offset :order])
 * find(url)
 **/

class Page
{
    const STATUS_DRAFT = 1;
    const STATUS_REVIEWED = 50;
    const STATUS_PUBLISHED = 100;
    const STATUS_HIDDEN = 101;
    
    public $id;
    public $title;
    public $breadcrumb;
    public $author;
    public $author_id;
    public $updator;
    public $updator_id;
    public $slug;
    public $url;
    
    function __construct($object)
    {
        foreach ($object as $key => $value) {
            $this->$key = $value;
        }
    } // Page

    function id() { echo $this->id; }
    function title() { echo $this->title; }
    function breadcrumb() { echo $this->breadcrumb; }
    function author() { echo $this->author; }
    function authorId() { echo $this->author_id; }
    function updator() { echo $this->updator; }
    function updatorId() { echo $this->updator_id; }
    function slug() { echo $this->slug; }
    function url() { echo BASE_URL . $this->slug; }

    function link($label=null, $options='')
    {
        if ($label == null) {
            $label = $this->title;
        }
        
        printf('<a%s href="%s" title="%s">%s</a>',
               $options,
               BASE_URL.$this->slug,
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
        $path = '';
        $paths = explode('/', '/'.$this->slug);
        $nb_path = count($paths);
        
        echo '<div class="breadcrumb">';
        for ($i = 0; $i < $nb_path-1; $i++) {
            $path .= $paths[$i];
            $page = find_page_by_slug($path);
            echo '<a href="'.BASE_URL.$page->slug.'" title="'.$page->breadcrumb.'">'.$page->breadcrumb.'</a> <span class="breadcrumb-separator">'.$separator.'</span> '."\n";
        }

        echo '<span class="breadcrumb-current">'.$this->breadcrumb.'</span></div>'."\n";
        
    } // breadcrumbs

    function hasContent($part)
    {
        return isset($this->part->$part);
    }

    function content($part='body', $inherit=false)
    {
        // if part exist we generate the content en execute it!
        if (isset($this->part->$part)) {
            eval('?>'.$this->part->$part->content_html);
        } else if ($inherit && ! empty($this->parent_id)) {
            $this->parent()->content($part, true);
        }
    } // content

    function children($args=null)
    {
        global $__FROG_CONN__, $pages_loaded;
        
        // Collect attributes...
        $where   = isset($args['where']) ? $args['where']: '';
        $order   = isset($args['order']) ? $args['order']: 'position, id';
        $offset  = isset($args['offset']) ? $args['offset']: 0;
        $limit   = isset($args['limit']) ? $args['limit']: 0;

        // Prepare query parts
        $where_string = trim($where) == '' ? '' : "AND ".$where;
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';

        // Prepare SQL
        $sql = 'SELECT page.*, author.name AS author, author.id AS author_id, updator.name AS updator, updator.id AS updator_id '
             . 'FROM '.TABLE_PREFIX.'page AS page '
             . 'LEFT JOIN '.TABLE_PREFIX.'user AS author ON author.id = page.created_by_id '
             . 'LEFT JOIN '.TABLE_PREFIX.'user AS updator ON updator.id = page.updated_by_id '
             . 'WHERE parent_id = '.$this->id.' AND (status_id='.Page::STATUS_REVIEWED.' OR status_id='.Page::STATUS_PUBLISHED.') '
             . "$where_string ORDER BY $order $limit_string";

        $pages = array();

        // Run!
        if ($stmt = $__FROG_CONN__->prepare($sql)) {
            $stmt->execute();
            while ($object = $stmt->fetchObject()) {
                $page = new Page($object);
                // assignParts
                $page->part = get_parts($page->id);
                $pages[] = $page;
                // little cache in case he need it again by a parent method or something
                $pages_loaded[$page->slug] = $page;
            }
        }

        if ($limit == 1) {
            return $pages[0];
        }
        
        return $pages;
    } // children

    function find($slug)
    {
        // return all pages finded
        return find_page_by_slug($slug);
    }

    function parent()
    {
        $slug = ''; // slug of the home page
        
        if (($pos = strrpos($this->slug, '/')) !== false) {
            $slug = substr($this->slug, 0, $pos);
        }
        
        return find_page_by_slug($slug);
    }

    function includeSnippet($name)
    {
        global $__FROG_CONN__;
        
        $sql = 'SELECT content_html FROM '.TABLE_PREFIX.'snippet WHERE name LIKE ?';
        
        $stmt = $__FROG_CONN__->prepare($sql);
        $stmt->execute(array($name));
        
        if ($snippet = $stmt->fetchObject()) {
            eval('?>'.$snippet->content_html);
        }
    } // includeSnippet

    function executionTime()
    {
        Benchmark::getInstance()->display();
    }

    /**
     * PRIVATES
     */

    function _executeLayout()
    {
        global $__FROG_CONN__;
        
        $sql = 'SELECT content_type, content FROM '.TABLE_PREFIX.'layout WHERE id = ?';
        
        $stmt = $__FROG_CONN__->prepare($sql);
        $stmt->execute(array($this->_getLayoutId()));
        
        if ($layout = $stmt->fetchObject()) {

            // if content-type not set, we set html as default
            if ($layout->content_type == '') {
                $layout->content_type = 'text/html';
            }

            // set content-type and charset of the page
            header('Content-Type: '.$layout->content_type.'; charset=UTF-8');

            // execute the layout code
            eval('?>'.$layout->content);
        }
    } // _executeLayout

    /**
     * find the layoutId of the page where the layout is set
     */
    function _getLayoutId()
    {
        if ($this->layout_id) {
            return $this->layout_id;
        } else if (($parent = $this->parent()) !== null) {
            return $parent->_getLayoutId();
        } else {
            exit ('You need to set a layout!');
        }
    } // _getLayoutId

} // Page
