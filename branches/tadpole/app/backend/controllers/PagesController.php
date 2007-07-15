<?php

/**
 * class PagesController
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

define ('PAGE_STATUS_DRAFT', 1);
define ('PAGE_STATUS_REVIEWED', 50);
define ('PAGE_STATUS_PUBLISHED', 100);
define ('PAGE_STATUS_HIDDEN', 101);

class PagesController extends Controller
{
    function __construct()
    {
        $this->loadModel('pages');
    }

    function index()
    {
        $this->setLayout('backend');

        // get all pages
        $root = $this->pages->findById(1);

        $content_children = $this->children(1, 0, true);
        
        $this->setView('pages/index');
        $this->assignToView(array(
            'root' => $root,
            'content_children' => $content_children
        ));
        $this->render();
    } // index

    function add()
    {
        // check if trying to save
        if (count($_POST)) return $this->_add();
        
        // include some javascript for helping fancy display
        include_javascript('pages');

        $page = flash_get('post_data');
        // generate all default value of a empty page
        if (empty($page)) {
            $page = new stdClass;
            $page->parent_id = get_id();
            $page->title = '';
            $page->slug = '';
            $page->breadcrumb = '';
            $page->status_id = 1;
            $page->layout_id = '';
            $page->behavior_id = '';
        }
        
        $page_parts = flash_get('post_parts_data');
        if (empty($page_parts)) {
            $page_parts = array($this->_getEmptyPart());
        }
        
        $this->loadModel('layouts');
        $layouts = $this->layouts->findAll();

        $this->loadModel('filters');
        $this->loadModel('behaviors');

        // display things ...
        $this->setLayout('backend');
        $this->setView('pages/edit');
        $this->assignToView(array('action'     => 'add',
                                  'page'       => $page,
                                  'filters'    => $this->filters->getAll(),
                                  'behaviors'  => $this->behaviors->getAll(),
                                  'page_parts' => $page_parts,
                                  'layouts'    => $layouts));
        $this->render();
    } // add

    function _add()
    {
        $data = array_var($_POST, 'page');
        flash_set('post_data', (object)$data);
        
        // trying to access directly, redirect to index
        // if (is_null($data)) redirect_to(get_url('pages'));

        // set creator field
        $data['created_on'] = $data['updated_on'] = date(DATE_MYSQL);
        $data['created_by_id'] = $data['updated_by_id'] = user_id();

        // save page data
        if ($this->pages->save($data)) {
            // get the inserted id for this new page
            $page_id = $this->pages->insertId();

            // make a new instance of the model page_parts
            $this->loadModel('page_parts');

            // get data from user
            $data_parts = array_var($_POST, 'part');
            flash_set('post_parts_data', (object)$data_parts);
            
            foreach ($data_parts as $data) {
                $data['page_id'] = $page_id;
                $this->page_parts->save($data);
            } // foreach

            // need to rebuild the tree to assumed a balanced tree
            $this->pages->rebuild();

            flash_success('Page has been saved!');
        } else {
            flash_error('Page has not been saved!');
            redirect_to(get_url('pages', 'add'));
        }
        // save and quit or save and continue editing ?
        if (isset($_POST['commit'])) {
            redirect_to(get_url('pages'));
        } else {
            redirect_to(get_url('pages', 'edit', $page_id));
        }
    } // doadd

    function addPart()
    {
        $data = array_var($_POST, 'part');
        print $this->_getPartView($data['index'], $data['name']);
    } // addpart

    function edit($id=null)
    {
        if (is_null($id)) redirect_to(get_url('snippets', 'add'));
        
        include_javascript('pages');

        $page = $this->pages->findById($id);

        if (!$page) {
            flash_error('Page not found!');
            redirect_to(get_url('pages'));
        }
        
        // check if trying to save
        if (count($_POST)) return $this->_edit($id);
        
        // find all page_part of this pages
        $this->loadModel('page_parts');
        $page_parts = $this->page_parts->findByPageId($id);

        if (empty($page_parts)) $page_parts = array($this->_getEmptyPart());

        // get all layouts (for dropdown)
        $this->loadModel('layouts');
        $layouts = $this->layouts->findAll();

        $this->loadModel('filters');
        $this->loadModel('behaviors');

        $this->setLayout('backend');
        $this->setView('pages/edit');
        $this->assignToView(array('action'     => 'edit',
                                  'page'       => $page,
                                  'layouts'    => $layouts,
                                  'filters'    => $this->filters->getAll(),
                                  'behaviors'  => $this->behaviors->getAll(),
                                  'page_parts' => $page_parts));
        $this->render();
    } // edit

    function _edit($id)
    {
        $data = array_var($_POST, 'page');
        
        // trying to access directly, redirect to index
        // if (is_null($data)) redirect_to(get_url('pages'));
        
        //$id = get_id();
        $data['id'] = $id;

        // add some infos to save...
        $data['updated_on'] = date(DATE_MYSQL);
        $data['updated_by_id'] = user_id();

        if ($this->pages->save($data)) {
            // get data for parts of this page
            $data_parts = array_var($_POST, 'part', array('name'=> 'body', 'content' => 'nothing here'));

            // load page_parts model!
            $this->loadModel('page_parts');

            $old_parts = $this->page_parts->findByPageId($id);

            // check if all old page part are passed in POST
            // if not ... we need to delete it!
            foreach ($old_parts as $old_part) {
                $not_in = true;
                foreach ($data_parts as $data) {
                    if ($old_part->name == $data['name']) {
                        $not_in = false;
                        break;
                    } // if
                } // foreach
                if ($not_in) {
                    $this->page_parts->deleteId($old_part->id);
                } // if
            }

            // save each parts
            foreach ($data_parts as $data) {
                $data['page_id'] = $id;
                $this->page_parts->save($data);
            }
            flash_success('Page has been saved!');
        } else {
            flash_error('Page has not been saved!');
            redirect_to(get_url('pages', 'edit', $id));
        }
        // save and quit or save and continue editing ?
        if (isset($_POST['commit'])) {
            redirect_to(get_url('pages'));
        } else {
            redirect_to(get_url('pages', 'edit', $id));
        } // if
    } // doedit

    function delete()
    {
        $id = get_id();

        // security (dont delete the root page)
        if ($id > 1) {
            // if no id set redirect to index users
            if (is_null($id)) redirect_to(get_url('pages'));

            // find the user to delete
            if ($page = $this->pages->findById($id)) {
                // load page_parts model!
                $this->loadModel('page_parts');

                // need to delete all page_parts too !!
                $this->page_parts->deleteByPageId($id);

                if ($this->pages->deleteId($id)) {
                    flash_success('Page '.$page->title.' as been deleted!');
                } else {
                    flash_error('Page '.$page->title.' as not been deleted!');
                } // if
            } else {
                flash_error('Page not found!');
            } // if
        } else {
            flash_error('Action disabled!');
        } // if
        redirect_to(get_url('pages'));
    } // delete
    
    function children($parent_id, $level, $return=false)
    {
        $expanded_rows = isset($_COOKIE['expanded_rows']) ? explode(',', $_COOKIE['expanded_rows']): array();
        
        // get all children of the page (parent_id)
        $children = $this->pages->childrenOf($parent_id);

        foreach ($children as $index => $child) {
            $children[$index]->has_children = $this->pages->hasChildren($child->id);
            $children[$index]->is_expanded = in_array($child->id, $expanded_rows);
            if ($children[$index]->is_expanded) {
                $children[$index]->children_rows = $this->children($child->id, $level+1, true);
            }
        }

        $this->setView('pages/children');
        $this->assignToView(array(
            'children' => $children,
            'level'    => $level+1,
        ));
        
        if ($return)
            return $this->fetchView();
            
        $this->displayView();
    }
    
    //
    // private methods
    //

    function _getPartView($index=1, $name='', $filter_id='', $content='')
    {
        $this->loadModel('filters');

        $this->setView('pages/part_edit');
        $this->assignToView(array('index'     => $index,
                                  'name'      => $name,
                                  'filters'   => $this->filters->getAll(),
                                  'filter_id' => $filter_id,
                                  'content'   => $content));
        return $this->fetchView();
    } // _getPartView

    function _getEmptyPart()
    {
        $empty_part = new stdClass;
        $empty_part->name = 'body';
        $empty_part->filter_id = '';
        $empty_part->content = '';
        return $empty_part;
    } // _getEmptyPart
    
} // PagesController
