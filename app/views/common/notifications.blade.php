@section('notifications')
@if(Session::get('success') != null)
  <div class="success-block centered">
    [% Session::get('success') %]
    <div class="close-success-btn" alt="[% Lang::get('locale.close') %]"></div>
  </div>
@endif
@endsection