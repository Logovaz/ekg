@section('meta')
  <script type="text/javascript" src="[% URL::to('/') %]/js/excanvas.min.js"></script>
  <!-- <script type="text/javascript" src="[% URL::to('/') %]/js/plot.min.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/plot/jqplot.cursor.min.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/plot/jqplot.dateAxisRenderer.min.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/plot/jqplot.canvasOverlay.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/plot/jqplot.highlighter.min.js"></script> -->
  <script type="text/javascript" src="[% URL::to('/') %]/js/flot/jquery.flot.min.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/flot/jquery.flot.time.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/flot/jquery.flot.selection.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/calendar.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/graph.js"></script>
  <script type="text/javascript" src="[% URL::to('/') %]/js/jquery-ui.min.js"></script>
  <link rel="stylesheet" type="text/css" href="[% URL::to('/') %]/css/jquery-ui.min.css">
  <link rel="stylesheet" type="text/css" href="[% URL::to('/') %]/css/plot.min.css">
@endsection

@section('content')
<div id="data">
  <input type="hidden" id="month" value="[% $date['month'] %]">
  <input type="hidden" id="year" value="[% $date['year'] %]">
  <input type="hidden" id="max_year" value="[% end($years) %]">
  <input type="hidden" id="min_year" value="[% array_shift($years) %]">
  <input type="hidden" id="start" value="[% $start %]">
  <input type="hidden" id="end" value="[% $end %]">
  <input type="hidden" id="url" value="[% URL::to('/') %]">
  <input type="hidden" id="user_id" value="[% $user_id %]">
</div>
<div class="calendar-block centered">
  <table class="all-holder centered">
    <tr>
      <td>
        <table id="calendar" class="calendar centered"></table>
      </td>
      <td>
        <table class="month-picker-table">
          <tr>
            <td>
              <div class="month-picker block mnt" num="1">[% Lang::get('names.january') %]</div>
              <div class="month-picker block mnt" num="2">[% Lang::get('names.february') %]</div>
              <div class="month-picker block mnt" num="3">[% Lang::get('names.march') %]</div>
              <div class="month-picker block mnt" num="4">[% Lang::get('names.april') %]</div>
              <div class="month-picker block mnt" num="5">[% Lang::get('names.may') %]</div>
              <div class="month-picker block mnt" num="6">[% Lang::get('names.june') %]</div>
            </td>
            <td>
              <div class="month-picker block mnt" num="7">[% Lang::get('names.july') %]</div>
              <div class="month-picker block mnt" num="8">[% Lang::get('names.august') %]</div>
              <div class="month-picker block mnt" num="9">[% Lang::get('names.september') %]</div>
              <div class="month-picker block mnt" num="10">[% Lang::get('names.october') %]</div>
              <div class="month-picker block mnt" num="11">[% Lang::get('names.november') %]</div>
              <div class="month-picker block mnt" num="12">[% Lang::get('names.december') %]</div>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <div class="month-picker" id="prevYear">&lt;&lt;</div>
              <div class="month-picker" id="curYear">[% $date['year'] %]</div>
              <div class="month-picker" id="nextYear">&gt;&gt;</div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</div>

<div class="grey-block" style="width: 400px;">
<table class="invisible-table control-table centered" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td class="td-right">
      <span>Left limit:</span>
    </td>
    <td>
      <div id="left-line-control" class="centered">
        <a href="#" class="dec green-btn">Left</a>
        <a href="#" class="inc green-btn">Right</a>
      </div>
    </td>
  </tr>
  <tr>
    <td class="td-right">
      <span>Right limit:</span>
    </td>
    <td>
      <div id="right-line-control" class="centered">
        <a href="#" class="dec green-btn">Left</a>
        <a href="#" class="inc green-btn">Right</a>
      </div>
    </td>
  </tr>
  <tr>
    <td class="td-right">
      <span>Top limit:</span>
    </td>
    <td>
      <div id="top-line-control" class="centered">
        <a href="#" class="dec green-btn">Down</a>
        <a href="#" class="inc green-btn">Up</a>
      </div>
    </td>
  </tr>
  <tr>
    <td class="td-right">
      <span>Bottom limit:</span>
    </td>
    <td>
      <div id="bottom-line-control" class="centered">
        <a href="#" class="dec green-btn">Down</a>
        <a href="#" class="inc green-btn">Up</a>
      </div>
    </td>
  </tr>
</table>
</div>

<!-- div class="grey-block" style="width: 400px;">
<table class="invisible-table control-table centered" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td class="td-right">
      <span>Left limit:</span>
    </td>
    <td>
      <div id="left-line-control" class="centered">
        <a href="#" class="dec green-btn">Left</a>
        <a href="#" class="inc green-btn">Right</a>
      </div>
    </td>
  </tr>
  <tr>
    <td class="td-right">
      <span>Right limit:</span>
    </td>
    <td>
      <div id="right-line-control" class="centered">
        <a href="#" class="dec green-btn">Left</a>
        <a href="#" class="inc green-btn">Right</a>
      </div>
    </td>
  </tr>
  <tr>
    <td class="td-right">
      <span>Top limit:</span>
    </td>
    <td>
      <div id="top-line-control" class="centered">
        <a href="#" class="dec green-btn">Down</a>
        <a href="#" class="inc green-btn">Up</a>
      </div>
    </td>
  </tr>
  <tr>
    <td class="td-right">
      <span>Bottom limit:</span>
    </td>
    <td>
      <div id="bottom-line-control" class="centered">
        <a href="#" class="dec green-btn">Down</a>
        <a href="#" class="inc green-btn">Up</a>
      </div>
    </td>
  </tr>
</table>
</div-->

<div class="white-block control-block">
 
  <!-- input  id='datetime' name="min" value="19.05.2010 05:07:25" class="datepickerTimeField" -->
  <input  id='datetime' name="min" value="[% $datetime %]" class="datepickerTimeField" >
  <input id="time" type="submit" value="Select">
  
  

  <input type="checkbox" id="flt"/><label for="flt">Filter</label>
  <input type="checkbox" id="flt1"/><label for="flt1">5s-30s</label>
    <select style="visibility: hidden" id="timerange">
    <option value="5">5 sec</option>
    <option value="10">10 sec</option>
    <option value="30">30 sec</option>
    <option value="1">1 min</option>
  </select > 
	Voltage
	<input type="radio" name="group2" value="5"> 0,5
	<input type="radio" name="group2" value="10" checked> 1,0
	<input type="radio" name="group2" value="15" > 1,5 

  <div id="ecg-buttons" >
	<div>
	</div>
	<div>
  <a id="to-start-btn1" href="#" class="green-btn">|< </a>
  <a id="prev-btn1" href="#" class="green-btn"><</a>
  <a id="refresh-btn1" href="#" class="green-btn">@</a>
  <a id="next-btn1" href="#" class="green-btn">></a>
  <a id="end-btn1" href="#" class="green-btn">>|</a>
  </div>
    </div>
    <div style="height:20px">
  
	</div>
	
  <div>
	<div>
	</div>
	<div>
  <a id="to-start-btn" href="#" class="green-btn">|<</a>
  <a id="prev-btn" href="#" class="green-btn"><</a>
  <a id="refresh-btn" href="#" class="green-btn">@</a>
  <a id="next-btn" href="#" class="green-btn">></a>
  <a id="end-btn" href="#" class="green-btn">>|</a>
	</div>
  </div>


</div>

<div id="content">
  <div class="demo-container">
    <div id="placeholder" class="demo-placeholder"></div>
  </div>
  <div class="demo-container" style="height:350px;">
    <div id="overview" class="demo-placeholder"></div>
  </div>
</div>
<div class="demo-container">
<table border='1'>
  <tr>
    <td>HR: 120 bpm</td>
    <td>P Dur: 100 ms</td>
    <td>PR int : 272 ms</td>
    <td>QRS Dur: 139 ms</td>
    <td>QT/QTC int: 456/405 ms</td>
  </tr>
  <tr>
    <td>AR: none</td>
    <td>P/QRS/T axis: 155/-13/39 </td>
    <td>RV5/SV1 amp: 0.462/1.319 mV</td>
    <td>RV5/+SV1 amp: 1.781 mV</td>
    <td>RV6/SV2 amp 0.592/1.273 mV</td>
  </tr>
</table>

</div>

<div class="white-block control-block">
  <select id="timerange">
    <option value="5">5 sec</option>
    <option value="10">10 sec</option>
    <option value="30">30 sec</option>
    <option value="1">1 min</option>
  </select>
  <a id="reset-btn" href="#" class="green-btn">Reset Zoom</a>
  <a id="prev-btn" href="#" class="green-btn">Previous</a>
  <a id="next-btn" href="#" class="green-btn">Next</a>
</div>
@endsection

@include('common.skeleton')