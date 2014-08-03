<?php
/**
 * ACL model for controlling user rights
 * Initialized by filters - see it there
 * @see app/config/Acl.php - for config file
 * @author Iliya Bubenschikov <mephis.oct@gmail.com>
 * @version 1.0
 */
class Acl {
    protected static $instance = null;
    
    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}
    
    public static function initialize() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    /**
     * Set access level for current user
     * @author Iliya Bubenschikov <mephis.oct@gmail.com>
     * @return multitype:
     */
    public static function setAccess() {
        if(Auth::check()) {
            $role = Auth::user()->role;
        } else {
            $role = 'guest';
        }
        $url = Request::path();
        $rights = Config::get('acl.' . $role . '.' . $url);
        if(is_null($rights)) {
            $rights = Config::get('acl.' . $role . '.*');
        }
        if(is_null($rights)) $rights = array();
        Session::flash('acl', $rights);
        return $rights;
    }
    
    /**
     * Check if user has rights for given option
     * Options stored in session
     * @param string $option
     * @return true or false
     */
    public static function has($option) {
        return in_array($option, Session::get('acl')) ? true : false;
    }
}