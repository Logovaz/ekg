@section('title')
[% $title %]
@endsection

@section('content')
<div class="main-block">    
    <h2>[% Lang::get('locale.search_user') %]</h2>
    <hr>
    [[% Form::open(array('action' => 'UserController@profileSearch')) %]]
    <div class="centered">
      <input type="email" name="search" class="signup-input" placeholder="[% Lang::get('locale.enter_users_email') %]" autocomplete="off">
    </div>
    <div class="centered">
      <input type="submit" value="Search" class="green-btn">
    </div>
    [[% Form::close() %]]
</div>
@endsection 

@include('profile.sidebar')
@include('profile.errors')
@include('common.skeleton')