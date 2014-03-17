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
    <p>
      [% Lang::get('locale.confirm_registration') %]
    </p>
    [[% Form::open(array('action' => 'UserController@signupConfirm')) %]]
    @if(Auth::user()->state == 'information')
      <input type="hidden" name="code" value="information">
    @else
      <div class="centered">
        <input type="text" name="code" class="signup-input" placeholder="[% Lang::get('locale.confirmation_code') %]" autocomplete="off">
      </div>
    @endif
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