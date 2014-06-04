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
  var calendar = new Calendar();
  calendar.drawCalendar();
  
  $('.mnt').click(function ( event ) {
    calendar.setMonth($(event.target).attr('num'));
  });
  
  $('#prevYear').click(function ( event ) {
    calendar.prevoiusYear();
  });
  
  $('#nextYear').click(function ( event ) {
    calendar.nextYear();
  });
  
});