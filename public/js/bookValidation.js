$(document).ready(function(){
  $('#book-form').submit(function(e){
    var formData = new FormData(this);
    e.preventDefault();
    $(".error-box p").html("");
    $('#submit').attr('disabled',true);
    var formData = new FormData(this);
   $.ajax({
     url: '../Controller/BookValidation.php',
     type: 'POST',
     data: formData,
     success: function (data, status) {
      if (status == 'success') {
        $('#submit').attr('disabled', false);
        alert(data);
        $('.error-box').html(data);
      }
     },
     cache: false,
     contentType: false,
     processData: false
 });
});
});