<?php

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
    
    public static function setAccess() {
        if(Auth::check()) {
            $role = Auth::user()->role;
        } else {
            $role = 'guest';
        }
        $url = Request::path();
        $rights = Config::get('acl.' . $role . '.' . $url);
        if(is_null($rights)) $rights = array();
        Session::flash('acl', $rights);
        return $rights;
    }
    
    public static function has($option) {
        return in_array($option, Session::get('acl')) ? true : false;
    }
}