var isFullName = false;
var isUsername = false;
var isEmail = false;
var isDOB = false;
var isGender = false;
var isWebsite = false;
var isBio = false;

function checkUsername(){
  var username = document.querySelector('#username').value;
  var uError = document.querySelector('#usernameErrorMessage');
  uError.style.color = 'orange';
  uError.innerHTML = '<div class="spinner errorSpinner" ></div>';
  if (username.length <= 8) {
    uError.innerHTML = ' Short Username ';
  }else{
    checkFromRemote();
    async function checkFromRemote(){
        const logUrl = `/.ht/API/updateDetails.php/?fieldsCheck`;
        var encyDat = {
          'personID' : `${ePID}`,
          'field' : 'username',
          'value' : `${username}`
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
          if (data.Result) {
            isUsername = false;
            uError.innerHTML = ' Already Taken ';
            uError.style.color = 'Orange';
          }else{
            // Username  not Avialble
            isUsername = false;
            uError.innerHTML = 'Available';
            uError.style.color = 'Lime';
          }
          
        }else{
            isUsername = false;
            uError.innerHTML = 'Try Again...';
            uError.style.color = 'Orange';
        }
    }
  }
}
    



function checkEmail(){
  var username = document.querySelector('#eMail').value;
  var uError = document.querySelector('#emailErrorMessage');
  uError.style.color = 'orange';
  if (username.length <= 5) {
    uError.innerHTML = '(Short Not Avialable)';
  }else{
    checkFromRemote();
    async function checkFromRemote(){
        const logUrl = `../.ht/API/updateDetails.php?fieldsCheck`;
        var encyDat = {
          'ePID' : `${ePID}`,
          'field' : 'emailID'
        };
        const response = await fetch(logUrl, {
            method: 'post',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(encyDat)
          });
        var data = await response.json();
        if (data.Result) {
          console.log(data);
            console.log(data.Result);
        }else{
            errorMessage.style.display = 'inline-block';
            errorMessage.innerHTML = 'Not Updated';
        }
    }
  }
}
  
   

  
   