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
                lineWidth: 1,
                color: 'rgb(255,0,0)',
                shadow: false
              }},
              {horizontalLine: {
                name: 'bottom',
                y: 1200,
                lineWidth: 1,
                color: 'rgb(255,0,0)',
                shadow: false
              }},
              {verticalLine: {
                name: 'left',
                x: left,
                lineWidth: 1,
                color: 'rgb(255,0,0)',
                shadow: false
              }},
              {verticalLine: {
                name: 'right',
                x: right,
                lineWidth: 1,
                color: 'rgb(255,0,0)',
                shadow: false
              }},
            ]
          }
        });
        self.plot.resetZoom();
      }
    });
  };
  
  this.moveVertical = function(line, direction, increement) {
    var overlay = this.plot.plugins.canvasOverlay;
    if(line == 'left') {
      var line = overlay.get('left');
    } else {
      var line = overlay.get('right');
    }
    
    if(direction == 'left') {
      line.options.x -= increement;
    } else {
      line.options.x += increement;
    }
    overlay.draw(this.plot);
  };
  
  this.moveHorizontal = function(line, direction, increement) {
    var overlay = this.plot.plugins.canvasOverlay;
    if(line == 'top') {
      var line = overlay.get('top');
    } else {
      var line = overlay.get('bottom');
    }
    
    if(direction == 'bottom') {
      line.options.y -= increement;
    } else {
      line.options.y += increement;
    }
    overlay.draw(this.plot);
  }
  
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

/**
 * jQuery Plugin rewrited
 */
(function($){
  $.fn.incrementLiner = function(options) {
    
    var settings = {            
      timeout: 50,
      cursor: false
    };
  
    return this.each(function() {
      if (options) {
        $.extend(settings, options);
      }
      
      var $this = $(this);
      
      var dec = $this.find('.dec');
      var inc = $this.find('.inc');
      var iteration = 1;
      var timeout = 50; 
      var isDown = false;     
      updateCursor();
      mousePress(inc, doIncrease);
      mousePress(dec, doDecrease);
      
      function mousePress(obj, func) { 
          focusElement = obj;
          obj.unbind('mousedown');
          obj.unbind('mouseup');
          obj.unbind('mouseleave');
          obj.bind('mousedown', function() {
            isDown = true;            
            setTimeout(func, settings.timeout);
          });
          
          obj.bind('mouseup', function() {              
            isDown = false;
            iteration = 1;
          });
          
          obj.bind('mouseleave', function() {             
            isDown = false;
            iteration = 1;
          });
        } 
      
      function updateCursor(){
        if(settings.cursor){ 
          dec.css('cursor','pointer');
          inc.css('cursor','pointer');
        }
      }
      
      function doIncrease() {
        if (isDown) {
          var increement = getIncrement(iteration);
          switch(settings.line) {
            case 'left' : {
              settings.plot.moveVertical('left', 'right', increement);
              break;
            };
            case 'right' : {
              settings.plot.moveVertical('right', 'right', increement);
              break;
            };
            case 'top' : {
              settings.plot.moveHorizontal('top', 'top', increement);
              break;
            };
            case 'bottom' : {
              settings.plot.moveHorizontal('bottom', 'top', increement);
              break;
            };
          }
          iteration++;
          setTimeout(doIncrease, settings.timeout);
        }
      }
      
      function doDecrease() {
        if (isDown) {
          var increement = getIncrement(iteration);           
          switch(settings.line) {
            case 'left' : {
              settings.plot.moveVertical('left', 'left', increement);
              break;
            };
            case 'right' : {
              settings.plot.moveVertical('right', 'left', increement);
              break;
            };
            case 'top' : {
              settings.plot.moveHorizontal('top', 'bottom', increement);
              break;
            };
            case 'bottom' : {
              settings.plot.moveHorizontal('bottom', 'bottom', increement);
              break;
            };
          }
          iteration++;
          setTimeout(doDecrease, settings.timeout);
        }
      }
      
      function getIncrement(iteration){
        var increement = 1;
        increement = iteration / 100 * 10;
        return  increement;
      }      
    });
  };
})(jQuery);

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
  
  $('#left-line-control').incrementLiner({
    line: 'left',
    plot: plot,
    timeout: 10
  });
  
  $('#right-line-control').incrementLiner({
    line: 'right',
    plot: plot,
    timeout: 10
  });
  
  $('#top-line-control').incrementLiner({
    line: 'top',
    plot: plot,
    timeout: 5
  });
  
  $('#bottom-line-control').incrementLiner({
    line: 'bottom',
    plot: plot,
    timeout: 5
  });
});