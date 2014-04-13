@section('content')
@if(!$news)
  <div class="white-block news-block">
    [% Lang::get('locale.no_news') %]
  </div>
@else
  @foreach($news as $item)
  <div class="white-block news-block">
    <h3>[% $item['title'] %]</h3>
    <p>
      [% $item['text'] %]
    </p>
  </div>
  @endforeach
@endif
@endsection

@include('common.skeleton')