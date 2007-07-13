<?php 

/**
 * class UsersController
 *
 * Enter description here...
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since    0.1
 */

class UsersController extends Controller
{

    function __construct()
    {
        if ( ! user_is_admin()) {
            flash_error(_('You don\'t have permissions to access requested page'));
            redirect_to(get_url());
        }
        
        $this->loadModel('users');
    } // __construct
    
    function index()
    {
        $users = $this->users->find();
        
        // display things...
        $this->setLayout('backend');
        $this->setView('users/index');
        $this->assignToView('users', $users);
        $this->render();
    } // index
    
    function add()
    {
        // check if user have already enter something
        $user = flash_get('post_data');
        
        if (empty($user)) {
            $user = new stdClass;
            $user->name = '';
            $user->email = '';
            $user->username = '';
        }
        
        $this->setLayout('backend');
        $this->setView('users/edit');
        $this->assignToView(array('action' => 'add', 'user' => $user));
        // show it!
        $this->render();
    } // add
    
    function doadd()
    {
        $data = array_var($_POST, 'user');
        
        // trying to access directly, redirect to index
        if (is_null($data)) redirect_to(get_url('users'));
        flash_set('post_data', (object)$data);
        
        // check if pass and confirm are egal and >= 5 chars
        if (strlen($data['password']) >= 5 && $data['password'] == $data['confirm']) {
            $data['password'] = sha1($data['password']);
        } else {
            flash_error(_('Password and Confirm are not the same or too small!'));
            redirect_to(get_url('users', 'add'));
        }
        
        // check if username >= 3 chars
        if (strlen($data['username']) < 3) {
            flash_error(_('Username must be 3 character minimum!'));
            redirect_to(get_url('users', 'add'));
        }
        
        unset($data['confirm']);
        
        // set creator field
        $data['created_on'] = date(DATE_MYSQL);
        $data['created_by_id'] = user_id();
        
        if ($this->users->save($data)) {
            $id = $this->users->insertId();
            flash_success(_('User has been added!'));
        } else {
            flash_error(_('User has not been added!'));
        }
        
        redirect_to(get_url('users'));
    } // doadd
    
    function edit()
    {
        $id = get_id();
        
        if ($user = $this->users->findById($id)) {
            
            $this->setLayout('backend');
            $this->setView('users/edit');
            $this->assignToView(array('action' => 'edit',
                                                                'user' => $user));
            // show it!
            $this->render();
        } else {
            flash_error(_('User not found!'));
        } // if
    } // edit
    
    function doedit()
    {
        $data = array_var($_POST, 'user');
        
        // trying to access directly, redirect to index
        if (is_null($data)) redirect_to(get_url('users'));
        
        $id = get_id();
        $data['id'] = $id;
        
        // check if user want to change the password
        if (strlen($data['password']) > 0) {
            // check if pass and confirm are egal and >= 5 chars
            if (strlen($data['password']) >= 5 && $data['password'] == $data['confirm']) {
                $data['password'] = sha1($data['password']);
                unset($data['confirm']);
            } else {
                flash_error(_('Password and Confirm are not the same or too small!'));
                redirect_to(get_url('users', 'add'));
            }
        } else {
            unset($data['password']);
        }
        unset($data['confirm']);
        
        // check if username >= 3 chars
        if (strlen($data['username']) < 3) {
            unset($data['username']);
            flash_error(_('Username have not been save must be 3 character minimum!'));
            //redirect_to(get_url('users', 'edit', $id));
        }
        
        // add some infos to save...
        $data['updated_on'] = date(DATE_MYSQL);
        $data['updated_by_id'] = user_id();
        
        if ($this->users->save($data)) {
            flash_success(_('User has been saved!'));
        } else {
            flash_error(_('User has not been saved!'));
        }
        
        redirect_to(get_url('users'));
    } // doedit
    
    function delete()
    {
        $id = get_id();
        
        // security (dont delete the first admin)
        if ($id > 1) {
            // if no id set redirect to index
            if (is_null($id)) redirect_to(get_url('users'));
            
            // find the user to delete
            if ($user = $this->users->findById($id)) {
                if ($this->users->deleteId($id)) {
                    flash_success('User <strong>'.$user->name.'</strong> has been deleted!');
                } else {
                    flash_error('User <strong>'.$user->name.'</strong> has not been deleted!');
                }
            } else {
                    flash_error('User not found!');
            }
        } else {
            flash_error('Action disabled!');
        }
        
        redirect_to(get_url('users'));
    } // delete

} // UsersController
