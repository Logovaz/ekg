@section('content')
@if(Acl::has('user'))
  @if(!$messages)
    <div class="white-block centered messages-block">
      <h3>[% Lang::get('locale.no_messages') %]</h3>
    </div>
  @else
    @foreach($messages as $message)
    <div class="white-block centered messages-block">
      <div class="news-block">
        [% $message->text %]
      </div>
    </div>
    @endforeach
  @endif
@endif

@if(Acl::has('doctor'))
<div class="white-block centered messages-block">
  @if(!$users)
    <p>
      [% Lang::get('locale.no_patients') %]
    </p>
  @else
    [[% Form::open(array('action' => 'UserController@sendMessage')) %]]
    <select class="centered block" name="user_id">
      @foreach($users as $user)
        <option value="[% $user->user_id %]">[% $user->first_name %] [% $user->last_name %]</option>
      @endforeach
    </select>
    <textarea class="centered block" name="message" rows="5" cols="70"></textarea>
    <input type="submit" class="green-btn" value="[% Lang::get('locale.send') %]">
    [[% Form::close() %]]
  @endif
</div>
<div class="white-block centered messages-block">
  @if(!$messages)
    <div class="white-block centered messages-block">
      <h3>[% Lang::get('locale.no_messages') %]</h3>
    </div>
  @else
    @foreach($messages as $message)
      <div class="white-block centered messages-block">
        <div class="news-block">
          [% $message->text %]
        </div>
      </div>
    @endforeach
  @endif
</div>
@endif
@endsection

@include('common.skeleton')