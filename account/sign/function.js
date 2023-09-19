function login(){
  let passwordInput = document.getElementById('loginPassword').value;
  let emailUsername = document.getElementById('userEmailName').value;
  let loginError = document.getElementById('loginError');
  const userLogin = async () =>{
    const url = '/.ht/API/EMAIL_LOGIN.php';
    var encyDat = {
      "purpose": "login",
      "usernameEmail": `${emailUsername}`,
      "password":`${passwordInput}`
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
        loginError.style.display = 'block';
        loginError.style.color = 'lime';
        loginError.innerHTML = 'Login Successful';
        let element = document.getElementById('loginButton');
        element.innerHTML = 'Login';
        window.location = '/';
      }else{
        loginError.style.display = 'block';
        loginError.innerHTML = data.message;
        let element = document.getElementById('loginButton');
        element.innerHTML = 'Redirecting.....';
      }
    }else{
      loginError.style.display = 'block';
      loginError.innerHTML = 'Problem at our end';
    }
  }
  if (emailUsername.trim() === "") {
    loginError.style.display = 'block';
    loginError.innerHTML = 'Empty Username or Email';
  }else if (passwordInput.trim() === "") {
    loginError.style.display = 'block';
    loginError.innerHTML = 'Empty Password';
  }else{
  enableLoading('loginButton');
  userLogin();
  }



}

function enableLoading(id){
  let element = document.getElementById(`${id}`);
  element.innerHTML = 'Loading...<div class="spinner"></div>';
}
var emailVerified = false;
var nameVerified = false;
var passwordVerified = false;

function signup(){
  let name = document.getElementById('fullname');
  let email = document.getElementById('emailAddress0');
  let password = document.getElementById('password');
  let vPassword = document.getElementById('passwordVerify');
  const userSignUp = async () =>{
    const url = '/.ht/API/EMAIL_LOGIN.php';
    var encyDat = {
      "purpose": "signUp",
      "Email": `${email}`,
      "password":`${password}`,
      "name": `${name}`
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
      }else{
      }
    }else{
    }
  }
  userSignUp();
}

function checkName() {
  var nameRegex =/^[a-zA-Z]+(?: [a-zA-Z]+)*(?:\. [a-zA-Z]+)?$/;
  let name = document.getElementById('fullName');
  if (name.value.length < 6) {
    name.style.borderColor = 'orange'
    name.style.color = 'red'
  }else if(nameRegex.test(name.value)){
    name.style.borderColor = 'lime'
    name.style.color = 'Green'
    nameVerified = true;
  }else{
    name.style.borderColor = 'orange'
    name.style.color = 'red'
  }
}

function checkEmail(){
  let emailID = document.querySelector('#emailAddress');
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
  if ( emailID.value.length <= 5) {
    emailID.style.borderColor = 'Orange';
  }else if(!emailRegex.test(emailID.value)){
    emailID.style.borderColor = 'Orange';
    emailID.style.color = 'red';
  }else{
    const checkFromRemote = async () =>{
        const logUrl = `/.ht/API/updateDetails.php/?emailCheck`;
        var encyDat = {
          'email': `${emailID.value}`
        };
        const response = await fetch(logUrl, {
            method: 'post',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(encyDat)
          });
        var data = await response.json();
        if (data) {
          if (!data.Result) {
            emailID.style.borderColor = 'lime';
            emailVerified = true;
            emailID.style.color = 'green';
          }else{
            alert('Email already exists');
            emailID.style.borderColor =  'Orange';
            emailID.style.color = 'red';
          }
        }else{
            alert('Server Problem');
            emailID.style.borderColor = 'Orange';
            emailID.style.color = 'red';
        }
    }
    checkFromRemote();
  }
}

function checkNewPassword(){
  var input = document.getElementById('password');
  newPass = input.value;
  checkVerifyPassword();
  if(newPass.length <= 8){
    input.style.color = '#ff3e00';
    input.style.borderColor = '#ff3e00';
  }else if(checkPasswordStrength(input) == 'Weak'){
    input.style.color = '#ff3e00';
    input.style.borderColor = '#ff3e00';
  }else if(checkPasswordStrength(input) == 'Medium'){
    input.style.color = 'green';
    input.style.borderColor = 'lime';
    passwordVerified = true;
  }else if(checkPasswordStrength(input) == 'Strong'){
    input.style.color = 'green';
    input.style.borderColor = 'lime';
  }else{
    input.style.color = 'orange';
    input.style.borderColor = '#ff3e00';
  }
}

function checkVerifyPassword(){
  var input = document.getElementById('passwordVerify');
  var input2 = document.getElementById('password');
  if (input.value.length < 8) {
    input.style.color = '#ff3e00';
    input2.style.color = '#ff3e00';
    input.style.borderColor = '#ff3e00';
    input2.style.borderColor = '#ff3e00';
  }else if(input2.value == input.value){
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
