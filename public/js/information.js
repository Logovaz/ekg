function getDays() {
  var year = $('#year').val();
  var month = $('#month').val();
  if(year == 'Year') return false;
  $.ajax({
    type: 'post',
    dataType: 'json',
    url: $('#url').val() + '/ajax/getDays',
    data: {
      month: month,
      year: year
    },
    success: function(response) {
      $('#day').html('');
      $('#day').show();
      for(var i = 1; i <= response; i++) {
        var option = document.createElement('option');
        option.setAttribute('value', i);
        option.text = i;
        $('#day').append(option);
      }
    }
  });
};

$(function () {
  $('#year').change(function () {
    getDays();
  });
  $('#month').change(function () {
    getDays();
  });
});