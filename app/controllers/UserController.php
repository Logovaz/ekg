<?php

use Illuminate\Support\Facades\Redirect;

class UserController extends Controller {
    
    public function home() {
        return View::make('home.index')->with('title', Lang::get('locale.home_title'));
    }
    
    public function signup() {
        return View::make('signup.index')->with('title', Lang::get('locale.signup_title'));
    }
    
    public function confirm() {
        return View::make('signup.confirm')->with('title', Lang::get('locale.signup_confirm'));
    }
    
    public function profile() {
        return View::make('profile.index')->with('title', Lang::get('locale.common_title') . Auth::user()->first_name . ' ' . Auth::user()->last_name);
    }
    
    public function login() {
        return View::make('login.index')->with('title', Lang::get('locale.login_title'));
    }
    
    public function logout() {
        Auth::logout();
        return Redirect::to('/');
    }
    
    public function signupConfirm() {
        $rules = array(
            'code' => 'required|numeric',
            'name' => 'required|alpha|max:24|min:2',
            'surname' => 'required|alpha|max:32|min:2'
        );
        $validator = Validator::make(Input::all(), $rules);
        if($validator->passes()) {
            $user = new User();
            if($user->confirm(array('code' => Input::get('code'), 'name' => Input::get('name'), 'surname' => Input::get('surname')))) {
                return Redirect::to('profile');
            } else {
                return Redirect::to('confirm')->withErrors(Lang::get('locale.incorrect_code'));
            }
        } else {
            return Redirect::to('confirm')->withErrors($validator);
        }
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
                if (Auth::attempt(array('login' => Input::get('email'), 'password' => Input::get('password')))) {
                    return Redirect::to('confirm');
                }
            } else {
                return Redirect::to('signup')->withErrors(Lang::get('locale.duplicate_email'));
            }
        } else {
            return Redirect::to('signup')->withErrors($validator);
        }
    }
    
    public function signupLink($id, $code) {
        return Redirect::to('confirm')->with('id', $id)->with('code', $code);
    }
    
    public function loginProcess() {
        $rules = array(
            'login' => 'required|email',
            'password' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
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
}