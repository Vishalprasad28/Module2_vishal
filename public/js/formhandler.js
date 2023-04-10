$(document).ready(function(){

  //Login Functionality here
  $('#login-form').submit(function(e){
    e.preventDefault();
    var form = $(this).serializeArray();
    login(form);
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

  //Login Form Validation
  function login(e) {
    var array = convertToArray(e);
    $.post('/loginValidation', array, function(data, status){
      alert(status);
      alert(data.message);
    });
  }
});