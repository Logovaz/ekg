@section('meta')
  <script type="text/javascript" src="[% URL::to('/') %]/js/information.js"></script>
@endsection

@section('content')
  <input type="hidden" id="url" value="[% URL::to('/') %]">
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
      <input type="text" name="weight" class="signup-input" placeholder="[% Lang::get('locale.weight') %]..." autocomplete="off">
    </div>
    <div class="centered search-block">
      <label for="year" class="notification">[% Lang::get('locale.birthday') %]:</label>
      <select name="year" id="year">
        @foreach($years as $year)
          <option value="[% $year %]">[% $year %]</option>
        @endforeach
      </select>
      <select name="month" id="month">
        <option value="1">[% Lang::get('names.january') %]</option>
        <option value="2">[% Lang::get('names.february') %]</option>
        <option value="3">[% Lang::get('names.march') %]</option>
        <option value="4">[% Lang::get('names.april') %]</option>
        <option value="5">[% Lang::get('names.may') %]</option>
        <option value="6">[% Lang::get('names.june') %]</option>
        <option value="7">[% Lang::get('names.july') %]</option>
        <option value="8">[% Lang::get('names.august') %]</option>
        <option value="9">[% Lang::get('names.september') %]</option>
        <option value="10">[% Lang::get('names.october') %]</option>
        <option value="11">[% Lang::get('names.november') %]</option>
        <option value="12">[% Lang::get('names.december') %]</option>
      </select>
      <select name="day" id="day" class="hidden">
      </select>
    </div>
    <div class="centered">
      <label for="gender" class="notification">[% Lang::get('locale.gender') %]:</label>
      <select name="gender">
        <option value="male">[% Lang::get('locale.male') %]</option>
        <option value="female">[% Lang::get('locale.female') %]</option>
      </select>
    </div>
    <div class="centered search-block">
      <input type="submit" value="[% Lang::get('locale.confirm') %]" class="green-btn signup-btn">
    </div>
    [[% Form::close() %]]
  </div>
@endsection

@include('common.skeleton')