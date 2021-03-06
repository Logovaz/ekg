var Plot = function() {
  this.step = 1;
  this.data = undefined;
  this.plot = undefined;
  
  this.getData = function() {
    var self = this;
    $.ajax({
      type: 'post',
      url: 'ajax/getPlotExample',
      dataType: 'json',
      data: {
        step: self.step
      },
      success: function( response ) {
        if(response === undefined) {
          return false;
        }
        self.plot = $.jqplot('plot', [ response ], {
          cursor: {
            show: true,
            zoom: true,
            showTooltip: false
          },
          highlighter: {
            show: true,
            sizeAdjust: 10
          },
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
});