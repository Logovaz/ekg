@section('title')
[% $title %]
@endsection

@section('errors')
@if($errors->first() != '')
  <div class="error-block centered">
    [% $errors->first() %]
    <div class="close-btn" alt="[% Lang::get('locale.close') %]"></div>
  </div>
@endif
@endsection

@section('content')
  <div class="white-block centered signup">
    <a href="[% URL::to('fbsignup') %]" class="fb-btn centered">[% Lang::get('locale.facebook_signup') %]</a>
    <hr>
    <p>
      [% Lang::get('locale.signup_manually') %]
    </p>
    [[% Form::open(array('action' => 'UserController@signupProcess')) %]]
    <div class="centered">
      <input type="email" name="email" class="signup-input" placeholder="[% Lang::get('locale.enter_your_email') %]" autocomplete="off">
    </div>
    <div class="centered">
      <input type="password" name="password" class="signup-input" placeholder="[% Lang::get('locale.password') %]" autocomplete="off">
    </div>
    <div class="centered">
      <input type="password" name="password_confirmation" class="signup-input" placeholder="[% Lang::get('locale.confirm_password') %]" autocomplete="off">
    </div>
    <div class="centered">
      <input type="submit" value="[% Lang::get('locale.signup') %]" class="green-btn signup-btn">
    </div>
    [[% Form::close() %]]
    <p class="notification">
      [% Lang::get('locale.already_have_account') %] <a href="[% URL::to('login') %]">[% Lang::get('locale.login') %]</a>
    </p>
  </div>
@endsection

@include('common.skeleton')