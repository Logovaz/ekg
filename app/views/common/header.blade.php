@section('header')
<div class="header">
  @if(Auth::check())
    @if(Auth::user()->first_name != null && Auth::user()->last_name != null)
      <div class="header-menu">
        <a href="#" id="profile">[% Auth::user()->first_name %] [% Auth::user()->last_name %]</a>
      </div>
    @endif
    <div class="header-menu">
      <a href="[% URL::to('logout') %]" class="green-btn header-btn">[% Lang::get('locale.logout') %]</a>
    </div>
  @else
    <div>
      <a href="[% URL::to('signup') %]" class="green-btn header-btn">[% Lang::get('locale.signup') %]</a>
      <a href="[% URL::to('login') %]" class="green-btn header-btn">[% Lang::get('locale.login') %]</a>
    </div>
  @endif
</div>
<div class="menu-block centered">
  <a href="[% URL::to('/') %]" class="green-btn menu-btn">[% Lang::get('locale.home') %]</a>
  <a href="[% URL::to('ecg') %]" class="green-btn menu-btn">[% Lang::get('locale.ecg') %]</a>
  <a href="[% URL::to('contacts') %]" class="green-btn menu-btn">[% Lang::get('locale.contacts') %]</a>
  <a href="[% URL::to('profile') %]" class="green-btn menu-btn">[% Lang::get('locale.my_ecg') %]</a>
  @if(Acl::has('patients'))
    <a href="[% URL::to('patients') %]" class="green-btn menu-btn">[% Lang::get('locale.patients') %]</a>
  @endif
  @if(Acl::has('messages'))
    <a href="[% URL::to('messages') %]" class="green-btn menu-btn">[% Lang::get('locale.messages') %]</a>
  @endif
  @if(Acl::has('control'))
    <a href="[% URL::to('control') %]" class="green-btn menu-btn">[% Lang::get('locale.control') %]</a>
  @endif
</div>
@endsection