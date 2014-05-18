@section('meta')
  <script type="text/javascript" src="[% URL::to('/') %]/js/excanvas.min.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/plot.min.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/plot/jqplot.cursor.min.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/plot/jqplot.dateAxisRenderer.min.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/plot/jqplot.highlighter.min.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/example.js"></script>
  <link rel="stylesheet" type="text/css" href="[% URL::to('/') %]/css/plot.min.css">
@endsection

@section('content')
<div class="plot" id="plot"></div>
<div class="white-block control-block">
  <a id="reset-btn" href="#" class="green-btn">Reset Zoom</a>
  <a id="prev-btn" href="#" class="green-btn">Previous</a>
  <a id="next-btn" href="#" class="green-btn">Next</a>
</div>
@endsection

@include('common.skeleton')