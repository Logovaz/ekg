@section('errors')
@if($errors->first() != '')
  <div class="error-block extend">
    [% $errors->first() %]
    <div class="close-btn" alt="[% Lang::get('locale.close') %]"></div>
  </div>
@endif
@endsection
