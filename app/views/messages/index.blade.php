@section('content')
@if(Acl::has('user'))
<div class="white-block centered messages-block">
  @if(!$messages)
    <p>
      [% Lang::get('locale.no_messages') %]
    </p>
  @else
    @foreach($messages as $message)
      <p></p>
    @endforeach
  @endif
</div>
@endif

@if(Acl::has('doctor'))
<div class="white-block centered messages-block">
  @if(!$users)
    <p>
      [% Lang::get('locale.no_patients') %]
    </p>
  @else
    <select name="user_id">
      @foreach($users as $user)
        <option value="[% $user['id'] %]">[% $user['first_name'] %] [% $user['last_name'] %]</option>
      @endforeach
    </select>
    <textarea name="message" rows="5" cols="70"></textarea>
  @endif
</div>
<div class="white-block centered messages-block">
  @if(!$messages)
    <p>
      [% Lang::get('locale.no_messages') %]
    </p>
  @else
    @foreach($messages as $message)
      
    @endforeach
  @endif
</div>
@endif
@endsection

@include('common.skeleton')