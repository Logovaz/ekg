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
        
        var top = response[0][1];
        var bottom = response[response.length - 1][1];
        var left = response[0][0];
        var right = response[response.length - 1][0];
        
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
          },
          canvasOverlay: {
            show: true,
            objects: [
                {horizontalLine: {
                  name: 'top',
                  y: 9500,
                  lineWidth: 2,
                  color: 'red',
                  shadow: false
                }},
                {horizontalLine: {
                  name: 'bottom',
                  y: 1200,
                  lineWidth: 2,
                  color: 'red',
                  shadow: false
                }},
                {verticalLine: {
                  name: 'left',
                  x: left,
                  lineWidth: 2,
                  color: 'red',
                  shadow: false
                }},
                {verticalLine: {
                  name: 'right',
                  x: right,
                  lineWidth: 2,
                  color: 'red',
                  shadow: false
                }},
            ]
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