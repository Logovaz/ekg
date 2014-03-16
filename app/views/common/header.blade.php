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
@endsection