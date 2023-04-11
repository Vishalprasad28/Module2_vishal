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
  
  
  //All Function Here

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
         alert(data.message);
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
         alert(data.message);
        }
      },
      cache: false,
      contentType: false,
      processData: false
  });
  }
});