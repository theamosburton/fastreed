let uValid = false;
  function checkUsername(x){
    $('#logUsername-status').attr('class', 'fa fa-sharp fa-solid fa-spinner');
    $('#logUsername-status').css('animation',' rotating 1s infinite linear');
    let userInputField = document.getElementById('logUsername');
    let userInput = userInputField.value;
    if (userInput.length < 6) {
      uValid = false;
      $('#logUsername-status').css('animation',' none');
      $('#logUsername-status').attr('class', 'fa fa-sharp fa-solid fa-circle-xmark');
      $('#logUsername-status').css('color','red');
    }else {
      if (hasWhiteSpace(userInput)) {
        $('#logUsername-status').css('animation',' none');
        $('#logUsername-status').attr('class', 'fa fa-sharp fa-solid fa-circle-xmark');
        $('#logUsername-status').css('color','red');
        uValid = false;
      }else {
        $('#logUsername-status').css('animation',' none');
        $('#logUsername-status').attr('class', 'fa fa-sharp fa-solid fa-circle-check');
        $('#logUsername-status').css('color','green');
        uValid = true;
        if(x == '1'){
            onSubmit('u');
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
        if (userInput.length < 1) {
          uValid = false;
          $('#logPassword-status').css('animation',' none');
          $('#logPassword-status').attr('class', 'fa fa-sharp fa-solid fa-circle-xmark');
          $('#logPassword-status').css('color','red');
        }else {
          uValid = true;
          $('#logPassword-status').css('animation',' none');
          $('#logPassword-status').attr('class', 'fa fa-sharp fa-solid fa-circle-check');
          $('#logPassword-status').css('color','green');
          if(x == '1'){
            onSubmit('p');
          }
          
        }
    

    return uValid;
  }



  $('#submit').attr('Value','Disabled');

function onSubmit(x){
    if(x == 'p'){
        if (checkUsername('0')) {
            $('#submit').attr('Value','Login');
            $('#submit').prop('disabled', false);
            document.getElementById("f-status").innerHTML = '';
          }else{
            document.getElementById("f-status").innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong> Username is Empty </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>`;
            }
    }
    else if(x == 'u'){
        if (checkPassword('0')) {
            $('#submit').attr('Value','Login');
            $('#submit').prop('disabled', false);
            document.getElementById("f-status").innerHTML = '';
          }else{
            document.getElementById("f-status").innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong> Password is Empty </strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>`;
            }
    }else{
        if (checkUsername()) {
            if (checkPassword()) {
              $('#submit').attr('Value','Login');
              $('#submit').prop('disabled', false);
              document.getElementById("f-status").innerHTML = '';
            }else{
              document.getElementById("f-status").innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong> Password is Empty </strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>`;
              }
          }else{
              document.getElementById("f-status").innerHTML = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong> Username is Empty </strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>`;;
          }
    }
  
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

  