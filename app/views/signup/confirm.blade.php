@section('title')
[% $title %]
@endsection

@section('errors')
@if($errors->first() != '')
  <div class="error-block centered">
    [% $errors->first() %]
  </div>
@endif
@endsection

@section('content')
  <div class="white-block centered signup">
    <p>
      [% Lang::get('locale.confirm_registration') %]
    </p>
    [[% Form::open(array('action' => 'UserController@signupConfirm')) %]]
    <div class="centered">
      <input type="text" name="code" class="signup-input" placeholder="[% Lang::get('locale.confirmation_code') %]" autocomplete="off">
    </div>
    <div class="centered">
      <input type="text" name="name" class="signup-input" placeholder="[% Lang::get('locale.name') %]" autocomplete="off">
    </div>
    <div class="centered">
      <input type="text" name="surname" class="signup-input" placeholder="[% Lang::get('locale.surname') %]" autocomplete="off">
    </div>
    <div class="centered">
      <input type="submit" value="[% Lang::get('locale.confirm') %]" class="green-btn signup-btn">
    </div>
    [[% Form::close() %]]
  </div>
@endsection

@include('common.skeleton')