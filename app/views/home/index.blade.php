@section('content')
@if(!$news)
  <div class="white-block no-data">
    [% Lang::get('locale.no_news') %]
  </div>
@else
  @foreach($news as $item)
  <div class="white-block news-block">
    <h3><a class="undecorated blue" href="[% URL::to('/') %]/article/[% $item['id'] %]">[% $item['title'] %]</a></h3>
    <p>
      [% $item['text'] %]
    </p>
  </div>
  @endforeach
@endif
@endsection

@include('common.skeleton')