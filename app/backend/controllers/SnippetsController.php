<?php

/**
 * class SnippetsController
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class SnippetsController extends Controller
{

    function __construct()
    {
        $this->loadModel('snippets');
        $this->setLayout('backend');
    }


    function index()
    {
        $snippets = $this->snippets->find();

        $this->setView('snippets/index');
        $this->assignToView('snippets', $snippets);
        $this->render();
    } // index


    function add()
    {
        // check if trying to save
        if (count($_POST)) return $this->_add();
        
        // check if user have already enter something
        $snippet = flash_get('post_data');
        if (empty($snippet)) {
            $snippet = new stdClass;
            $snippet->name = '';
            $snippet->content = '';
            $snippet->filter_id = '';
        }

        $this->loadModel('filters');

        $this->setView('snippets/edit');
        $this->assignToView(array('action'  => 'add',
                                  'filters' => $this->filters->getAll(),
                                  'snippet' => $snippet));
        $this->render();
    } // add


    function _add()
    {
        $data = array_var($_POST, 'snippet');
        flash_set('post_data', (object)$data);
        
        // add more fields to save
        $data['created_on'] = $data['updated_on'] = date(DATE_MYSQL);
        $data['created_by_id'] = $data['updated_by_id'] = user_id();

        if ($this->snippets->save($data)) {
            $id = $this->snippets->insertId();
            flash_success(__('Snippet has been added!'));
        } else {
            flash_error(__('Snippet has not been added. Name must be unique!'));
            redirect_to(get_url('snippets', 'add'));
        }

        // save and quit or save and continue editing?
        if (isset($_POST['commit'])) {
            redirect_to(get_url('snippets'));
        } else {
            redirect_to(get_url('snippets', 'edit', $id));
        }
    } // doadd


    function edit($id=null)
    {
        if (is_null($id)) redirect_to(get_url('snippets/add'));
        
        $snippet = $this->snippets->findById($id);
        
        if (!$snippet) {
            flash_error(__('Snippet not found!'));
            redirect_to(get_url('snippets'));
        }
        
        // check if trying to save
        if (count($_POST)) return $this->_edit($id);

        $this->loadModel('filters');

        $this->setView('snippets/edit');
        $this->assignToView(array('action'  =>'edit',
                                  'filters' => $this->filters->getAll(),
                                  'snippet' => $snippet));
        $this->render();
    } // edit


    function _edit($id)
    {
        $data = array_var($_POST, 'snippet');
        
        $data['id'] = $id;

        // add more fields to save
        $data['updated_on'] = date(DATE_MYSQL);
        $data['updated_by_id'] = user_id();

        if ($this->snippets->save($data)) {
            flash_success(__('Snippet :name has been saved!', array(':name' => $data['name'])));
        } else {
            flash_error(__('Snippet :name has not been saved. Name must be unique!', array(':name' => $data['name'])));
            redirect_to(get_url('snippets/edit/'.$id));
        }

        // save and quit or save and continue editing?
        if (isset($_POST['commit'])) {
            redirect_to(get_url('snippets'));
        } else {
            redirect_to(get_url('snippets/edit/'.$id));
        }
    } // doedit


    function delete($id=null)
    {
        // if no id set redirect to index snippets
        if (is_null($id)) redirect_to(getUrl('snippets'));

        // find the user to delete
        if ($snippet = $this->snippets->findById($id)) {
            if ($this->snippets->deleteId($id)) {
                flash_success(__('Snippet :name has been deleted!', array(':name' => $snippet->name)));
            } else {
                flash_error(__('Snippet :name has not been deleted!', array(':name' => $snippet->name)));
            }
        } else {
            flash_error(__('Snippet not found!'));
        }

        redirect_to(get_url('snippets'));
    } // delete

} // SnippetsController
