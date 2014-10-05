var Plot = function() {
  /*************** DEFINES ***************/
  this.step = 1;
  this.stepEcg = 0;
  this.data = undefined;
  this.recordStart = $('#start').val();
  this.recordEnd = $('#end').val();
  this.range = $('#timerange').val();

  // Ecg graph settings
  this.graphECG = undefined;
  this.startGraph = 0;
  this.endGraph = 0;
  this.topVoltageBorder = 9000;
  this.bottomVoltageBorder = 2500;
  this.voltageZoom = $('input:radio[name=voltage_group]:checked').val();
  this.voltageGraphSize = 6500;

  // Pulse graph settings
  this.graphPulse = undefined;
  this.averagePulsePerGraph = 0;
  this.optimalHumanPulse = 0;
  this.actualPulsePerGraph = 0;
  this.pulsesGreedTop = 120;
  this.pulsesGreedBottom = 20;

  //Other settings
  this.checkBoxFilter = false;
  this.checkBoxGraphView = true;
  
  this.redLines = {
    top: {
      type: 'horizontal',
      y: undefined,
      topX: undefined,
      bottomX: undefined,
      limit: undefined
    },
    bottom: {
      type: 'horizontal',
      y: undefined,
      topX: undefined,
      bottomX: undefined,
      limit: undefined
    },
    left: {
      type: 'vertical',
      x: undefined,
      topY: undefined,
      bottomY: undefined,
      limit: undefined
    },
    right: {
      type: 'vertical',
      x: undefined,
      topY: undefined,
      bottomY: undefined,
      limit: undefined
    }
  };

  this.options = {
    verticalLineColor: '#000000',
    pulseColor: '#000000',
    ecgColor: '#000000',
    pulsesColor: '#000000',
    timeCorrection: (60 * 60 * 1000) * 2
  };

  this.arrays = {
    pulsesTimes: [],
    pulsesLine: [],
    pulsesLineUnder: [],
    pulsesLineOver: [],
    ecgLine: [],
    datasetECG: [],
    datasetPulse:[]
  };

  /************** FUNCTIONS ***************/
  this.resetData = function() {
    var self = this;

    this.options.ecgColor = '#000000';
    this.range = 30;
    this.actualPulsePerGraph = 0;
    this.startGraph = parseInt((+self.recordEnd + ((self.step - 1) * self.range)) * 1000);
    this.endGraph = parseInt(self.startGraph) + 30000;

    $.each(this.arrays, function( index, value ) {
      self.arrays[index] = [];
    });
  };
  
  this.initRedLines = function() {
    var ecg = this.arrays.datasetECG;
    
    var length = this.arrays.datasetECG.length;
    var max = ecg[length - 2].data.length - 1;
    
    if (max < 1) return false;
    
    var leftData = ecg[length - 2].data[0][0];
    if (typeof leftData === undefined) return false;
    
    var rightData = ecg[length - 2].data[max][0];
    if (typeof rightData === undefined) return false;
    
    this.redLines.left.x = leftData;
    this.redLines.left.topY = this.topVoltageBorder;
    this.redLines.left.bottomY = this.bottomVoltageBorder;
    this.redLines.left.limit = leftData;
    
    this.redLines.right.x = rightData;
    this.redLines.right.topY = this.topVoltageBorder;
    this.redLines.right.bottomY = this.bottomVoltageBorder;
    this.redLines.right.limit = rightData;
    
    this.redLines.top.y = this.topVoltageBorder;
    this.redLines.top.topX = leftData;
    this.redLines.top.bottomX = rightData;
    this.redLines.top.limit = this.topVoltageBorder;
    
    this.redLines.bottom.y = this.bottomVoltageBorder;
    this.redLines.bottom.topX = leftData;
    this.redLines.bottom.bottomX = rightData;
    this.redLines.bottom.limit = this.bottomVoltageBorder;
    
    this.arrays.datasetECG.push({
      data: [
        [this.redLines.left.x, this.redLines.left.topY],
        [this.redLines.left.x, this.redLines.left.bottomY]
      ],
      color: 'red', shadowSize: 0, lines: {show: true, lineWidth: 1}} 
    );
    
    this.arrays.datasetECG.push({
      data: [
        [this.redLines.right.x, this.redLines.right.topY],
        [this.redLines.right.x, this.redLines.right.bottomY]
      ],
      color: 'red', shadowSize: 0, lines: {show: true, lineWidth: 1}} 
    );
    
    this.arrays.datasetECG.push({
      data: [
        [this.redLines.top.topX, this.redLines.top.y],
        [this.redLines.top.bottomX, this.redLines.top.y]
      ],
      color: 'red', shadowSize: 0, lines: {show: true, lineWidth: 1}} 
    );
    
    this.arrays.datasetECG.push({
      data: [
        [this.redLines.bottom.topX, this.redLines.bottom.y],
        [this.redLines.bottom.bottomX, this.redLines.bottom.y]
      ],
      color: 'red', shadowSize: 0, lines: {show: true, lineWidth: 1}} 
    );
    
  };
  
  this.moveRedLine = function(linePosition, direction) {
    switch (linePosition) {
      case 'left': {
        var line = this.redLines.left;
        var lowLimit = this.redLines.left.limit;
        var highLimit = this.redLines.right.x;
        break;
      }
      case 'right': {
        var line = this.redLines.right;
        var lowLimit = this.redLines.left.x;
        var highLimit = this.redLines.right.limit;
        break;
      }
      case 'top': {
        var line = this.redLines.top;
        var lowLimit = this.redLines.bottom.y;
        var highLimit = this.redLines.top.limit;
        break;
      }
      case 'bottom': {
        var line = this.redLines.bottom;
        var lowLimit = this.redLines.bottom.limit;
        var highLimit = this.redLines.top.y;
        break;
      }
    }

    switch (direction) {
      case 'right': {
        if (line.x >= lowLimit && line.x <= highLimit) {
          line.x += 10;
        } else {
          line.x -= 10;
        }
        break;
      }
      case 'left': {
        if (line.x >= lowLimit && line.x <= highLimit) {
          line.x -= 10;
        } else {
          line.x += 10;
        }
        break;
      }
      case 'up': {
        if (line.y >= lowLimit && line.y <= highLimit) {
          line.y += 10;
        } else {
          line.y -= 10;
        }
        break;
      }
      case 'down': {
        if (line.y >= lowLimit && line.y <= highLimit) {
          line.y -= 10;
        } else {
          line.y += 10;
        }
        break;
      }
    }

    var length = this.arrays.datasetECG.length;
    
    switch (linePosition) {
      case 'left': {
        this.redLines.left.x = line.x;
        this.arrays.datasetECG[length - 4].length = 0;
        this.arrays.datasetECG[length - 4] = {
          data: [
            [this.redLines.left.x, this.redLines.left.topY],
            [this.redLines.left.x, this.redLines.left.bottomY]
          ],
          color: 'red', shadowSize: 0, lines: {show: true, lineWidth: 1}
        };
        break;
      }
      case 'right': {
        this.redLines.right.x = line.x;
        this.arrays.datasetECG[length - 3].length = 0;
        this.arrays.datasetECG[length - 3] = {
          data: [
            [this.redLines.right.x, this.redLines.left.topY],
            [this.redLines.right.x, this.redLines.left.bottomY]
          ],
          color: 'red', shadowSize: 0, lines: {show: true, lineWidth: 1}
        };
        break;
      }
      case 'top': {
        this.redLines.top.y = line.y;
        this.arrays.datasetECG[length - 2].length = 0;
        this.arrays.datasetECG[length - 2] = {
          data: [
            [this.redLines.top.topX, this.redLines.top.y],
            [this.redLines.top.bottomX, this.redLines.top.y]
          ],
          color: 'red', shadowSize: 0, lines: {show: true, lineWidth: 1}
        };
        break;
      }
      case 'bottom': {
        this.redLines.bottom.y = line.y;
        this.arrays.datasetECG[length - 2].length = 0;
        this.arrays.datasetECG[length - 2] = {
          data: [
            [this.redLines.bottom.topX, this.redLines.bottom.y],
            [this.redLines.bottom.bottomX, this.redLines.bottom.y]
          ],
          color: 'red', shadowSize: 0, lines: {show: true, lineWidth: 1}
        };
        break;
      }
    }

    var optionsECG = {
        xaxis: {
          mode: 'time',
          timeformat: '%d-%m-%Y %H:%M:%S'
        },  
        yaxis: {
          min: this.bottomVoltageBorder,
          max: this.topVoltageBorder
        },
        selection: {
          mode: 'x'
        },
        grid: {
          markings: this.weekendAreas,
          backgroundColor: '#F0E68C' ,
          hoverable: true
        }
      };
      
      this.graphECG = $.plot('#placeholder', this.arrays.datasetECG, optionsECG);
  };

  this.getData = function() {
    var self = this;
    self.resetData();

    $.ajax({
      type: 'post',
      url: $('#url').val() + '/ajax/getPlot',
      dataType: 'json',
      data: {
        step: self.step,
        range: 5,
        start: self.recordStart,
        end: self.recordEnd,
        user_id: $('#user_id').val()
      },
      success: function( response ) {
          var optionsECG = {
              xaxis: {
                  mode: 'time',
                  timeformat: '%d-%m-%Y %H:%M:%S'
              },
              yaxis: {
                  min: self.bottomVoltageBorder,
                  max: self.topVoltageBorder
              },
              selection: {
                  mode: 'x'
              },
              grid: {
                  markings: self.weekendAreas,
                  backgroundColor: '#F0E68C' ,
                  hoverable: true
              }
          };

          self.graphECG = $.plot('#placeholder', response.ecg, optionsECG);

          self.graphPulse = $.plot('#overview', response.pulse, optionsPulse);

          $('#placeholder').bind('plotselected', function (event, ranges) {
              /* Do the zooming */
              $.each(self.graphECG.getXAxes(), function(_, axis) {
                  var opts = axis.options;
                  opts.min = ranges.xaxis.from;
                  opts.max = ranges.xaxis.to;
              });
              self.graphECG.setupGrid();
              self.graphECG.draw();
              self.graphECG.clearSelection();

              self.graphPulse.setSelection(ranges, true);
          });

          $('#overview').bind('plotselected', function (event, ranges) {
              self.graphECG.setSelection(ranges);
          });


          return;


        if (response === undefined) {
          alert('graph.js.Plot.GetData.fail');
          return false;
        }
        
        /* Make empty blue line */ 
        if (response.length == 0){
          self.options.ecgColor = '#0000FF';
          self.options.pulsesColor = '#0000FF';
          for (var i = 0; i <= 30; i++){
            response.push([(self.startGraph + i * 1000), 5000]);
          }
        }

        switch(parseInt( self.voltageZoom )) {
          case  5:
            self.topVoltageBorder = parseInt(self.bottomVoltageBorder+self.voltageGraphSize * 2);
            break;
          case  15:
            self.topVoltageBorder = parseInt(self.bottomVoltageBorder+self.voltageGraphSize / 2);
            break;
          default:
            self.topVoltageBorder = parseInt(self.bottomVoltageBorder+self.voltageGraphSize);
            break;
        }

        /* Time correction for graph */
        self.startGraph = parseInt(self.startGraph + self.options.timeCorrection);
        self.endGraph = parseInt(self.endGraph + self.options.timeCorrection);

        self.makeTimeCorrection( response );
        self.makeGraphNetwork();

        /* Pulses */
        var timeTmp = +self.startGraph;

        if (self.arrays.pulsesTimes.length == 0) {
          self.options.pulseColor = '#0000FF';
          for (var i = 0; i <= 30; i++) {
            self.arrays.pulsesLine.push( [timeTmp, 50] );
            timeTmp += 1000;
          }
        } else {
          for (var i = 1; i < self.arrays.pulsesTimes.length; i++) {
            var actualPulse = Math.floor( 60000 / (self.arrays.pulsesTimes[i] - self.arrays.pulsesTimes[i - 1]));
            self.arrays.pulsesLine.push( [self.arrays.pulsesTimes[i], actualPulse] );
            var line = [];
            line.push( [self.arrays.pulsesTimes[i], self.pulsesGreedBottom] );
            line.push( [self.arrays.pulsesTimes[i], actualPulse] );
            self.arrays.datasetPulse.push( {data: line, color: self.options.verticalLineColor} );
          }
        }

        var pulseCount = 0;
        var pulseRand = [];
        
        for (var i = 0; i < 250; i++) {
          pulseRand.push( 0 );
        }

        for (var i = 0; i < self.arrays.pulsesLine.length; i++){
          if (self.arrays.pulsesLine[i][1] < 250) {
            pulseRand[ self.arrays.pulsesLine[i][1] ]++;
          } else {
            pulseRand[ 249 ]++;
          }
        }

        var pulseCountStr = '';

        for (var i = 0; i < 250; i++) {
          pulseCountStr += (pulseRand[i] + '-');
        }

        var maxValue = pulseRand[0];
        
        for (var i = 0; i < 130; i++) {
          if (pulseRand[i] > maxValue) {
            maxValue = pulseRand[i];
            self.optimalHumanPulse = i;
          }
        }

        var filterdLine = [];

        if (self.checkBoxFilter) {
          for (var i = 0; i < self.arrays.pulsesLine.length; i++) {
            if (self.arrays.pulsesLine[i][1] > self.optimalHumanPulse * 1.66 || self.arrays.pulsesLine[i][1] < 30) {
              filterdLine.push( [self.arrays.pulsesLine[i][0], self.optimalHumanPulse] );
            } else {
              filterdLine.push( [self.arrays.pulsesLine[i][0], self.arrays.pulsesLine[i][1]] );
            }
          }
        } else {
          filterdLine = self.arrays.pulsesLine;
        }

        for (var i = filterdLine.length - 1; i >= 0; i--) {
          self.actualPulsePerGraph += filterdLine[i][1];
          pulseCount++;
          if (pulseCount >= 15) {
            i = -1;
          }
        }
        self.averagePulsePerGraph = +Math.floor(self.actualPulsePerGraph / pulseCount);

        for (var i = 0; i < filterdLine.length; i++) {
          var middlePulse = 0;
          switch (i) {
            case 0:
              middlePulse = filterdLine[i][1];
              break;
            case 1:
              middlePulse = (filterdLine[i][1] + filterdLine[0][1])/2;
              break;
            case 2:
              middlePulse = (filterdLine[i][1] + filterdLine[0][1] + filterdLine[1][1]) / 3;
              break;
            case 3:
              middlePulse = (filterdLine[i][1] + filterdLine[0][1] + filterdLine[1][1] + filterdLine[2][1]) / 4;
              break;
            default:
              var sum = 0;
              for (var y = 0; y <= 4; y++) {
                sum += filterdLine[i - y][1];
              }
              middlePulse = sum / 5;
              break;
          }

          self.arrays.pulsesLineUnder.push( [filterdLine[i][0], middlePulse - 10] );
          self.arrays.pulsesLineOver.push( [filterdLine[i][0],  middlePulse + 10] );
        }

        for (var i = self.startGraph; i <= self.endGraph; i = i + 30000) {
          var xTimeLines = [];
          xTimeLines.push( [i, self.pulsesGreedTop] );
          xTimeLines.push( [i, self.pulsesGreedBottom] );
          self.arrays.datasetPulse.push( {data: xTimeLines, color: self.options.pulseColor} );
        }
      
        self.arrays.datasetPulse.push( {data: self.arrays.pulsesLine, color: '#ffa500', shadowSize: 0, lines: {show: true, lineWidth: 1}} );
        self.arrays.datasetPulse.push( {data: filterdLine, color:self.options.pulseColor, lines: {show: true, lineWidth: 1}} );
    
        self.arrays.datasetPulse.push( {data: self.arrays.pulsesLineUnder, color: '#FF00FF', shadowSize: 0} );
        self.arrays.datasetPulse.push( {data: self.arrays.pulsesLineOver, color: '#FF00FF', shadowSize: 0} );
    
        self.arrays.datasetPulse.push( {data: self.ecgTimeMarker, color: '#00FF00', shadowSize: 0, lines: {show: true, lineWidth: 1}} );

        self.initRedLines(response);

        var optionsECG = {
          xaxis: {
            mode: 'time',
            timeformat: '%d-%m-%Y %H:%M:%S'
          },  
          yaxis: {
            min: self.bottomVoltageBorder,
            max: self.topVoltageBorder
          },
          selection: {
            mode: 'x'
          },
          grid: {
            markings: self.weekendAreas,
            backgroundColor: '#F0E68C' ,
            hoverable: true
          }
        };
        
        self.graphECG = $.plot('#placeholder', self.arrays.datasetECG, optionsECG);

        var optionsPulse = {
          series: {
            lines: {
              show: true,
              lineWidth: 1
            },
            shadowSize: 0
          },
          xaxis: {
            mode: 'time',
            timeformat: '%d-%m-%Y %H:%M:%S'
          },
          yaxis: {
            min: 20,
            max: 120,        //autoscaleMargin: .01//autoscaleMargin: 0.1
            tickSize:20
          },
          selection: {
            mode: 'x'
          }
        }

        self.graphPulse = $.plot('#overview', self.arrays.datasetPulse, optionsPulse);

        $('#placeholder').bind('plotselected', function (event, ranges) {
          /* Do the zooming */
          $.each(self.graphECG.getXAxes(), function(_, axis) {
            var opts = axis.options;
            opts.min = ranges.xaxis.from;
            opts.max = ranges.xaxis.to;
          });
          self.graphECG.setupGrid();
          self.graphECG.draw();
          self.graphECG.clearSelection();

            self.graphPulse.setSelection(ranges, true);
        });
        
        $('#overview').bind('plotselected', function (event, ranges) {
          self.graphECG.setSelection(ranges);
        });

        self.setStatisticValues();
      }//end of success
    }); // end of ajax request
  };// end of getData

  this.weekendAreas = function(axes) {
    var markings = [], d = new Date(axes.xaxis.min);
    // go to the first Saturday
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
  };

  this.makeTimeCorrection = function(response) {
    for (var i = 0; i < response.length; i++) {
      response[i][0] = parseInt(response[i][0]) + this.options.timeCorrection;
      if (response[i][1] == 0) {
        this.arrays.pulsesTimes.push(response[i][0]);
      } else {
        this.arrays.ecgLine.push(response[i]);
      }
    }
  }

  this.makeGraphNetwork = function() {
    var partEcg = [];
    this.ecgTimeMarker = [];
    if (this.checkBoxGraphView) {  // part of ecg line 5 s
      var lineTimeStart = this.endGraph + (5000 * (this.stepEcg - 1));
      var lineTimeEnd = this.endGraph + (5000 * (this.stepEcg));
      for (var i = 0; i < this.arrays.ecgLine.length; i++) {
        if (lineTimeStart <= this.arrays.ecgLine[i][0] && this.arrays.ecgLine[i][0] <= lineTimeEnd) {
          partEcg.push( this.arrays.ecgLine[i] );
        }
      }
    } else { //full line 30s
      partEcg = this.arrays.ecgLine;
    }

    this.ecgTimeMarker.push( [lineTimeStart, 30] );
    this.ecgTimeMarker.push( [lineTimeEnd, 30] );

    var count = 0;
    var calibrationPlace = 600;
    var time = lineTimeStart - calibrationPlace;

    for ( var i = this.bottomVoltageBorder; i <= this.topVoltageBorder; i = i + 150){
      count++;
      var line = [];
      line.push( [lineTimeStart - calibrationPlace, i] );
      line.push( [lineTimeEnd, i] );
      if ((count - 1) % 5 == 0) {
        this.arrays.datasetECG.push( {data: line, color: '#CA8907', shadowSize: 0, lines: {show: true, lineWidth: 1}} );
      } else {
        this.arrays.datasetECG.push( {data: line, color: '#FFAB00', shadowSize: 0, lines: {show: true, lineWidth: 1}} );
      }
    }

    // dark line **************************************************************
    count = 0;
    time = lineTimeStart - calibrationPlace;
    for ( var i = lineTimeStart; i < lineTimeEnd + calibrationPlace; i = i + 40) {
      count++;
      var line = [];
      line.push( [time, this.bottomVoltageBorder] );
      line.push( [time, this.topVoltageBorder] );
      if ((count - 1) % 5 == 0) {
        this.arrays.datasetECG.push( {data: line, color: '#CA8907', shadowSize: 0, lines: {show: true, lineWidth: 1}} );
      } else {
        this.arrays.datasetECG.push( {data: line, color: '#FFAB00', shadowSize: 0, lines: {show: true, lineWidth: 1}} );
      }
      time += 40;
    }

    this.arrays.datasetECG.push( {data: partEcg, color: this.options.ecgColor, shadowSize: 0, lines: {show: true, lineWidth: 2}} );
    this.MakeCalibrationLine( lineTimeStart - calibrationPlace );
  }

  this.setStatisticValues = function() {
    $('#filter').attr( 'checked', this.checkBoxFilter );
    $('#filter_seconds').attr( 'checked', this.checkBoxGraphView );

    $('input:radio[name=voltage_group]').filter('[value="' + this.voltageZoom + '"]').attr( 'checked', true );
    
    if (this.checkBoxGraphView) {
      $('#ecg-buttons').show();
    } else {
      $('#ecg-buttons').hide();
    }

    $('#hr').text( this.averagePulsePerGraph + ' BPM ');
    $('#ar').text( this.optimalHumanPulse  + 'BPM');
    
    $('#datetime').val( this.getHumanDateTimeEU( this.startGraph - this.options.timeCorrection ) );
  };

  this.MakeCalibrationLine = function( graphTimeStart ) {
    var calibrationLine = [];

    calibrationLine.push( [graphTimeStart + 100, this.bottomVoltageBorder + 750] );
    calibrationLine.push( [graphTimeStart + 200, this.bottomVoltageBorder + 750] );
    calibrationLine.push( [graphTimeStart + 200, this.bottomVoltageBorder + 2250] );
    calibrationLine.push( [graphTimeStart + 400, this.bottomVoltageBorder + 2250] );
    calibrationLine.push( [graphTimeStart + 400, this.bottomVoltageBorder + 750] );
    calibrationLine.push( [graphTimeStart + 500, this.bottomVoltageBorder + 750] );

    this.arrays.datasetECG.push( {data: calibrationLine, color: this.options.ecgColor, lines: {show: true, lineWidth: 2}} );
  };

  this.increaseStep = function() {
    this.step++;
    this.data = [];
  };

  this.decreaseStep = function() {
    this.step--;
    this.data = [];
    return true;
  };

  this.resetZoom = function() {
    this.plot.resetZoom();
  };

  this.selectStart = function(){
    this.step = -(Math.floor((this.recordEnd - this.recordStart) / this.range));
  };

  this.selectEnd = function(){
    var self = this;
    var stepTmp = 0;
    $.ajax({
      type: 'post',
      url: $('#url').val() + '/ajax/getLastTime',
      dataType: 'json',
      data: {
        step: self.step,
        range: 30,
        start: self.recordStart,
        end: self.recordEnd,
        user_id: $('#user_id').val()
      },

      success: function( response ) {
        if(response === undefined) {
          alert('graph.js.Plot.getLastTime.fail');
          return false;
        }

        var unixTime = new Date (response[0]).getTime() + 7200000; //ms
        rangeTmp = 30000; //ms
        stepTmp = +(Math.floor((unixTime - (self.recordEnd * 1000)) / rangeTmp) + 1);
        self.step = stepTmp;
        self.getData();
      }
    });
  };

  // Ecg ******************************
  this.increaseStepEcg = function() {
    this.stepEcg++;
    if (this.stepEcg == 1 ) {
      this.increaseStep();
      this.stepEcg = -5;
    }
    this.data = [];
  };

  this.decreaseStepEcg = function() {
    this.stepEcg--;
    if (this.stepEcg % 6 == 0 ) {
      this.decreaseStep();
      this.stepEcg = 0;
    }
    this.data = [];
  };

  this.moveEcgUp = function() {
    this.topVoltageBorder += 150;
    this.bottomVoltageBorder += 150;
  };

  this.moveEcgDown = function() {
    this.topVoltageBorder -= 150;
    this.bottomVoltageBorder -= 150;
  };

  this.checkBoxControl = function(){
    if ($('#filter').prop('checked')) {
      this.checkBoxFilter = true;
    } else {
      this.checkBoxFilter = false;
    }
  }

  this.checkBoxGraphViewControl = function() {
    if ($('#filter_seconds').prop('checked')) {
      this.checkBoxGraphView = true;
    } else {
      this.checkBoxGraphView = false;
    }
  };

  this.setSelectedVoltageZoom = function(valueTmp) {
    this.voltageZoom = valueTmp;
  };

  this.changeDateTime = function() {  
    var newDateTime = $('#datetime').val(); 
    var recordEnd = $('#end').val();
    
    var oldDateTime = newDateTime.split(' ');
    var oldDate = oldDateTime[0];
    var oldTime = oldDateTime[1];
    
    var oldDatas = oldDate.split('.');
    var day = oldDatas[0];
    var month = oldDatas[1];
    var year = oldDatas[2];
    
    var americanDate = year + '-' + month + '-' + day + ' ' + oldTime;
    var unixTime = new Date ( americanDate ).getTime(); //ms
    var step = -(Math.floor( +(recordEnd - (unixTime / 1000)) / this.range));
    this.step = step;
  }

  this.getHumanDateTimeEU = function( time ) {
    var t = new Date(time);
    var value = this.checkTime(t.getDate())
        + '.' + this.checkTime(t.getMonth() + 1) 
        + '.' + this.checkTime(t.getFullYear())
        + ' ' + this.checkTime(t.getHours())
        + ':' + this.checkTime(t.getMinutes())
        + ':' + this.checkTime(t.getSeconds());
    return value;
  }

  this.checkTime = function(i) {
    if (i < 10){
      i = '0' + i;
    }
    return i;
  }
}; //end of class plot

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
  
  $('#to-start-btn').click(function ( event ) {
    event.preventDefault();
    plot.selectStart();
    plot.getData();
  });

  
  $('#prev-btn').click(function ( event ) {
    event.preventDefault();
    if(plot.decreaseStep()) {
      plot.getData();
    }
  });
  
  $('#refresh-btn').click(function ( event ) {
    event.preventDefault();
    plot.getData();
  });
  
  $('#next-btn').click(function ( event ) {
    event.preventDefault();
    plot.increaseStep();
    plot.getData();
  });
  $('#end-btn').click(function ( event ) {
    event.preventDefault();
    plot.selectEnd();
  });
  
  //ECG graph buttons ************************************
  $('#to-start-btn1').click(function ( event ) {
    event.preventDefault();
    plot.selectStart();
    plot.getData();
  });

  
  $('#prev-btn1').click(function ( event ) {
    event.preventDefault();
    plot.decreaseStepEcg();
    plot.getData();
    
  });
  
  $('#refresh-btn1').click(function ( event ) {
    event.preventDefault();
    plot.getData();
  });
  
  $('#next-btn1').click(function ( event ) {
    event.preventDefault();
    plot.increaseStepEcg();
    plot.getData();
  });
  $('#end-btn1').click(function ( event ) {
    event.preventDefault();
    plot.selectEnd();
  });
  
  $('#ekg-top').click(function ( event ) {
    event.preventDefault();
    plot.moveEcgUp();
    plot.getData();
  });
  $('#ekg-down').click(function ( event ) {
    event.preventDefault();
    plot.moveEcgDown();
    plot.getData();
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

  $('#time').click(function ( event ) {
    event.preventDefault();
    plot.changeDateTime();
    plot.getData();
  });

  $('#filter').click(function ( event ) {
    plot.checkBoxControl();
    plot.getData();
    
  });

  $('#filter_seconds').click(function ( event ) {
    plot.checkBoxGraphViewControl();
    plot.getData();
  });

  $('input[name=voltage_group]').click( function ( event ){
    var valueTmp = $('input:radio[name=voltage_group]:checked').val();
    plot.setSelectedVoltageZoom(valueTmp);
    plot.getData();
  });

  //line control
  $('#left-line-control').click(function ( event ) {
    event.preventDefault();
    if ($(event.target).hasClass('inc')) {
      plot.moveRedLine('left', 'right');
    } else {
      plot.moveRedLine('left', 'left'); 
    }
  });
  $('#right-line-control').click(function ( event ) {
    event.preventDefault();
    if ($(event.target).hasClass('inc')) {
      plot.moveRedLine('right', 'right');
    } else {
      plot.moveRedLine('right', 'left'); 
    }
  });
  $('#top-line-control').click(function ( event ) {
    event.preventDefault();
    if ($(event.target).hasClass('inc')) {
      plot.moveRedLine('top', 'up');
    } else {
      plot.moveRedLine('top', 'down'); 
    }
  });
  $('#bottom-line-control').click(function ( event ) {
    event.preventDefault();
    event.preventDefault();
    if ($(event.target).hasClass('inc')) {
      plot.moveRedLine('bottom', 'up');
    } else {
      plot.moveRedLine('bottom', 'down'); 
    }
  });
});