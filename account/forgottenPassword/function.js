var isOTP = false;
var passwordVerified = false;
var passMessage = "";

function sendOTP(){
  let error =  document.getElementById('otpError');
  let eu =  document.getElementById('emailUsername');
  const sendotp = async () =>{
    const url = '/.ht/API/FORGOT_PASSWORD.php';
    var encyDat = {
      "purpose": "sendOTP",
      "emailUsername": `${eu.value}`

    };
    const response = await fetch(url, {
      method: 'post',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(encyDat)
    });
    var data = await response.json();
    if (data) {
      if (data.Result) {
        window.location = 'setPassword';
      }else{
        error.style.display = 'block';
        error.innerHTML = data.message;
        error.style.color = 'red';
      }
    }else{
      error.style.display = 'block';
      error.innerHTML = 'Server Error';
      error.style.color = 'red';
    }
  }
  sendotp();
}

function resendOTP(){
  let error =  document.getElementById('otpError');
  let eu =  document.getElementById('emailUsername');
  const resendotp = async () =>{
    const url = '/.ht/API/FORGOT_PASSWORD.php';
    var encyDat = {
      "purpose": "resendOTP"
    };
    const response = await fetch(url, {
        method: 'post',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(encyDat)
      });
      var data = await response.json();
    if (data) {
      if (data.Result) {
        error.style.display = 'block';
        error.innerHTML = data.message;
        error.style.color = 'limegreen';
      }else{
        error.style.display = 'block';
        error.innerHTML = data.message;
        error.style.color = 'red';
      }
    }else{
      error.style.display = 'block';
      error.innerHTML = 'Server Error';
      error.style.color = 'red';
    }
  }
  resendotp();
}




function checkOTP(){
  var input = document.getElementById('otpInput');
  var newPass = input.value;
  if(newPass.length == 6){
    input.style.color = 'green';
    input.style.borderColor = 'lime';
    isOTP = true;
  }else{
    input.style.color = 'orange';
    input.style.borderColor = '#ff3e00';
  }
}


function checkNewPassword(){
  var input = document.getElementById('password');
  var newOTP = input.value;
  checkVerifyPassword();
  if(newOTP.length <= 8){
    input.style.color = '#ff3e00';
    input.style.borderColor = '#ff3e00';
  }else if(checkPasswordStrength(input) == 'Weak'){
    input.style.color = '#ff3e00';
    input.style.borderColor = '#ff3e00';
  }else if(checkPasswordStrength(input) == 'Medium'){
    input.style.color = 'green';
    input.style.borderColor = 'lime';
  }else if(checkPasswordStrength(input) == 'Strong'){
    input.style.color = 'green';
    input.style.borderColor = 'lime';
  }else{
    input.style.color = 'orange';
    input.style.borderColor = '#ff3e00';
  }
}

function checkPasswordStrength(password) {
  var strength = '';

  var hasLetters = /[a-zA-Z]/.test(password);
  var hasNumbers = /\d/.test(password);
  var hasSpecialChars = /[^a-zA-Z0-9]/.test(password);

  if ((hasLetters && hasNumbers) || (hasLetters && hasSpecialChars) || (hasSpecialChars && hasNumbers)) {
    strength = 'Medium';
  } else if (hasLetters && hasNumbers && hasSpecialChars) {
    strength = 'Strong';
  } else {
    strength = 'Weak';
  }

  return strength;
}

function checkVerifyPassword(){
  var input = document.getElementById('passwordVerify');
  var input2 = document.getElementById('password');
  if (input.value.length < 8) {
    input.style.color = '#ff3e00';
    input2.style.color = '#ff3e00';
    input.style.borderColor = '#ff3e00';
    input2.style.borderColor = '#ff3e00';
    passMessage = 'Short password entered';
    passwordVerified = false;
  }else if(input2.value === input.value){
    input.style.color = 'green';
    input2.style.color = 'green';
    input.style.borderColor = 'lime';
    input2.style.borderColor = 'lime';
    passwordVerified = true;
  }else{
    input2.style.color = '#ff3e00';
    input.style.color = '#ff3e00';
    input.style.borderColor = '#ff3e00';
    input2.style.borderColor = '#ff3e00';
    passMessage = 'Password not matched';
    passwordVerified = false;
  }
}


function resetPassword(){
  let error =  document.getElementById('otpError');
  if (!isOTP) {
    error.style.display = 'block';
    error.innerHTML = 'Six digits OTP required';
    error.style.color = 'red';
  }else if (!passwordVerified) {
    error.style.display = 'block';
    error.innerHTML = passMessage;
    error.style.color = 'red';
  }else{
    var input = document.getElementById('otpInput');
    var input2 = document.getElementById('passwordVerify');
    var inputOTP = input.value;
    const resetPassword = async () =>{
      const url = '/.ht/API/FORGOT_PASSWORD.php';
      var encyDat = {
        "purpose": "verifyOTP",
        "OTP": `${inputOTP}`,
        "newPassword" :`${input2.value}`

      };
      const response = await fetch(url, {
        method: 'post',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(encyDat)
      });
      var data = await response.json();
      if (data) {
        if (data.Result) {
          error.style.display = 'block';
          error.innerHTML = 'Password Reset Successfully';
          error.style.color = 'lime';
          setTimeout(function (){
            window.location = '/account/';
          }, 3000);
        }else{
          error.style.display = 'block';
          error.innerHTML = data.message;
          error.style.color = 'red';
        }
      }else{
        error.style.display = 'block';
        error.innerHTML = 'Server Error';
        error.style.color = 'red';
      }
    }
    resetPassword();
  }
}
