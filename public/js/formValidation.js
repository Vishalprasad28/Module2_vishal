$(document).ready(function(){
  $('#login-form').submit(function(e){
    var formData = new FormData(this);
    e.preventDefault();
    $(".error-box p").html("");
    $('#submit').attr('disabled',true);
    var formData = new FormData(this);
   $.ajax({
     url: '../Controller/LoginValidation.php',
     type: 'POST',
     data: formData,
     success: function (data, status) {
      var data = $.parseJSON(data);
      if (status == 'success') {
        $('#submit').attr('disabled', false);
        if (data.message == 'success') {
          if (data.role == 'author') {
            window.location.href = 'bookform.php';
          }
          else if (data.role == 'reader') {
            window.location.href = 'accountoverview.php';
          }
        }
        $('.error-box').html(data.message);
      }
     },
     cache: false,
     contentType: false,
     processData: false
 });
});
});