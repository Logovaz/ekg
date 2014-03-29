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

@section('notifications')
@if(Session::get('success') != null)
  <div class="success-block centered">
    [% Session::get('success') %]
    <div class="close-success-btn" alt="[% Lang::get('locale.close') %]"></div>
  </div>
@endif
@endsection

@section('content')
  <div class="white-block centered signup">
    <p class="notification">
      [% Lang::get('locale.thank_registration') %] <a href="mailto:[% Config::get('mail.from.address') %]">[% Lang::get('locale.contact_us') %]</a>
    </p>
    <hr>
    <p>
      [% Lang::get('locale.confirm_registration') %]
    </p>
    [[% Form::open(array('action' => 'UserController@confirmProcess')) %]]
    <div class="centered">
      <input type="text" name="user_id" class="signup-input" placeholder="[% Lang::get('locale.user_id') %]" autocomplete="off">
    </div>
    <div class="centered">
      <input type="text" name="code" class="signup-input" placeholder="[% Lang::get('locale.confirmation_code') %]" autocomplete="off">
    </div>
    <div class="centered">
      <input type="submit" value="[% Lang::get('locale.confirm') %]" class="green-btn signup-btn">
    </div>
    [[% Form::close() %]]
  </div>
@endsection

@include('common.skeleton')