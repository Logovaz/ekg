var Plot = function() {
  this.step = 1;
  this.range = $('#timerange').val();
  this.data = undefined;
  this.plot = undefined;
  
  this.getData = function() {
    var self = this;
    $.ajax({
      type: 'post',
      url: $('#url').val() + '/ajax/getPlot',
      dataType: 'json',
      data: {
        step: self.step,
        range: self.range,
        start: $('#start').val(),
        end: $('#end').val(),
        user_id: $('#user_id').val()
      },
      success: function( response ) {
        if(response === undefined) {
          return false;
        }
        self.plot = $.jqplot('plot', [ response ], {
          seriesColors:['#000000'],
          cursor: {
            show: true,
            zoom: false,
            showTooltip: false
          },
          highlighter: {
            show: false,
            sizeAdjust: 10
          },
          axesDefaults: {
            showTicks: false,
            showTickMarks: false       
          },
          seriesDefaults: {
            showMarker: false
          },
          grid: {
            drawGridlines: false
          },
          axes: {
            xaxis: {
              pad: 0,
            },
            yaxis: {
              pad: 0,
              autoscale: false,
              max: 10000,
              min: 700
            }
          }
        });
        self.plot.resetZoom();
      }
    });
  };
  
  this.increaseStep = function() {
    this.step++;
    this.data = [];
  };
  
  this.decreaseStep = function() {
    if(this.step > 1) {
      this.step--;
      this.data = [];
      return true;
    }
    return false;
  };
  
  this.resetZoom = function() {
    this.plot.resetZoom();
  };
};

var Calendar = function() {
  this.month = $('#month').val();
  this.year = $('#year').val();
  
  this.drawElement = function( weekNum, days ) {
    var tr = document.createElement('tr');
    tr.setAttribute('id', 'week-' + weekNum);
    for(var i = 0; i < days.length; i++) {
      var td = document.createElement('td');
      td.setAttribute('id', 'day-' + days[i]);
      if(days[i] != 0) {
        td.innerHTML = days[i];
      }
      tr.appendChild(td);
    }
    document.getElementById('calendar').appendChild(tr);
  };
  
  this.drawCalendar = function() {
    $('#calendar').empty();
    var self = this;
    $.ajax({
      type: 'post',
      url: $('#url').val() + '/ajax/getCalendar',
      dataType: 'json',
      data: {
        month: self.month,
        year: self.year
      },
      success: function ( data ) {
        for(var i = 0; i < data.length; i++) {
          self.drawElement(i, data[i]);
        }
      }
    });
    this.updatePicker();
  };
  
  this.updatePicker = function() {
    var monthButtons = $('.mnt');
    for(var i = 0; i < monthButtons.length; i++) {
      if($(monthButtons[i]).attr('num') != parseInt(this.month)) {
        $(monthButtons[i]).removeClass('month-picker-active');
        $(monthButtons[i]).addClass('month-picker-passive');
      } else {
        $(monthButtons[i]).removeClass('month-picker-passive');
        $(monthButtons[i]).addClass('month-picker-active');
      }
    }
  };
  
  this.setMonth = function(value) {
    if(value == parseInt(this.month)) {
      return false;
    }
    this.month = value;
    this.updatePicker();
    this.drawCalendar();
  };
  
  this.prevoiusYear = function() {
    var targetYear = parseInt($('#curYear').html()) - 1;
    if(targetYear <= $('#min_year').val()) {
      return false;
    }
    this.year = targetYear;
    this.drawCalendar();
    $('#curYear').html(this.year);
  };
  
  this.nextYear = function() {
    var targetYear = parseInt($('#curYear').html()) + 1;
    if(targetYear >= $('#max_year').val()) {
      return false;
    }
    this.year = targetYear;
    this.drawCalendar();
    $('#curYear').html(this.year);
  };
  
};

$(function() {
  var plot = new Plot();
  var calendar = new Calendar();
  calendar.drawCalendar();
  plot.getData();
  
  $('.mnt').click(function ( event ) {
    calendar.setMonth($(event.target).attr('num'));
  });
  
  $('#prevYear').click(function ( event ) {
    calendar.prevoiusYear();
  });
  
  $('#nextYear').click(function ( event ) {
    calendar.nextYear();
  });
  
  $('#reset-btn').click(function ( event ) {
    event.preventDefault();
    plot.resetZoom();
  });
  
  $('#next-btn').click(function ( event ) {
    event.preventDefault();
    plot.increaseStep();
    plot.getData();
  });
  
  $('#prev-btn').click(function ( event ) {
    event.preventDefault();
    if(plot.decreaseStep()) {
      plot.getData();
    }
  });
  $('#timerange').change(function ( event ) {
    switch($( this ).val()) {
      case '5': {
        $('#plot').width(750);
        plot.range = 5;
        plot.getData();
        break;
      };
      case '10': {
        $('#plot').width(1500);
        plot.range = 10;
        plot.getData();
        break;
      };
      case '30': {
        $('#plot').width(1500);
        plot.range = 10;
        plot.getData();
        break;
      }
    }
  });
});