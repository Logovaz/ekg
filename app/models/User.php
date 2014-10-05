<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Support\Facades\Auth;

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
    
    public function setInformation($args, $userId, $completeDate = false) {
        try {
            if($completeDate) {
                $birthday = $args['birthday'];
            } else {
                $birthday = $args['year'] . '-' . $args['month'] . '-' . $args['day'] . ' 00:00:00';
            }
            $middleName = empty($args['middle_name']) ? '' : $args['middle_name'];
            $id = DB::table('users')->where('id', $userId)->update(array(
                'first_name' => $args['name'],
                'middle_name' => $middleName,
                'last_name' => $args['surname'],
                'weight' => $args['weight'],
                'birthday' => $birthday,
                'gender' => $args['gender'],
                'timezone' => $args['timezone'],
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
    
    public function getProfile($id) {                
        try {
            $profile = DB::table('users')->where('id', $id)->first();            
            return $profile;
        } catch(Exception $e) {
            return false;
        }
    }
    public function getPatients() {
        try {
            $stmt = "
                select
                    u.`id`,
                    u.`first_name`,
                    u.`last_name`,
                    u.`email`,
                    u.`login`,
                    r.`status`
                from
                    `users` as u
                inner join
                    `relations` as r
                on
                    u.`id` = r.`user_id`
                where
                    r.`doctor_id` = :doctor_id
                and
                    u.`state` = 'registered'
            ";
            
            $bindings = array(
                'doctor_id' => Auth::user()->id
            );
            $patients = DB::select(DB::raw($stmt), $bindings);

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
            $messages = DB::table('messages')->where($directed, '=', Auth::user()->id)->get();
            if(empty($messages)) {
                return false;
            }
            return $messages;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function sendMessage($args) {
        try {
            DB::table('messages')->insert(array(
                'from' => Auth::user()->id,
                'to' => $args['user_id'],
                'text' => $args['message'],
                'status' => 'new'
            ));
            return true;
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
                array_push($result, array('id' => $item->id, 'date' => $item->timestamp, 'title' => $item->title, 'text' => substr($item->text, 0, 500) . '...'));
            }
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function getArticle($id) {
        return DB::table('news')->where('id', '=', $id)->first();
    }
    
    public function search($args) {
        if($args['login'] == Auth::user()->login) return false;
        try {
            $profile = DB::table('users')->where('login', $args['login'])->first();
            if($profile) {
                return $profile;
            } else {
                return false;
            }
        } catch(Exception $e) {
            return false;
        }
    }
    
    public function searchNotPatient($args) {
        if($args['login'] == Auth::user()->login) return false;
        try {
            $profile = DB::table('users')->where('login', $args['login'])->where('state', '=', 'registered')->first();
            if($profile) {
                $check = DB::table('relations')->where('user_id', $profile->id)->where('doctor_id', Auth::user()->id)->first();
                if($check) return false;
                return $profile;
            } else {
                return false;
            }
        } catch(Exception $e) {
            return false;
        }
    }
    
    public function exist($args) {
        $res = $this->getValue(DB::table('users')->select('id')->where('login', '=', $args['login'])->get(), 'id');        
        return $res; 
    }
    
    public function addPatient($id) {
        try {
            $res = DB::table('relations')->insertGetId(array(
                'doctor_id' => Auth::user()->id,
                'user_id' => $id,
                'status' => 'waiting'
            ));
            if($res) return true;
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function getYears() {
        $years = array(Lang::get('locale.year'));
        $currentYear = date('Y');
        for($i = $currentYear - 5; $i > $currentYear - 100; $i--) {
            array_push($years, $i);
        }
        return $years;
    }
    
    public function getDays($month, $year) {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    private function getValue($object, $value) {
        return $object[0]->$value;
    }
}