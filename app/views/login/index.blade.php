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
    <a href="[% URL::to('fblogin') %]" class="fb-btn centered">[% Lang::get('locale.facebook_login') %]</a>
    <hr>
    <p>
      [% Lang::get('locale.login_manually') %]
    </p>
    [[% Form::open(array('action' => 'UserController@loginProcess')) %]]
    <div class="centered">
      <input type="email" name="login" class="signup-input" placeholder="[% Lang::get('locale.enter_your_email') %]" autocomplete="off">
    </div>
    <div class="centered">
      <input type="password" name="password" class="signup-input" placeholder="[% Lang::get('locale.password') %]" autocomplete="off">
    </div>
    <div class="centered">
      <input type="submit" value="[% Lang::get('locale.login') %]" class="green-btn signup-btn">
    </div>
    [[% Form::close() %]]
    <p class="notification">
      [% Lang::get('locale.forgot_password') %] <a href="[% URL::to('restore') %]">[% Lang::get('locale.restore') %]</a>
    </p>
  </div>
@endsection

@include('common.skeleton')