<?php

use Illuminate\Auth\UserInterface;

class User extends Eloquent implements UserInterface {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');
    
    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
    	return $this->getKey();
    }
    
    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
    	return $this->password;
    }
    
    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
    	return $this->email;
    }
    
    public function register($args) {
        if(!isset($args['login']) || !isset($args['password'])) {
            return false;
        }
        try {
            $code = rand(100000, 999999);
            $id = DB::table('users')->insertGetId(array(
                'login' => $args['login'],
                'password' => $args['password'],
                'email' => $args['login'],
                'code' => $code
            ));
            $args['id'] = $id;
            $args['code'] = $code;
            $this->sendRegisterMail($args);
            return $id;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function confirm($args) {
        if(!isset($args['code']) || !isset($args['user_id'])) {
            return false;
        }
        $code = $this->getValue(DB::table('users')->select('code')->where('id', '=', $args['user_id'])->get(), 'code');
        if($args['code'] != $code) {
            return false;
        } else {
            try {
                $id = DB::table('users')->where('id', $args['user_id'])->update(array(
                    'state' => 'information'
                ));
                return $id;
            } catch (Exception $e) {
                return false;
            }
        }
    }
    
    public function setInformation($args) {
        try {
            $id = DB::table('users')->where('id', Auth::user()->id)->update(array(
                'first_name' => $args['name'],
                'last_name' => $args['surname'],
                'state' => 'registered'
            ));
            return $id;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function sendRegisterMail($args) {
        $user = $args['login'];
         
        $data = array(
            'link' => URL::to('signup/link') . '/' . $args['id'] . '/' . md5($args['code']),
            'user_id' => $args['id'],
            'code' => $args['code']
        );
         
        Mail::send('emails.confirm', $data, function($message) use ($user) {
            $message->to($user, $user)->subject(Lang::get('reminders.confirmation_subject'));
        });
    }
    
    public function registerByLink($id, $hash) {
        $code = $this->getValue(DB::table('users')->select('code')->where('id', '=', $id)->get(), 'code');
        if(md5($code) == $hash) {
            DB::table('users')->where('id', $id)->update(array('state' => 'information'));
            return true;
        }
        return false;
    }
    
    public function checkConfirmation($login) {
        try {
            $state = $this->getValue(DB::table('users')->select('state')->where('login', '=', $login)->get(), 'state');
            if($state == 'confirmation') {
                return 'confirmation';
            }
            return 'accepted';
        } catch(Exception $e) {
            return 'empty';
        }
    }
    
    public function getPatients() {
        try {
            $patients = DB::table('users')->join('relations', 'users.id', '=', 'relations.user_id')
                                          ->select('*')->where($directed, '=', Auth::user()->id)
                                          ->where('state', '=', 'registered')->get();
            if(empty($patients)) {
                return false;
            }
            return $patients;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function getMessages() {
        if(Auth::user()->role == 'user') {
            $directed = 'to';
        } elseif (Auth::user()->role == 'doctor') {
            $directed = 'from';
        }
        try {
            $messages = DB::table('messages')->select('*')->where($directed, '=', Auth::user()->id)->get();
            if(empty($messages)) {
                return false;
            }
            return $messages;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function getNews() {
        try {
            $news = DB::table('news')->select('*')->where('visibility', '=', 'visible')->get();
            if(empty($news)) {
               return false; 
            }
            $result = array();
            foreach($news as $item) {
                array_push($result, array('date' => $item->timestamp, 'title' => $item->title, 'text' => substr($item->text, 0, 500) . '...'));
            }
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function search($args) {                
        $profile = DB::table('users')->where('login', $args['login'])->first();
        return $profile;
    }

    private function getValue($object, $value) {
        return $object[0]->$value;
    }    
}