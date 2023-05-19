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
  uError.innerHTML = 'Checking...';
  if (username.length <= 8) {
    uError.innerHTML = ' Short Username ';
  }else{
    checkFromRemote();
    async function checkFromRemote(){
        const logUrl = `/.ht/API/updateDetails.php/?fieldsCheck`;
        var encyDat = {
          'personID' : `${ePID}`,
          'field' : 'username',
          'value' : `${username}`,
          'currentValue': `${currentValue}`
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
  var emailID = document.querySelector('#eMail').value;
  var uError = document.querySelector('#emailErrorMessage');
  uError.style.color = 'orange';
  uError.innerHTML = 'Checking...';
  if (username.length <= 5) {
    uError.innerHTML = ' Short Email ';
  }else{
    checkFromRemote();
    async function checkFromRemote(){
        const logUrl = `/.ht/API/updateDetails.php/?fieldsCheck`;
        var encyDat = {
          'personID' : `${ePID}`,
          'field' : 'emailID',
          'value' : `${emailID}`,
          'currentValue': `${currentValue}`
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
            isEmail = false;
            uError.innerHTML = ' Already Taken ';
            uError.style.color = 'Orange';
          }else{
            // Username  not Avialble
            isEmail = false;
            uError.innerHTML = 'Available';
            uError.style.color = 'Lime';
          }
          
        }else{
            isEmail = false;
            uError.innerHTML = 'Try Again...';
            uError.style.color = 'Orange';
        }
    }
  }
}


function checkDOB(){
  var DOB = document.querySelector('#DOB').value;
  var errorMessage = document.querySelector('#DOBErrorMessage');
  if (DOB != '') {
    if (isFiveYearsOld(DOB)) {
        isDOB = true;
        errorMessage.style.color = 'Lime';
    }else{
        errorMessage.style.color = 'Orange';
        errorMessage.innerHTML = 'Must be atleast 7 years old';
    }
  }else{
      errorMessage.style.color = 'Orange';
      errorMessage.innerHTML = 'Please Enter DOB';
  }


  function isFiveYearsOld(dateString) {
    var inputDate = new Date(dateString);
    var today = new Date();
    var tenYearsAgo = new Date().setFullYear(today.getFullYear() - 7);
    return inputDate <= tenYearsAgo;
    }

}
   

  
   