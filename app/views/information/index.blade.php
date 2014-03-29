@section('content')
  <div class="white-block centered signup">
    <p>
      [% Lang::get('locale.enter_information') %]
    </p>
    [[% Form::open(array('action' => 'UserController@informationProcess')) %]]
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