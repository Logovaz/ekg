@section('content')
<div class="white-block centered messages-block">
  [[% Form::open(array('action' => 'UserController@userSearch')) %]]
    <input type="text" class="signup-input" name="search" placeholder="[% Lang::get('locale.email') %]" autocomplete="off">
    <input type="submit" class="green-btn" value="[% Lang::get('locale.search_user') %]">
  [[% Form::close() %]]
</div>
<div class="white-block centered messages-block">
  @if(!$patients)
    <p>
      [% Lang::get('locale.no_patients') %]
    </p>
  @else
    @foreach($patients as $patient)
      <div class="grey-block centered">
        <a href="[% URL::to('/') %]/user/[% $patient['id'] %]">[% $patient['name'] %] [% $patient['surname'] %]</a>
      </div>
    @endforeach
  @endif
</div>
@endsection

@include('common.skeleton')