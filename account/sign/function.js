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

function signup(){
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
