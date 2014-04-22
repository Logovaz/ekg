@section('meta')
<script type="text/javascript">
  var sendText = "[% Lang::get('locale.send') %]";
  var cancelText = "[% Lang::get('locale.cancel') %]";
  var clearText = "[% Lang::get('locale.clear') %]";
  var messageToText = "[% Lang::get('locale.message_to') %]";
</script>

<link rel="stylesheet" type="text/css" href="../css/jquery-ui.min.css">
<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/patients.js"></script>
@endsection

@section('content')
<div class="success-block centered hidden">
  [% Lang::get('locale.message_sent') %]
  <div class="close-success-btn" alt="Close"></div>
</div>
<div class="error-block centered hidden">
  [% Lang::get('locale.message_fail') %]
  <div class="close-btn" alt="Close"></div>
</div>
<div class="white-block centered messages-block">
  <div class="search-block">
  [[% Form::open(array('action' => 'UserController@patientsSearch')) %]]
    <input type="text" class="signup-input" name="search" placeholder="[% Lang::get('locale.email') %]" autocomplete="off">
    <input type="submit" class="green-btn search-btn" value="[% Lang::get('locale.search_user') %]">
  [[% Form::close() %]]
  </div>
  @if(Session::has('search_result'))
  <div class="centered inlined">
    <hr>
    <h3>[% Lang::get('locale.user_found') %]</h3>
      <div class="notification inlined">
        <a href="[% URL::to('/') %]/user/[% Session::get('search_result')->id %]"  target="_blank" class="undecorated">
          [% Session::get('search_result')->first_name %] [% Session::get('search_result')->last_name %] ([% Session::get('search_result')->login %])
        </a>
      </div>
      <div class="add-btn inlined" id="add-patient" title="[% Lang::get('locale.add_patient') %]"></div>
      [[% Form::open(array('action' => 'UserController@patientAdd', 'id' => 'add-form')) %]]
        <input type="hidden" value="[% Session::get('search_result')->id %]" name="user_id">
      [[% Form::close() %]]
  </div>
  @endif
  <hr>
  @if(!$patients)
    <h3>[% Lang::get('locale.no_patients') %]</h3>
  @else
  <h3>[% Lang::get('locale.your_patients') %]</h3>
    @foreach($patients as $patient)
      <div class="grey-block centered">
        <div class="notification inlined">
          <a class="undecorated" href="[% URL::to('/') %]/user/[% $patient->id %]">[% $patient->first_name %] [% $patient->last_name %] ([% $patient->login %])</a>
          @if($patient->status == 'waiting')
            <div class="time-btn inlined" title="[% Lang::get('locale.waiting_status') %]"></div>
          @else
            <div class="mail-btn inlined" title="[% Lang::get('locale.send_message') %]"><input type="hidden" value="[% $patient->user_id %]"></input></div>
          @endif
        </div>
      </div>
    @endforeach
  @endif
  <div class="message-form"><textarea id="message-area" class="message-area"></textarea></div>
</div>
@endsection

@include('common.skeleton')