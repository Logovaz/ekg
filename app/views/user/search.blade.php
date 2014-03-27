@section('title')
[% $title %]
@endsection

@section('errors')
@if($errors->first() != '')
  <div class="error-block extend">
    [% $errors->first() %]
    <div class="close-btn" alt="[% Lang::get('locale.close') %]"></div>
  </div>
@endif
@endsection

@section('content')
<div class="main-block">    
  <h2>[% Lang::get('locale.search_user') %]</h2>
  <hr>
  [[% Form::open(array('action' => 'UserController@userSearchProcess')) %]]
    <div class="centered">
      <input type="email" name="search" class="signup-input" placeholder="[% Lang::get('locale.enter_users_email') %]" autocomplete="off">
    </div>
    <div class="centered">
      <input type="submit" value="Search" class="green-btn">
    </div>
  [[% Form::close() %]]
</div>
@endsection 

@include('common.sidebar')
@include('common.skeleton')