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
    <div class="centered">
      <label for="timezone" class="notification">[% Lang::get('locale.your_timezone') %]:</label>
      <select name="timezone">
        <option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
        <option value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
        <option value="-10.0">(GMT -10:00) Hawaii</option>
        <option value="-9.0">(GMT -9:00) Alaska</option>
        <option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
        <option value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
        <option value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
        <option value="-5.0">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
        <option value="-4.0">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
        <option value="-3.5">(GMT -3:30) Newfoundland</option>
        <option value="-3.0">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
        <option value="-2.0">(GMT -2:00) Mid-Atlantic</option>
        <option value="-1.0">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
        <option value="0.0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
        <option value="1.0">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
        <option value="2.0">(GMT +2:00) Kaliningrad, South Africa</option>
        <option value="3.0">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
        <option value="3.5">(GMT +3:30) Tehran</option>
        <option value="4.0">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
        <option value="4.5">(GMT +4:30) Kabul</option>
        <option value="5.0">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
        <option value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
        <option value="5.75">(GMT +5:45) Kathmandu</option>
        <option value="6.0">(GMT +6:00) Almaty, Dhaka, Colombo</option>
        <option value="7.0">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
        <option value="8.0">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
        <option value="9.0">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
        <option value="9.5">(GMT +9:30) Adelaide, Darwin</option>
        <option value="10.0">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
        <option value="11.0">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
        <option value="12.0">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
      </select>
    </div>
    <div class="centered search-block">
      <input type="submit" value="[% Lang::get('locale.confirm') %]" class="green-btn signup-btn">
    </div>
    [[% Form::close() %]]
  </div>
@endsection

@include('common.skeleton')