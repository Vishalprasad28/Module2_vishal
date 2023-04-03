$(document).ready(function(){
  $('#login-form').submit(function(e){
    e.preventDefault();
    // var array = convertToArray(this);
    var formData = new FormData(this);
  //   $.post("/loginValidation",array, function (data, status) {
  //     var jsonData = $.parseJSON(data);
  //     if (jsonData.status = 'success') {
  //       if (jsonData.role == 'admin') {
  //         window.location.href = '/bookForm';
  //       }
  //       else if (jsonData.role == 'reader') {
  //         window.location.href = '/booklist';
  //       }
  //     }
  //     $('.error-box').html(jsonData.status);
  // });
  $.ajax({url: "/loginValidation", formData,
  success: function(data, status){
    var jsonData = $.parseJSON(data);
    if (jsonData.status = 'success') {
    if (jsonData.role == 'admin') {
    window.location.href = '/bookForm';
    }
    else if (jsonData.role == 'reader') {
    window.location.href = '/booklist';
    }
    }
    $('.error-box').html(jsonData.status);
  }});
});


});