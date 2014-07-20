var Plot = function() {
  this.step = 1;
  this.range = $('#timerange').val();
  this.data = undefined;
  this.plot = undefined;
    
  this.getData = function() {
    var self = this;
    var valueStart = $('#start').val();
    var valueEnd = $('#end').val();
    
    var valueStep = (self.step-1)*self.range;
    
    var StartGraphTime =  +valueStart + valueStep ;
    var FinishGraphTime =  +valueStart + valueStep ;
    
    self.range = 30;
    var finishTime = StartGraphTime + 30000;
    
    var ekgLine = [];
    var datasetECG = [];
    var ekgColor = "#000000";
    
    var pulsesTimes = [];
    var pulsesColor = "#000000";
    
    $.ajax({
      type: 'post',
      url: $('#url').val() + '/ajax/getPlot',
      dataType: 'json',
      data: {
        step: self.step,
        /* range: self.range, */
        range: 30,
        start: valueStart,
        end: valueEnd,
        user_id: $('#user_id').val()
      },
     
      success: function( response ) {
        if(response === undefined) {
          alert("graph.js.Plot.GetData.fail");
          return false;
        }
        
        if ( response.length == 0 ){
          ekgColor = '#0000FF';
          pulsesColor = '#0000FF';
          response = 
          [
            [1196463600000, 1000], [1196550000000, 1000], [1196636400000, 1000], [1196722800000, 1000], 
            [1196809200000, 1000], [1196895600000, 1000], [1196982000000, 1000], [1197068400000, 1000], 
            [1197154800000, 1000], [1197241200000, 1000], [1197327600000, 1000], [1197414000000, 1000], 
            [1197500400000, 1000], [1197586800000, 1000], [1197673200000, 1000], [1197759600000, 1000], 
            [1197846000000, 1000], [1197932400000, 1000], [1198018800000, 1000], [1198105200000, 1000], 
            [1198191600000, 1000], [1198278000000, 1000], [1198364400000, 1000], [1198450800000, 1000], 
            [1198537200000, 1000], [1198623600000, 1000], [1198710000000, 1000], [1198796400000, 1000], 
            [1198882800000, 1000], [1198969200000, 1000], [1199055600000, 1000], [1199142000000, 1000], 
            [1199228400000, 1000], [1199314800000, 1000], [1199401200000, 1000], [1199487600000, 1000], 
            [1199574000000, 1000], [1199660400000, 1000], [1199746800000, 1000], [1199833200000, 1000], 
            [1199919600000, 1000], [1200006000000, 1000], [1200092400000, 1000], [1200178800000, 1000], 
            [1200265200000, 1000], [1200351600000, 1000], [1200438000000, 1000], [1200524400000, 1000], 
            [1200610800000, 1000], [1200697200000, 1000], [1200783600000, 1000], [1200870000000, 1000], 
            [1200956400000, 1000], [1201042800000, 1000], [1201129200000, 1000], [1201215600000, 1000], 
            [1201302000000, 1000], [1201388400000, 1000], [1201474800000, 1000], [1201561200000, 1000], 
            [1201647600000, 1000], [1201734000000, 1000], [1201820400000, 1000], [1201906800000, 1000], 
            [1201993200000, 1000], [1202079600000, 1000], [1202166000000, 1000], [1202252400000, 1000], 
            [1202338800000, 1000], [1202425200000, 1000], [1202511600000, 1000], [1202598000000, 1000], 
            [1202684400000, 1000], [1202770800000, 1000], [1202857200000, 1000], [1202943600000, 1000], 
            [1203030000000, 1000], [1203116400000, 1000], [1203202800000, 1000], [1203289200000, 1000], 
            [1203375600000, 1000], [1203462000000, 1000], [1203548400000, 1000], [1203634800000, 1000], 
            [1203721200000, 1000], [1203807600000, 1000], [1203894000000, 1000]];
        }
      
        var verticalLineColor = '#000000';
        var pulseColor = '#000000';
  
        var showResponse = false;
        
        var startTimeGraph = 0; 
        /* time correction for graph */
        for ( var i = 0; i < response.length; i++) {
          
          var timeCorrection = response[i][0] + (60 * 60 * 1000)*2;
          response[i][0] = timeCorrection;
  
          if ( i == 0 ){
            startTimeGraph = response[i][0];
          }
          
          if ( response[i][1] == 0 ){
            pulsesTimes.push(response[i][0]);
          }else{
            ekgLine.push(response[i]);
          }
        }
        var tmp = (60 * 60 )*2;
        
        valueStart = startTimeGraph;
        valueEnd =  +(+valueStart + 30000);
              
        var count = 0;
        var time =  valueStart;
        
        for ( var i = valueStart; i < valueEnd; i=i+40){
          count++;
          
          var line = [];
          line.push( [time, 2500  ]  );
          line.push( [time, 7000 ]  );
          if (count%5 ==0 ){
            /* datasetECG.push( {data:line, color:'#CA8907'} ); */
          }else{
            datasetECG.push( {data:line, color:'#FFAB00'} );
          }
          time+=40;
        }
        
        count = 0;
        for ( var i = 2500; i <= 7000; i=i+150){
          count++;
          
          var line = [];
          line.push( [valueStart, i  ]  );
          line.push( [valueEnd, i ]  );
          if (count%5 ==0 ){
            /* datasetECG.push( {data:line, color:'#CA8907'} ); */
          }else{
            datasetECG.push( {data:line, color:'#FFAB00'} );
          }
        }
        
        /* dark line */
        count = 0;
        time = valueStart;
        for ( var i = valueStart; i < valueEnd; i=i+40){
          count++;
          
          var line = [];
          line.push( [time, 2500  ]  );
          line.push( [time, 7000 ]  );
          if (count%5 ==0 ){
            datasetECG.push( {data:line, color:'#CA8907', lines:{show:true, lineWidth:5} } );
          }else{
            /* datasetECG.push( {data:line, color:'#FFAB00'} ); */
          }
          time+=40;
        }
        
        count = 0;
        for ( var i = 2500; i <= 7000; i=i+150){
          count++;
          
          var line = [];
          line.push( [valueStart, i  ]  );
          line.push( [valueEnd, i ]  );
          if (count%5 ==0 ){
            datasetECG.push( {data:line, color:'#CA8907', lines:{show:true, lineWidth:5} } );
          }else{
            /* datasetECG.push( {data:line, color:'#FFAB00'} ); */
          }
        }
  
        datasetECG.push({data:ekgLine, color:ekgColor, lines:{show:true, lineWidth:5} });

        var pulsesLine = [];
        var pulsesLineUnder = [];
        var pulsesLineOver = [];
    
        var interval = +StartGraphTime;
      
        var datasetPulse = [];
        
        showResponse = true;
        if ( pulsesTimes.length == 0 ){
          for ( var i = 0; i< 3; i++ ){
            interval += 10000;
          }
        }else{
          for ( var i = 0; i< pulsesTimes.length; i++ ){
            if ( i == 0 ){
            }else{
              var actualPulse = Math.floor(60000/( pulsesTimes[i]-pulsesTimes[i-1]) );
              var middlePulse = 0;
              
    
              
              pulsesLine.push( [ pulsesTimes[i]  ,  actualPulse ]  );
              
                        switch( i ){
                case 0:
                  middlePulse = actualPulse;
                  break;
                case 1:
                  middlePulse = (actualPulse+pulsesLine[0][1])/2;
                  break;
                case 2:
                  middlePulse = (actualPulse+pulsesLine[0][1]+pulsesLine[1][1])/3;
                  break;
                case 3:
                  middlePulse = (actualPulse+pulsesLine[0][1]+pulsesLine[1][1]+pulsesLine[2][1])/4;
                  break;
                default:
                  middlePulse = (actualPulse
                      +pulsesLine[i-1][1]
                      +pulsesLine[i-2][1]
                      +pulsesLine[i-3][1]
                      +pulsesLine[i-4][1]
                    )/5;
                  break;
              }
              
              pulsesLineUnder.push( [ pulsesTimes[i]  ,  middlePulse - 10 ] );
              pulsesLineOver.push( [ pulsesTimes[i]  ,  middlePulse + 10 ] );
              
              var line = [];
              line.push( [pulsesTimes[i], 20  ]  );
              line.push( [pulsesTimes[i], actualPulse ]  );
              datasetPulse.push( {data:line, color:verticalLineColor} );
            }
          }
        }
        datasetPulse.push( {data:pulsesLine, color:pulseColor } );
        datasetPulse.push( {data:pulsesLineUnder, color:'#FF00FF' } );
        datasetPulse.push( {data:pulsesLineOver, color:'#FF00FF' } );
    
      
        function weekendAreas(axes) {
          var markings = [], d = new Date(axes.xaxis.min);
          /* go to the first Saturday */
          d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
          d.setUTCSeconds(0);
          d.setUTCMinutes(0);
          d.setUTCHours(0);
    
          var i = d.getTime();
    
          // when we don't set yaxis, the rectangle automatically
          // extends to infinity upwards and downwards
    
          do {
            markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
            i += 7 * 24 * 60 * 60 * 1000;
          } while (i < axes.xaxis.max);
    
          return markings;
        }
          
          
        var options = {
          xaxis: {
            mode: 'time',
            timeformat: "%d-%m-%Y %H:%M:%S"
             
          },
          yaxis: {
            min: 2500,
            max: 7000
             
          },
          selection: {
            mode: "x"
          },
          grid: {
            markings: weekendAreas,
            backgroundColor: "#F0E68C" ,
            hoverable: true
          }
        };
        var plot = $.plot('#placeholder', datasetECG, options);
        
        var overview = $.plot("#overview", datasetPulse, {
          series: {
            lines: {
              show: true,
              lineWidth: 1
            },
            shadowSize: 0
          },
          xaxis: {
            //ticks: [],
            mode: "time",
            timeformat: "%d-%m-%Y %H:%M:%S"
          },
          yaxis: {
            //ticks: [],
            min: 20,
            //autoscaleMargin: .01//autoscaleMargin: 0.1
            tickSize:20
          },
          selection: {
            mode: "x"
          }
        });
   
   
        $("#placeholder").bind("plotselected", function (event, ranges) {

        $.each(plot.getXAxes(), function(_, axis) {
          var opts = axis.options;
          opts.min = ranges.xaxis.from;
          opts.max = ranges.xaxis.to;
        });
        plot.setupGrid();
        plot.draw();
        plot.clearSelection();

      // don't fire event on the overview to prevent eternal loop
      overview.setSelection(ranges, true);
    });
      $("#overview").bind("plotselected", function (event, ranges) {
        plot.setSelection(ranges);
      });
      // Add the Flot version string to the footer
      $("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
      
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
    
  function checkTime(i) {
    if (i<10) {
      i="0" + i;
    }
    return i;
  }

function getHumanDateTime( time ){
  var t = new Date(time);
  return checkTime(t.getDay())+'.'+checkTime(t.getMonth())+'.'+checkTime(t.getFullYear())
        +'\n\r '+checkTime(t.getHours())+':'+checkTime(t.getMinutes())+':'+checkTime(t.getSeconds())
}

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