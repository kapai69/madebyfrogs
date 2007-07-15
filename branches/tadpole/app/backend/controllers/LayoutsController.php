<?php

/**
 * class LayoutsController
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class LayoutsController extends Controller
{
    
    function __construct()
    {
        $this->loadModel('layouts');
    }


    function index()
    {
        $layouts = $this->layouts->find();

        // display things...
        $this->setLayout('backend');
        $this->setView('layouts/index');
        $this->assignToView('layouts', $layouts);
        $this->render();
    } // index


    function add()
    {
        // check if trying to save
        if (count($_POST)) return $this->_add();
        
        // check if user have already enter something
        $layout = flash_get('post_data');
        if (empty($layout)) {
            $layout = new stdClass;
            $layout->name = '';
            $layout->content_type = '';
            $layout->content = '';
        }

        // display things...
        $this->setLayout('backend');
        $this->setView('layouts/edit');
        $this->assignToView('action', 'add');
        $this->assignToView('layout', $layout);
        // run it!
        $this->render();
    } // add


    function _add()
    {
        $data = array_var($_POST, 'layout');
        flash_set('post_data', (object)$data);

        // add more fields to save
        $data['created_on'] = $data['updated_on'] = date(DATE_MYSQL);
        $data['created_by_id'] = $data['updated_by_id'] = user_id();

        if ($this->layouts->save($data)) {
            $id = $this->layouts->insertId();
            flash_success('Layout has been added!');
        } else {
            flash_error('Layout has not been added. Name must be unique!');
            redirect_to(get_url('layouts', 'add'));
        } //if

        // save and quit or save and continue editing?
        if (isset($_POST['commit'])) {
            redirect_to(get_url('layouts'));
        } else {
            redirect_to(get_url('layouts', 'edit', $id));
        } // if
    } // doadd


    function edit($id=null)
    {
        if (is_null($id)) redirect_to(get_url('layouts', 'add'));
    
        $layout = $this->layouts->findById($id);

        if (!$layout) {
            flash_error('Layout not found!');
            redirect_to(get_url('layouts'));
        }
        
        // check if trying to save
        if (count($_POST)) return $this->_edit($id);
        
        // display things...
        $this->setLayout('backend');
        $this->setView('layouts/edit');
        $this->assignToView('action', 'edit');
        $this->assignToView('layout', $layout);
        $this->render();
    } // edit


    function _edit($id)
    {
        $data = array_var($_POST, 'layout');
        
        $data['id'] = $id;

        // add more fields to save
        $data['updated_on'] = date(DATE_MYSQL);
        $data['updated_by_id'] = user_id();

        if ($this->layouts->save($data)) {
            flash_success('Layout has been saved!');
        } else {
            flash_error('Layout has not been saved. Name must be unique!');
            redirect_to(get_url('layouts', 'edit', $id));
        } // if

        // save and quit or save and continue editing?
        if (isset($_POST['commit'])) {
            redirect_to(get_url('layouts'));
        } else {
            redirect_to(get_url('layouts', 'edit', $id));
        } // if
    } // doedit


    function delete($id)
    {
        // if no id set redirect to index layouts
        if (is_null($id)) redirect_to(getUrl('layouts'));

        // find the user to delete
        if ($layout = $this->layouts->findById($id)) {
            if ($this->layouts->deleteId($id)) {
                flash_success('Layout '.$layout->name.' has been deleted!');
            } else {
                flash_error('Layout '.$layout->name.' has not been deleted!');
            } // if
        } else {
            flash_error('Layout not found!');
        } // if

        redirect_to(get_url('layouts'));
    } // delete

} // LayoutsController
