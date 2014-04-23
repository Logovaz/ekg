@section('content')
<div class="white-block news-block">
  <h3>[% $article->title %]</h3>
  <p>
    [% $article->text %]
  </p>
</div>
@endsection

@include('common.skeleton')