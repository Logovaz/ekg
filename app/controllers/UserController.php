<?php

class UserController extends Controller {
    
    public function home() {
        return View::make('home.index')->with('title', Lang::get('locale.home_title'));
    }
    
    public function signup() {
        return View::make('signup.index')->with('title', Lang::get('locale.signup_title'));
    }
    
    public function confirm() {
        return View::make('confirm.index')->with('title', Lang::get('locale.signup_confirm'));
    }
    
    public function information() {
        return View::make('information.index')->with('title', Lang::get('locale.information'));
    }
    
    public function profile() {
        return View::make('profile.index')->with('title', Lang::get('locale.common_title') . Auth::user()->first_name . ' ' . Auth::user()->last_name);
    }
    
    public function login() {
        return View::make('login.index')->with('title', Lang::get('locale.login_title'));
    }
    
    public function change() {        
        return View::make('user.change')->with('title', Lang::get('locale.common_title') . Auth::user()->first_name . ' ' . Auth::user()->last_name);
    }
    
    public function userSearch() {
        return View::make('user.search')->with('title', Lang::get('locale.user_search_title'));
    }
    
    public function logout() {
        Auth::logout();
        return Redirect::to('/');
    }
    
    public function signupProcess() {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required|min:6|max:24|confirmed',
            'password_confirmation' => 'required|min:6|max:24'
        );
        $validator = Validator::make(Input::all(), $rules);
        if($validator->passes()) {
            $user = new User();
            if($user->register(array('login' => Input::get('email'), 'password' => Hash::make(Input::get('password'))))) {
                return Redirect::to('confirm');
            } else {
                return Redirect::to('signup')->withErrors(Lang::get('locale.duplicate_email'));
            }
        } else {
            return Redirect::to('signup')->withErrors($validator);
        }
    }
    
    public function confirmProcess() {
        $rules = array(
                'user_id' => 'required|integer',
                'code' => 'required|integer'
        );
    
        $validator = Validator::make(Input::all(), $rules);
        if($validator->passes()) {
            $user = new User();
            if($user->confirm(array('code' => Input::get('code'), 'user_id' => Input::get('user_id')))) {
                return Redirect::to('login')->with('success', Lang::get('locale.code_accepted'));
            } else {
                return Redirect::to('confirm')->withErrors(Lang::get('locale.code_decline'));
            }
        } else {
            return Redirect::to('confirm')->withErrors($validator);
        }
    }
    
    public function informationProcess() {
        $rules = array(
            'name' => 'required|alpha|min:2|max:32',
            'surname' => 'required|alpha|min:2|max:32'
        );
        
        $validator = Validator::make(Input::all(), $rules);
        if($validator->passes()) {
            $user = new User();
            $user->setInformation(Input::all());
        }
        return Redirect::to('profile')->with('success', Lang::get('locale.information_updated'));
    }
    
    public function confirmLink($id, $code) {
        $user = new User();
        if($user->registerByLink($id, $code)) {
            return Redirect::to('login')->with('success', Lang::get('locale.code_accepted'));
        }
        return Redirect::to('login')->withErrors(Lang::get('locale.code_decline'));
    }
    
    public function loginProcess() {
        $rules = array(
            'login' => 'required|email',
            'password' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        
        $user = new User();
        switch($user->checkConfirmation(Input::get('login'))) {
            case 'confirmation': return Redirect::to('confirm'); break;
            case 'accepted': break;
            case 'empty': return Redirect::to('login')->withErrors(Lang::get('locale.wrong_login')); break;
        }
        
        if($validator->passes()) {
            if(Auth::attempt(array('login' => Input::get('login'), 'password' => Input::get('password')))) {
                return Redirect::to('profile');
            } else {
                return Redirect::to('login')->withErrors(Lang::get('locale.wrong_login'));
            }
        } else {
            return Redirect::to('login')->withErrors($validator);
        }
    }

    public function userSearchProcess() {
        if(!Auth::check()) {
            return Redirect::to('login');    
        }
        $rules = array(
            'search' => 'required|email'
        );
        $validator = Validator::make(Input::all(), $rules);
        if($validator->passes()) {
            $user = new User();
            if($userprofile = $user->search(array('login' => Input::get('search')))) {
                return Redirect::to('user/change')->with('userprofile', $userprofile);
            } else {                
                return Redirect::to('user/search')->withErrors(Lang::get('locale.user_not_found'));                
            }
        } else {
            return Redirect::to('user/search')->withErrors($validator);
        }
    }

    public function userChangeProcess()
    {
        if(!Auth::check()) {
            return Redirect::to('login')->with('success', Lang::get('locale.not_logged'));
        }

        $rules = array(
            'name' => 'required|alpha|max:24|min:2',
            'surname' => 'required|alpha|max:32|min:2'
        );
        $validator = Validator::make(Input::all(). $rules);
        if($validator->passes()) {
            $user = new User();
            
        }

    }
}