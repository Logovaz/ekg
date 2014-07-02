<?php
/**
 * @author Iliya.Bubenschikov
 */
class UserController extends Controller {
    
	/**
	 * Display index page 
	 * @author Iliya.Bubenschikov
	 * @return View 
	 */
    public function home() {
        $user = new User();
        return View::make('home.index')->with('title', Lang::get('locale.home_title'))->with('news', $user->getNews());
    }
    
    /**
     * Display signup page
     * @author Iliya.Bubenschikov
     * @return View
     */
    public function signup() {
        return View::make('signup.index')->with('title', Lang::get('locale.signup_title'));
    }
    
    /**
     * Display signup confirmation page
     * @author Iliya.Bubenschikov
     * @return View
     */
    public function confirm() {
        return View::make('confirm.index')->with('title', Lang::get('locale.signup_confirm'));
    }
    
    /**
     * Display information input (name, surname) page !
     * @author Iliya.Bubenschikov
     * @return View
     */
    public function information() {
        $user = new User();
        return View::make('information.index')->with('years', $user->getYears())->with('title', Lang::get('locale.information'));
    }
    
    /**
     * Display contacts page
     * @author Iliya.Bubenschikov
     * @return View
     */
    public function contacts() {
        return View::make('contacts.index')->with('title', Lang::get('locale.contacts'));
    }
    
    /**
     * Display profile page
     * @author Iliya.Bubenschikov
     * @return View
     */
    public function profile() {
        $date['month'] = date('m');
        $date['year'] = date('Y');
        $ecg = new Ecg();
        $years = $ecg->getYears(Auth::user()->id);
        $list = $ecg->getGraphs(Auth::user()->id);
        return View::make('profile.index')
                ->with('title', Lang::get('locale.common_title') . Auth::user()->first_name . ' ' . Auth::user()->last_name)
                ->with('graphs', $list)
                ->with('user_id', Auth::user()->id)
                ->with('date', $date)
                ->with('years', $years);
    }
    
    /**
     * Display admin control page
     * @author Iliya.Bubenschikov
     * @return View
     */
    public function control() {
        return View::make('control.index')->with('title', Lang::get('locale.common_title') . Auth::user()->first_name . ' ' . Auth::user()->last_name);
    }

    /**
     * Display login page
     * @author Iliya.Bubenschikov
     * @return View
     */
    public function login() {
        return View::make('login.index')->with('title', Lang::get('locale.login_title'));
    }
    
    /**
     * @deprecated
     * @author Roman.Kolomeets
     * @param int $id - user id
     */
    public function change($id) {        
        return View::make('user.change')->with('title', Lang::get('locale.common_title') . Auth::user()->first_name . ' ' . Auth::user()->last_name);
    }
    
    /**
     * Display patients page - for doctor only
     * @author Iliya.Bubenschikov
     * @return View
     */
    public function patients() {
        $user = new User();
        return View::make('patients.index')->with('title', Lang::get('locale.patients_title'))->with('patients', $user->getPatients());
    }
    
    /**
     * Display user search page - for doctor only
     * @author Iliya.Bubenschikov
     * @return View 
     */
    public function userSearch() {
        return View::make('user.search')->with('title', Lang::get('locale.user_search_title'));
    }
    
    /**
     * @deprecated
     * @author Roman.Kolomeets
     * @param int $id
     * @return View
     */
    public function article($id) {
        $user = new User();
        $article = $user->getArticle($id);
        return View::make('article.index')->with('title', Lang::get('locale.common_title') . $article->title)->with('article', $article);
    }
    
    /**
     * Display messages page
     * @author Iliya.Bubenschikov
     * @return View
     */
    public function messages() {
        $user = new User();
        return View::make('messages.index')
                ->with('title', Lang::get('locale.messages_title'))
                ->with('messages', $user->getMessages())
                ->with('users', $user->getPatients());
    }
    
    /**
     * User logout
     * @author Iliya.Bubenschikov
     * @return Redirect
     */
    public function logout() {
        Auth::logout();
        return Redirect::to('/');
    }
    
    /**
     * Signup process
     * @author Iliya.Bubenschikov
     * @return Redirect
     */
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
    
    /**
     * Send message - for doctor only
     * @author Iliya.Bubenschikov
     * @return Redirect
     */
    public function sendMessage() {
        $user = new User();
        if($user->sendMessage(Input::all())) {
            return Redirect::to('messages')->with('success', Lang::get('locale.message_sent'));
        } else {
            return Redirect::to('messages')->withErrors(Lang::get('locale.message_fail'));
        }
    }
    
    /**
     * Send message with ajax request
     * @author Iliya.Bubenschikov
     * @return Redirect json
     */
    public function sendAjaxMessage() {
        $user = new User();
        if($user->sendMessage(Input::all())) {
            return Response::json(Lang::get('locale.message_sent'));
        } else {
            return Response::json(Lang::get('locale.message_fail'));
        }
    }
    
    public function patientsSearch() {
        $rules = array(
          'search' => 'required|email'  
        );
        $validator = Validator::make(Input::all(), $rules);
        if($validator->passes()) {
            $user = new User();
            $result = $user->searchNotPatient(array('login' => Input::get('search')));
            if(is_null($result) || !$result) {
                return Redirect::to('patients')->withErrors(Lang::get('locale.no_results'));
            } else {
                return Redirect::to('patients')->with('search_result', $result);
            }
        } else {
            return Redirect::to('patients')->withErrors($validator);
        }
    }
    
    public function patientAdd() {
        $user = new User();
        if($user->addPatient(Input::get('user_id'))) {
            return Redirect::to('patients')->with('success', Lang::get('locale.user_add_notify'));
        } else {
            return Redirect::to('patients')->withErrors(Lang::get('locale.request_fail'));
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
    
    public function getDays() {
        $user = new User();
        return Response::json($user->getDays(Input::get('month'), Input::get('year')));
    }

    public function userSearchProcess() {
        $rules = array(
            'search' => 'required|email'
        );
        $validator = Validator::make(Input::all(), $rules);
        if($validator->passes()) {
            $user = new User();
            if($id = $user->exist(array('login' => Input::get('search')))) {
                return Redirect::to('user/'.$id);
            } else {                
                return Redirect::to('user/search')->withErrors(Lang::get('locale.user_not_found'));                
            }
        } else {
            return Redirect::to('user/search')->withErrors($validator);
        }
    }

    public function userView($id) {
        $user = new User();
        $profile = $user->getProfile($id);
        return View::make('user.view', array('profile' => $profile))->with('title', Lang::get('locale.common_title') . Auth::user()->first_name . ' ' . Auth::user()->last_name);
    }

    public function userChange() {
    	dd(Input::all());
        $rules = array(
            'first' => 'required|alpha|max:24|min:2',
            'surname' => 'required|alpha|max:32|min:2',
        	
        );
        $validator = Validator::make(Input::all(). $rules);
        if($validator->passes()) {
            $user = new User();
        }
    }
}