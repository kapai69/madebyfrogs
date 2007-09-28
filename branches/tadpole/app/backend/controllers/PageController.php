<?php

/**
 * class PagesController
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class PageController extends Controller
{
    public function __construct()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn()) {
            redirect(get_url('login'));
        }
    }

    public function index()
    {
        $this->setLayout('backend');
        
        $this->display('page/index', array(
            'root' => Record::findByIdFrom('Page', 1),
            'content_children' => $this->children(1, 0, true)
        ));
        
    } // index

    public function add($parent_id=1)
    {
        // check if trying to save
        if (get_request_method() == 'POST') {
            return $this->_add();
        }
        
        if ($page = Flash::get('post_data') == null) {
            $page = new Page;
            $page->parent_id  = $parent_id;
        }
        
        $page_parts = Flash::get('post_parts_data');
        if (empty($page_parts)) {
            $page_parts = array(new PagePart);
        }

        // display things ...
        $this->setLayout('backend');
        echo $this->render('page/edit', array(
            'action'     => 'add',
            'page'       => $page,
            'filters'    => Filter::findAll(),
            'behaviors'  => Behavior::findAll(),
            'page_parts' => $page_parts,
            'layouts'    => Record::findAllFrom('Layout'))
        );
    } // add

    private function _add()
    {
        $data = $_POST['page'];
        Flash::set('post_data', (object) $data);

        $page = new Page($data);
        
        // save page data
        if ($page->save()) {

            // get data from user
            $data_parts = $_POST['part'];
            Flash::set('post_parts_data', (object) $data_parts);
            
            foreach ($data_parts as $data) {
                $data['page_id'] = $page->id;
                $page_part = new PagePart($data);
                $page_part->save();
            }

            Flash::set('success', __('Page has been saved!'));
        } else {
            Flash::set('error', __('Page has not been saved!'));
            redirect(get_url('page/add'));
        }
        
        // save and quit or save and continue editing ?
        if (isset($_POST['commit'])) {
            redirect(get_url('page'));
        } else {
            redirect(get_url('page/edit/'.$page->id));
        }
    
    } // _add

    public function addPart()
    {
        $data = $_POST['part'];
        print $this->_getPartView($data['index'], $data['name']);
    } // addpart

    public function edit($id=null)
    {
        if (is_null($id)) redirect(get_url('page'));

        $page = Page::findById($id);

        if (!$page) {
            Flash::set('error', __('Page not found!'));
            redirect(get_url('page'));
        }
        
        // check if trying to save
        if (get_request_method() == 'POST') {
            return $this->_edit($id);
        }
        
        // find all page_part of this pages
        $page_parts = PagePart::findByPageId($id);

        if (empty($page_parts)) $page_parts = array(new PagePart);

        // display things ...
        $this->setLayout('backend');
        echo $this->render('page/edit', array(
            'action'     => 'edit',
            'page'       => $page,
            'filters'    => Filter::findAll(),
            'behaviors'  => Behavior::findAll(),
            'page_parts' => $page_parts,
            'layouts'    => Record::findAllFrom('Layout'))
        );
    } // edit

    private function _edit($id)
    {
        $data = $_POST['page'];

        $page = Record::findByIdFrom('Page', $id);
        
        $page->setFromData($data);

        if ($page->save()) {
            // get data for parts of this page
            $data_parts = $_POST['part'];

            $old_parts = PagePart::findByPageId($id);

            // check if all old page part are passed in POST
            // if not ... we need to delete it!
            foreach ($old_parts as $old_part) {
                $not_in = true;
                foreach ($data_parts as $part_id => $data) {
                    if ($old_part->name == $data['name']) {
                        $not_in = false;
                        
                        $part = new PagePart($data);
                        $part->page_id = $id;
                        $part->save();

                        unset($data_parts[$part_id]);
                        
                        break;
                    }
                }
                
                if ($not_in) {
                    $old_part->delete();
                }
            }
            
            // add the new ones
            foreach ($data_parts as $part_id => $data) {

                $part = new PagePart($data);
                $part->page_id = $id;
                $part->save();

            }

            Flash::set('success', __('Page has been saved!'));
        } else {
            Flash::set('error', __('Page has not been saved!'));
            redirect(get_url('page/edit/'.$id));
        }
        // save and quit or save and continue editing ?
        if (isset($_POST['commit'])) {
            redirect(get_url('page'));
        } else {
            redirect(get_url('page/edit/'.$id));
        }
    } // doedit

    public function delete($id)
    {
        // security (dont delete the root page)
        if ($id > 1) {

            // find the user to delete
            if ($page = Record::findByIdFrom('Page', $id)) {

                // need to delete all page_parts too !!
                PagePart::deleteByPageId($id);

                if ($page->delete()) {
                    Flash::set('success', __('Page :title as been deleted!', array(':title'=>$page->title)));
                } else {
                    Flash::set('error', __('Page :title as not been deleted!', array(':title'=>$page->title)));
                }
            } else {
                Flash::set('error', __('Page not found!'));
            }
        } else {
            Flash::set('error', __('Action disabled!'));
        }
        redirect(get_url('page'));
    } // delete
    
    function children($parent_id, $level, $return=false)
    {
        $expanded_rows = isset($_COOKIE['expanded_rows']) ? explode(',', $_COOKIE['expanded_rows']): array();
        
        // get all children of the page (parent_id)
        $childrens = Page::childrenOf($parent_id);

        foreach ($childrens as $index => $child) {
            $childrens[$index]->has_children = Page::hasChildren($child->id);
            $childrens[$index]->is_expanded = in_array($child->id, $expanded_rows);
            if ($childrens[$index]->is_expanded) {
                $childrens[$index]->children_rows = $this->children($child->id, $level+1, true);
            }
        }

        $content = new View(
            APP_PATH.'/views/page/children.php',
            array(
                'childrens' => $childrens,
                'level'    => $level+1,
            )
        );
        
        if ($return) {
            return $content;
        }
        
        echo $content;
    }
    
    /**
     * Ajax action to reorder (page->position) a page 
     *
     * all the child of the new page->parent_id have to be updated
     * and all nested tree has to be rebuild
     */
    function reorder($parent_id)
    {
        parse_str($_POST['data']);

        $new_brother = false;

        foreach ($pages as $position => $page_id) {
            
            $page = Record::findByIdFrom('Page', $page_id);
            $page->position = (int) $position;
            $page->parent_id = (int) $parent_id;
            $page->save();
            
        }
    }
    
    //
    // private methods
    //

    function _getPartView($index=1, $name='', $filter_id='', $content='')
    {
        return $this->render('page/part_edit', array(
                    'index'     => $index,
                    'name'      => $name,
                    'filters'   => Filter::findAll(),
                    'filter_id' => $filter_id,
                    'content'   => $content
                ));
    } // _getPartView
    
} // end PageController class
