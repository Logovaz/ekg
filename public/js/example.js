var Plot = function() {
  this.step = 1;
  this.range = $('#timerange').val();
  this.data = undefined;
  this.plot = undefined;
  
  this.getData = function() {
    var self = this;
    $.ajax({
      type: 'post',
      url: 'ajax/getPlotExample',
      dataType: 'json',
      data: {
        step: self.step,
        range: self.range
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
  }
};

$(function() {
  var plot = new Plot();
  plot.getData();
  
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