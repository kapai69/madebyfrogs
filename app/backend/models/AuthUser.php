<?php

class AuthUser
{
    const SESSION_KEY               = 'frog.auth.user';
    const COOKIE_KEY                = 'frog.auth.user';
    const ALLOW_LOGIN_WITH_EMAIL    = false;
    const COOKIE_LIFE               = 604800; // 1 week (7 days)
    const DELAY_ON_INVALID_LOGIN    = true;
    
    static protected $is_logged_in  = false;
    static protected $user_id       = false;
    static protected $is_admin      = false;
    static protected $record        = false;
    static protected $permissions   = array();
      
    static public function load()
    {
        if (isset($_SESSION[self::SESSION_KEY]) && isset($_SESSION[self::SESSION_KEY]['username'])) {
            $user = User::findBy('username', $_SESSION[self::SESSION_KEY]['username']);
        } else if (isset($_COOKIE[self::COOKIE_KEY])) {
            $user = self::challengeCookie($_COOKIE[self::COOKIE_KEY]);
        } else {
            return false;
        }
        
        if ( ! $user) {
            self::logout();
            return;
        }
        
        self::setInfos($user);
    }
    
    static public function setInfos(Record $user)
    {
        $_SESSION[self::SESSION_KEY] = (array) $user;
        unset($_SESSION[self::SESSION_KEY]['password']);
        
        self::$record = $user;
        self::$is_logged_in = true;
        self::$permissions = $user->getPermissions();
        self::$is_admin = self::hasPermission('administrator');
    }
    
    static public function isLoggedIn()
    {
        return self::$is_logged_in;
    }
    
    static public function isAdmin()
    {
        return self::$is_admin;
    }
    
    static public function getRecord()
    {
        return self::$record ? self::$record: false;
    }
    
    static public function getId()
    {
        return self::$record ? self::$record->id: false;
    }
    
    static public function getUserName()
    {  
        return self::$record ? self::$record->username: false;  
    }  
    
    static public function getPermissions()
    {
        return self::$permissions;
    }
    
    static public function hasPermission($permission)
    { 
        return in_array(strtolower($permission), self::$permissions);
    }
      
    /** 
     * Immediately (no redirect required) logs the user in. 
     * @param string $name 
     * @param string $password 
     * @param bool $set_cookie 
     * @return bool 
     */  
    static public function login($username, $password, $set_cookie=false)
    {
        self::logout();
          
        $user = User::findBy('username', $username);
        
        if ( ! $user instanceof User && self::ALLOW_LOGIN_WITH_EMAIL) {
            $user = User::findBy('email', $username);
        }

        if ($user instanceof User && $user->password == sha1($password)) {
            $user->last_login = date('Y-m-d H:i:s');
            $user->save();
            
            if ($set_cookie) {
                $time = $_SERVER['REQUEST_TIME'] + self::COOKIE_LIFE;
                setcookie(self::COOKIE_KEY, self::bakeUserCookie($time, $user->id, $user->usernamer), $time, '/', null, (isset($_ENV['SERVER_PROTOCOL']) && ((strpos($_ENV['SERVER_PROTOCOL'],'https') || strpos($_ENV['SERVER_PROTOCOL'],'HTTPS')))));
            }
            
            self::setInfos($user);
            return true;
        
        } else {
            if (self::DELAY_ON_INVALID_LOGIN) {
                if ( ! isset($_SESSION[self::SESSION_KEY.'.invalid_logins'])) {
                    $_SESSION[self::SESSION_KEY.'.invalid_logins'] = 1;
                } else {
                    ++$_SESSION[self::SESSION_KEY.'.invalid_logins'];
                }
                sleep(max(0, min($_SESSION[self::SESSION_KEY.'.invalid_logins'], (ini_get('max_execution_time') - 1))));
            }
            return false;   
        }
    }
    
    static public function logout()
    {
        session_unregister(self::SESSION_KEY);
        self::eatCookie();
        self::$record = false;
        self::$user_id = false;
        self::$is_admin = false;
        self::$permissions = array();
    }
    
    static protected function challengeCookie($cookie)
    {
        $params = self::explodeCookie($cookie);
        if (isset($params['exp'], $params['id'], $params['digest'])) {
            $user = Record::findByIdFrom('User', $params['id']);
            
            if (!$user) {
                return false;
            }
            
            if (self::bakeUserCookie($params['exp'], $user->id, $user->username) == $cookie && $params['exp'] > $_SERVER['REQUEST_TIME']) {
                return $user;
            }  
        }  
        return false;  
    }  
      
    static protected function explodeCookie($cookie)
    {
        $pieces = explode('&', $cookie);
        
        if (count($pieces) < 2) {
            return array();
        }
        
        foreach ($pieces as $piece) {
            list($key, $value) = explode('=', $piece);
            $params[$key] = $value;
        }
        return $params;
    }
    
    static protected function eatCookie()
    {
        setcookie(self::COOKIE_KEY, false, $_SERVER['REQUEST_TIME']-self::COOKIE_LIFE, '/', null, (isset($_ENV['SERVER_PROTOCOL']) && (strpos($_ENV['SERVER_PROTOCOL'],'https') || strpos($_ENV['SERVER_PROTOCOL'],'HTTPS'))));
    }
    
    static protected function bakeUserCookie($time, $id, $username)
    {
        return 'exp='.$time.'&id='.$id.='&digest='.md5(SECRET_KEY. $time. $id. $username);
    }
    
} // end AuthUser class