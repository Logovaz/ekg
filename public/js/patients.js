var PatientControl = function() {
  
  var user_id = undefined;
  
  this.handleAddButton = function() {
    $('#add-patient').click(function ( event ) {
      event.preventDefault();
      $('#add-form').submit();
    });
  };
  
  this.handleTooltip = function() {
    $('.time-btn').tooltip({
      position: {
        my: 'center',
        at: 'right+83 top'
      }
    });
    $('.mail-btn').tooltip({
      position: {
        my: 'center',
        at: 'right+60 top'
      }
    });
    $('#add-patient').tooltip({
      position: {
        my: 'center',
        at: 'right+52 top'
      }
    });
  };
  
  this.handleMessage = function() {
    var self = this;
    $('.message-form').dialog({
      autoOpen: false,
      height: 300,
      width: 350,
      modal: true,
      buttons: [
        {
          text: sendText,
          click: function() {
            if (!$.trim($('#message-area').val())) {
              return false;
            }
            $.ajax({
              url: 'ajax/sendMessage',
              data: {
                user_id: self.user_id,
                message: $('#message-area').val()
              },
              dataType: 'json',
              type: 'post',
              success: function(ajax) {
                $('#message-area').val('');
                $('.message-form').dialog('close');
                $('.success-block').show();
              },
              error: function() {
                $('.message-form').dialog('close');
                $('.error-block').show();
              }
            });
          }
        },
        {
          text: clearText,
          click: function() {
            $('#message-area').val('');
            $('#message-area').focus();
          }
        },
        {
          text: cancelText,
          click: function() {
            $( this ).dialog('close');
          }
        }
      ]
      });
    $('.mail-btn').click(function() {
      self.user_id = $( this ).children().first().val();
      $('.message-form').dialog('option', 'title', messageToText + $( this ).parent().children().first().html());
      $('.message-form').dialog('open');
    });
  };
  
  this.start = function() {
    this.handleAddButton();
    this.handleTooltip();
    this.handleMessage();
  };
};

$( function () {
  mainHandle = new PatientControl();
  mainHandle.start();
});