@section('meta')
  <script type="text/javascript" src="[% URL::to('/') %]/js/profile.js"></script>
@endsection

@section('content')

<div id="data">
  <input type="hidden" id="month" value="[% $date['month'] %]">
  <input type="hidden" id="year" value="[% $date['year'] %]">
  <input type="hidden" id="max_year" value="[% end($years) %]">
  <input type="hidden" id="min_year" value="[% array_shift($years) %]">
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

@if(!$graphs)
  <div class="white-block messages-block">
    <h3 >
      [% Lang::get('locale.no_graphs') %]
    </h3>
  </div>
@else
  @foreach($graphs as $graph)
    <div class="white-block messages-block">
      <a class="undecorated blue" href="[% URL::to('/') %]/graph/[% $user_id %]/[% strtotime($graph->start) %]_[% strtotime($graph->end) %]" target="blank_">[% $graph->start %] - [% $graph->end %]</a>
    </div>
  @endforeach
@endif
@endsection 

@include('common.skeleton')