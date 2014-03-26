@section('title')
[% $title %]
@endsection

@section('content')
<div class="main-block">    
    <h2>[% Session::get('userprofile')->first_name . ' ' . Session::get('userprofile')->last_name %] </h2>    
    <hr>
    <p>Change profile</p>    
    <div>
    [[% Form::open(array('action' => 'UserController@profileChangeProcess')) %]]
        
        <div class="centered">
            <input type="text" name="name" class="signup-input" autocomplete="off" value="[% Session::get('userprofile')->first_name %]">
        </div>
        <div class="centered">
            <input type="text" name="surname" class="signup-input" autocomplete="off" value="[% Session::get('userprofile')->last_name %]">
        </div>
        <div class="centered">
            <input type="submit" value="[% Lang::get('locale.save') %]" class="green-btn">
        </div>

    [[% Form::close() %]]
    </div>
</div>
@endsection

@include('profile.sidebar')
@include('profile.errors')
@include('common.skeleton')