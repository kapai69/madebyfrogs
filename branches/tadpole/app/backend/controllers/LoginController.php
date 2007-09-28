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
    function __construct()
    {
        AuthUser::load();
    }
    
    function index()
    {
        // already log in ?
        if (AuthUser::isLoggedIn()) {
            redirect(get_url());
        }

        // show it!
        echo $this->render('login/login');
    } // add
    
    function login()
    {
        // already log in ?
        if (AuthUser::isLoggedIn()) {
            redirect(get_url());
        }

        $data = isset($_POST['login']) ? $_POST['login']: array();
        
        if (AuthUser::login($data['username'], $data['password'], isset($data['remember']))) {
            $this->_checkVersion();
            // redirect to defaut controller and action
            redirect(get_url());

        } else {
            // login error
            Flash::set('error', __('Failed to log you in. Please check your login data and try again'));
        } // if
        
        // not find or password is wrong
        redirect(get_url('login'));
        
    } // login
    
    function logout()
    {
        AuthUser::logout();
        redirect(get_url());
    } // logout
    
    function forgot()
    {
        // show it!
        echo $this->render('login/forgot');
    } // forgot
    
    function _checkVersion()
    {
        $v = file_get_contents('http://www.madebyfrog.com/version/');
        if ($v > FROG_VERSION) {
            Flash::set('error', __('<b>Information!</b> New Frog version available (v. <b>:version</b>)! Visit <a href="http://www.madebyfrog.com/">http://www.madebyfrog.com/</a> to upgrade your version!',
                       array(':version' => $v )));
        }
    }
    
} // end LoginController class
