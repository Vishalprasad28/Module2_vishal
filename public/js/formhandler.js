$(document).ready(function(){

  //Login Functionality here
  $('#login-form').submit(function(e){
    e.preventDefault();
    login(this);
  });

  //SignUp Functionality here
  $('#signup-form').submit(function(e){
    e.preventDefault();
    signUp(this);
  });
  
  //Book Upload Form functionality
  $('#book-form').submit(function(e){
    e.preventDefault();
    bookUpload(this);
  }) 


  //All Function Here

  //Function to diaplay the message in error-box
  function showMessage(btn, message) {
    $('#submit').html(btn);
    $('.error-box').html(message);
  }

  //Function to convert the ford data into an array
  function convertToArray(e) {
    var array = {};
    e.forEach(element => {
      array[element['name']] = element['value'];
    });
    return array;
  }

  //Sign Up Form Validation Here
  function signUp(form) {
    var formData = new FormData(form);
    $.ajax({
      url: '/signUpValidation',
      type: 'POST',
      data: formData,
      success: function (data, status) {
        if (status == 'success') {
          showMessage('login', data.message);
          if (data.message == 'Registered') {
           window.location.href = '/upload';
          }
        }
      },
      cache: false,
      contentType: false,
      processData: false
  });
  }

  //Login Form Validation
  function login(form) {
    var formData = new FormData(form);
    $.ajax({
      url: '/loginValidation',
      type: 'POST',
      data: formData,
      success: function (data, status) {
        if (status == 'success') {
         showMessage('login', data.message);
         if (data.message == 'success') {
           window.location.href = '/upload';
         }
        }
      },
      cache: false,
      contentType: false,
      processData: false
  });
  }

  //Book pload for validation here
  function bookUpload(form) {
    var formData = new FormData(form);
    $.ajax({
      url: '/bookUploadValidation',
      type: 'POST',
      data: formData,
      success: function (data, status) {
        if (status == 'success') {
          showMessage('upload', data.message);
          if (data.message == 'success') {
            form.reset();
          }
        }
      },
      cache: false,
      contentType: false,
      processData: false
    });
  }
});