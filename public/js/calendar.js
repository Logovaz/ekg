var Calendar = function() {
  this.month = $('#month').val();
  this.year = $('#year').val();
  
  this.ObjectLength = function ( object ) {
    var length = 0;
    for( var key in object ) {
      if( object.hasOwnProperty(key) ) {
          ++length;
      }
    }
    return length;
  };
  
  this.drawElement = function( weekNum, days ) {
    var tr = document.createElement('tr');
    tr.setAttribute('id', 'week-' + weekNum);
    for(var i = 0; i < days.length; i++) {
      var td = document.createElement('td');
      td.setAttribute('id', 'day-' + days[i].n);
      if(days[i].n != 0) {
        var dayNumber = document.createElement('span');
        dayNumber.style.display = 'block';
        dayNumber.innerHTML = days[i].n;
        $(td).append(dayNumber);
        if(this.ObjectLength(days[i]) > 1) {
          for (var j = 0; j < this.ObjectLength(days[i]) - 1; j++) {
            var image = document.createElement('div');
            image.className += 'heart';
            $(image).insertAfter($(dayNumber));
            var currentDay = days[i][j];
            if(parseInt(currentDay.endDay) - parseInt(currentDay.startDay) > 0) {
              image.setAttribute('title', 'Start: ' + currentDay.start.slice(-8));
              $(image).tooltip({
                position: {
                  my: 'right',
                  at: 'top-20'
                }
              });
            } else {
              image.setAttribute('title', 'Start: ' + currentDay.start.slice(-8) + ' - End: ' + currentDay.end.slice(-8));
              $(image).tooltip({
                position: {
                  my: 'center',
                  at: 'top-20'
                }
              });
            }
            
            (function (currentDay) {
              $(image).mouseover( function( event ) {
                for (var i = currentDay.startDay; i <= currentDay.endDay; i++) {
                  $('#day-' + i).addClass('highlightGreen');
                }
                if (parseInt(currentDay.endDay) - parseInt(currentDay.startDay) > 0) {
                  var tooltip = document.getElementById('day-' + currentDay.endDay);
                  if (tooltip.getAttribute('title') == null) {
                    tooltip.setAttribute('title', 'End: ' + currentDay.end.slice(-8));
                    $(tooltip).tooltip({
                      position: {
                        my: 'center',
                        at: 'top+40'
                      }
                    });
                  }
                  $(tooltip).mouseenter();
                }
              });
              
              $(image).mouseout( function( event ) {
                for (var i = currentDay.startDay; i <= currentDay.endDay; i++) {
                  $('#day-' + i).removeClass('highlightGreen');
                }
                var tooltip = document.getElementById('day-' + currentDay.endDay);
                $(tooltip).mouseleave();
              });
            }) (currentDay);
          }
        }
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
        year: self.year,
        user_id: $('#user_id').val()
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