@section('content')
@if(!$graphs)
  <div class="white-block messages-block">
    <h3 >
      [% Lang::get('locale.no_graphs') %]
    </h3>
  </div>
@else
  @foreach($graphs as $graph)
    <div class="white-block messages-block">
      <a class="undecorated blue" href="[% URL::to('/') %]/graph/[% $user_id %]/[% $graph->ecg_id %]" target="blank_">[% $graph->timestamp %]</a>
    </div>
  @endforeach
@endif
@endsection 

@include('common.skeleton')