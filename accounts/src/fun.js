let uValid = false;
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
  if (typeof onSubmit !== 'function') {
    console.error('onSubmit is not a function');
  }else{
    console.log("No Error");
  }
});

$('#submit').attr('Value','Inputs Required');

function onSubmit(response, x){
    let input = document.getElementById('submit');
    if(x == 'p'){
        if (checkUsername('0')) {
            $('#submit').attr('Value','Login');
            input.disabled = false;
            document.getElementById("f-status").innerHTML = '';
          }
    }
    else if(x == 'u'){
        if (checkPassword('0')) {
            $('#submit').attr('Value','Login');
            input.disabled = false;
            document.getElementById("f-status").innerHTML = '';
          }
    }else if(x == 'O'){
        if (checkUsername()) {
            if (checkPassword()) {
              $('#submit').attr('Value','Login');
              input.disabled = false;
              document.getElementById("f-status").innerHTML = '';
            }
          }
    }else{

    }
  
}

  function checkUsername(x){
    $('#logUsername-status').attr('class', 'fa fa-sharp fa-solid fa-spinner');
    $('#logUsername-status').css('animation',' rotating 1s infinite linear');
    let userInputField = document.getElementById('logUsername');
    let userInput = userInputField.value;
    if (userInput.length < 6) {
      uValid = false;
      $('#logUsername-status').css('animation',' none');
      $('#logUsername-status').attr('class', 'fa fa-sharp fa-solid fa-circle-xmark fa-xl');
      $('#logUsername-status').css('color','red');
    }else {
      if (hasWhiteSpace(userInput)) {
        $('#logUsername-status').css('animation',' none');
        $('#logUsername-status').attr('class', 'fa fa-sharp fa-solid fa-circle-xmark fa-xl');
        $('#logUsername-status').attr('data-toggle', 'tooltip');
        $('#logUsername-status').attr('data-placement', 'top');
        $('#logUsername-status').attr('title', 'Spaces are not allowed');
        $('#logUsername-status').css('color','red');
        uValid = false;
      }else {
        $('#logUsername-status').css('animation',' none');
        $('#logUsername-status').attr('class', 'fa fa-sharp fa-solid fa-circle-check fa-xl');
        $('#logUsername-status').attr('data-toggle', 'tooltip');
        $('#logUsername-status').attr('data-placement', 'top');
        $('#logUsername-status').attr('title', 'The username is not authenticated yet');
        $('#logUsername-status').css('color','green');
        uValid = true;
        if(x == '1'){
            onSubmit('fgfdg','u');
          }
      }
    }
    return uValid;
  }

  function checkPassword(x){
        $('#logPassword-status').attr('class', 'fa fa-sharp fa-solid fa-spinner');
        $('#logPassword-status').css('animation',' rotating 1s infinite linear');
        let userInputField = document.getElementById('logPassword');
        let userInput = userInputField.value;
        if (userInput.length < 8) {
          uValid = false;
          $('#logPassword-status').css('animation',' none');
          $('#logPassword-status').attr('class', 'fa fa-sharp fa-solid fa-circle-xmark fa-xl');
          $('#logPassword-status').attr('data-toggle', 'tooltip');
          $('#logPassword-status').attr('title', 'Password should not empty or less then 8!');
          $('#logPassword-status').attr('data-placement', 'top');
          $('#logPassword-status').css('color','red');
        }else {
          uValid = true;
          $('#logPassword-status').css('animation',' none');
          $('#logPassword-status').attr('class', 'fa fa-sharp fa-solid fa-circle-check  fa-xl');
          $('#logPassword-status').attr('data-toggle', 'tooltip');
          $('#logPassword-status').attr('title', 'Password is not authenticated yet!');
          $('#logPassword-status').attr('data-placement', 'top');
          $('#logPassword-status').css('color','green');
          if(x == '1'){
            onSubmit('dfgfdg','p');
          }
          
        }
    

    return uValid;
  }



 
  
  function hasWhiteSpace(data){
    return data.includes(' ');
  }
  
  function hasSpecialChars(str) {
    const specialChars = /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
    return specialChars.test(str);
  }
  
  function hideError(a){
    let error = document.getElementById('adminErros');
    error.style.display = "none";
  }

  