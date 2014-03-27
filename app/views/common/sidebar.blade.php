@section('sidebar')
<div class="sidebar">
<ul>
  <li>
    <a href="[% URL::to('profile') %]">User</a>
  </li>
  <li>
    <a href="[% URL::to('userekg') %]">EKG</a>
  </li>
  <li>
    <a href="[% URL::to('messages') %]">Messages</a>
  </li>
  <li>
    <a href="[% URL::to('adwords') %]">Adwords1</a>
  </li>
  <li>
    <a href="[% URL::to('adwords2') %]">Adwords2</a>        
  </li>
  <li>
    <a href="[% URL::to('addnews') %]">News</a>
  </li>
  @if(Acl::has('search_user'))
    <li>
      <a href="[% URL::to('user/search') %]">[% Lang::get('locale.search_user') %]</a>
    </li>
  @endif
</ul>
</div>
@endsection