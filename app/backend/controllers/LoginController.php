<?php 

/**
 * class LoginController
 *
 * Log a use in and out and send a mail with something on 
 * if the user doesn't remember is password !!!
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.1
 */

class LoginController extends Controller
{

    function index()
    {
        // already log in ?
        if (!is_null(user_id())) {
            redirect_to(get_url());
        }
        
        // set the login view
        $this->setView('login/index');
        // show it!
        $this->render();
    } // add
    
    function login()
    {
        // already log in ?
        if (!is_null(user_id())) {
            redirect_to(get_url());
        }
        
        $this->loadModel('users');
        
        $data = array_var($_POST, 'login');
        
        if ($user = $this->users->findByUsername($data['username'])) {
            
            if ($user->password == sha1($data['password'])) {
                // adding small security
                session_regenerate_id();
                
                // save all info to session vars
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_is_admin'] = $user->is_admin;
                
                // need to be remembered for 14 days ?
                if (isset($data['remember'])) {
                    setcookie(session_name(), session_id(), time()+REMEMBER_LOGIN_LIFETIME, COOKIE_PATH, COOKIE_DOMAIN, COOKIE_SECURE);
                } // if
                
                // redirect to defaut controller and action
                redirect_to(get_url());
            } else {
                // password error (but user don't have to know every think !!)
                flash_error(_('Failed to log you in. Please check your login data and try again'));
            } // if
        } else {
            // login error
            flash_error(_('Failed to log you in. Please check your login data and try again'));
        } // if
        
        // not find or password is wrong
        redirect_to(get_url('login'));
        
    } // login
    
    function logout()
    {
        session_destroy();
        redirect_to(get_url());
    } // logout
    
    function forgot()
    {
        // set the login view
        $this->setView('login/forgot');
        // show it!
        $this->render();
    } // forgot
    
} // LoginController
