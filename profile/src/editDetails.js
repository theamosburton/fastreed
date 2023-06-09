var newPass;
var isNewPass = false;
var verifyPass = false;
var isCurrentPass = false;
var isCurrentPassDet = false;
var currentPassDet;
var currentPass;
class updateDetails{
  constructor(){

    this.isFullName = false;
    this.isUsername = false;
    this.isEmail = false;
    this.isDOB = false;
    this.isGender = false;
    this.fullName;
    this.emailID;
    this.username;
    this.DOB;
    this.Gender;
    this.website;
    this.about;
    var email = document.querySelector('#eMail');
    if (typeof email !== 'undefined' && email !== null) {
      this.checkEmail();
    }
    this.checkName();
    this.checkDOB()
    this.checkUsername();
    this.validateGender();
    this.checkWebsite();
    this.checkAbout();
    this.checkNewPassword();
    this.checkVerifyPassword();
  }

  checkName() {
    var nameRegex =/^[a-zA-Z]+(?: [a-zA-Z]+)*(?:\. [a-zA-Z]+)?$/;
    this.fullName = document.querySelector('#fullName').value;
    var uError = document.querySelector('#nameErrorMessage');
    
    if(nameRegex.test(this.fullName)){
      uError.innerHTML = '&#x2713;';
      uError.style.color = 'lime';
      this.isFullName = true;
    }else{
      this.isFullName = false;
      uError.style.color = 'orange';
      uError.innerHTML = 'Checking...';
      uError.innerHTML = 'Invalid Name';
    }
  }


  validateGender(){
    this.isGender = false;
    this.Gender = document.querySelector('#gender').value;
    var errorMessage = document.querySelector('#genderErrorMessage');
    errorMessage.style.color = 'Orange';
    if (this.Gender == '') {
      errorMessage.innerHTML = 'Please select gender';
    }else if(this.Gender == 'Male' || gender.value == 'Female' || gender.value == 'Others'){
      this.isGender = true;
      errorMessage.innerHTML = '&#x2713;';
      errorMessage.style.color = 'lime';
      
    }else{
      errorMessage.innerHTML = `Please select gender`;
    }
  }


  checkUsername(){
    this.isUsername = false;
    this.username = document.querySelector('#username').value;
    var uError = document.querySelector('#usernameErrorMessage');
    uError.style.color = 'orange';
    uError.innerHTML = 'Checking...';
    var usernameRegex = /^[a-zA-Z0-9_.]+$/;
    var validUsername = !/\s/.test(this.username) && usernameRegex.test(this.username)
    if(!validUsername){
      uError.innerHTML = ' Invalid Username ';
    }else if (this.username.length <= 8) {
      uError.innerHTML = ' Short Username ';
    }else{
      
      const checkFromRemote = async () =>{
          const logUrl = `/.ht/API/updateDetails.php/?fieldsCheck`;
          var encyDat = {
            'personID' : `${ePID}`,
            'field' : 'username',
            'value' : `${this.username}`,
            'currentValue': `${currentUsername}`
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
              uError.innerHTML = ' Already Taken ';
              uError.style.color = 'Orange';
            }else{
              this.isUsername = true;
              uError.innerHTML = '&#x2713;';
              uError.style.color = 'Lime';
            }
            
          }else{
              uError.innerHTML = 'Try Again...';
              uError.style.color = 'Orange';
          }
      }
      checkFromRemote();
    }
  }

  checkEmail(){
    this.isEmail = false;
    this.emailID = document.querySelector('#eMail').value;
    var uError = document.querySelector('#emailErrorMessage');
    uError.style.color = 'orange';
    uError.innerHTML = 'Checking...';
    if ( this.emailID.length <= 5) {
      uError.innerHTML = ' Short Email ';
    }else{
      const checkFromRemote = async () =>{
          const logUrl = `/.ht/API/updateDetails.php/?fieldsCheck`;
          var encyDat = {
            'personID' : `${ePID}`,
            'field' : 'emailID',
            'value' : `${this.emailID}`,
            'currentValue': `${currentEmail}`
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
              this.isEmail = true;
              uError.innerHTML = '&#x2713;';
              uError.style.color = 'Lime';   
            }else{
              uError.innerHTML = ' Already Taken ';
              uError.style.color = 'Orange';
            }
          }else{
              uError.innerHTML = 'Try Again...';
              uError.style.color = 'Orange';
          }
      }
      checkFromRemote();
    }
  }
  
  checkDOB(){
    this.isDOB = false;
    this.DOB = document.querySelector('#DOB').value;
    var errorMessage = document.querySelector('#DOBErrorMessage');
    if ( this.DOB != '') {
      if (isFiveYearsOld(this.DOB)) {
          this.isDOB = true;
          errorMessage.innerHTML = '&#x2713;';
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

  checkWebsite(){
    this.website = document.querySelector('#website').value;
    var errorMessage = document.querySelector('#websiteErrorMessage');
    var pattern = new RegExp('^((https?:)?\\/\\/)?' + // protocol
    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
    '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
    '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
    '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
  
    if(pattern.test(this.website)){
      if(this.website == 'https://www.fastreed.com'){
        this.website = '';
        errorMessage.innerHTML = '&#x2713;';
        errorMessage.style.color = 'Lime';
      }else{
        errorMessage.innerHTML = '&#x2713;';
        errorMessage.style.color = 'Lime';
      }
    }else{
      errorMessage.style.color = 'Orange';
      errorMessage.innerHTML = 'Invalid Url';
    }
  }

  checkAbout(){
    this.about = document.querySelector('#about').value;
  }

  editByAdmin(){
    var messageDiv = document.querySelector('#uAlert');
    var mainDiv = document.querySelector('#updateAlert');
    var dispMessage = document.querySelector('#uAlert #editmessage');
    var adminPassword = document.getElementById('adminPasswordEdit').value;
    messageDiv.classList.remove('alert-success');
    messageDiv.classList.add('alert-danger');
    mainDiv.style.display = 'block';
    messageDiv.style.display = 'block';
    if (this.isFullName) {
      if (this.isUsername) {
        if (this.isEmail) {
          if (this.isDOB) {
            if (adminPassword < 8) {
              mainDiv.style.display = 'block';
              dispMessage.innerHTML = 'Admin password required';
            }else{
              messageDiv.classList.add('alert-success');
              messageDiv.classList.remove('alert-danger');
              dispMessage.innerHTML = 'Updating...';
              const updateDetails = async () =>{
                const url = '/.ht/API/updateDetails.php/?fullProfileUpdate';
                var encyDat = {
                  'personID' : `${ePID}`,
                  'fullName' : `${this.fullName}`,
                  'username' : `${this.username}`,
                  'email': `${this.emailID}`,
                  'DOB': `${this.DOB}`,
                  'Gender' : `${this.Gender}`,
                  'website' : `${this.website}`,
                  'about' : `${this.about}`,
                  'cUsername': `${currentUsername}`,
                  'cEmail': `${currentEmail}`,
                  'editor':'admin',
                  'currentPassword': `${currentPassDet}`,
                  'adminPassword' : `${adminPassword}`
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
                    messageDiv.classList.add('alert-success');
                    messageDiv.classList.remove('alert-danger');
                    dispMessage.innerHTML = 'Updated Successfully';
                    let urlPostfix = this.username;
                    setTimeout(function(){
                      window.location.href = `/users/${urlPostfix}`;
                    }, 3000);
                  }else{
                    mainDiv.style.display = 'block';
                    dispMessage.innerHTML = data.message;
                  }
                }else{
                  mainDiv.style.display = 'block';
                  dispMessage.innerHTML = data.message;
                }
              }
              updateDetails();
            }
          }else{
            mainDiv.style.display = 'block';
            dispMessage.innerHTML = 'Problem With Date of Birth';
          }
        }else{
          mainDiv.style.display = 'block';
          dispMessage.innerHTML = 'Problem With Email ID';
        }
      }else{
        mainDiv.style.display = 'block';
        dispMessage.innerHTML = 'Problem With username';
      }
    }else{
      mainDiv.style.display = 'block';
      dispMessage.innerHTML = 'Problem With Your Name';
    }
  }

  editByUser(){
    var messageDiv = document.getElementById('uAlert');
    var mainDiv = document.getElementById('updateAlert');
    var dispMessage = document.querySelector('#uAlert #editmessage');
    messageDiv.classList.remove('alert-success');
    messageDiv.classList.add('alert-danger');
    mainDiv.style.display = 'block';
    messageDiv.style.display = 'block';
    if (this.isFullName) {
      if (this.isUsername) {
        var emailVal = document.querySelector('#eMail');
        if (typeof emailVal !== 'undefined' && emailVal !== null) {
          this.isEmail = this.isEmail;
        }else{
          this.isEmail = true;
          this.emailID = '';
        }
        if (this.isEmail) {
          if (this.isDOB) {
           
            messageDiv.classList.add('alert-success');
            messageDiv.classList.remove('alert-danger');
            dispMessage.innerHTML = 'Updating...';
            const updateDetails = async () =>{
              const url = '/.ht/API/updateDetails.php/?fullProfileUpdate';
              var encyDat = {
                'personID' : `${ePID}`,
                'fullName' : `${this.fullName}`,
                'username' : `${this.username}`,
                'email': `${this.emailID}`,
                'DOB': `${this.DOB}`,
                'Gender' : `${this.Gender}`,
                'website' : `${this.website}`,
                'about' : `${this.about}`,
                'cUsername': `${currentUsername}`,
                'cEmail': `${currentEmail}`,
                'editor':'user',
                'currentPassword': `${currentPassDet}`
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
                console.log();
                if (data.Result) {
                  mainDiv.style.display = 'block';
                  messageDiv.classList.add('alert-success');
                  messageDiv.classList.remove('alert-danger');
                  dispMessage.innerHTML = 'Updated Successfully';
                  setTimeout(function(){
                    location.reload();
                  }, 3000);
                }else{
                  mainDiv.style.display = 'block';
                  dispMessage.innerHTML = data.message;
                }
              }else{
                mainDiv.style.display = 'block';
                dispMessage.innerHTML = data.message;
              }
            }
            updateDetails();
          }else{
            mainDiv.style.display = 'block';
            dispMessage.innerHTML = 'Problem With Date of Birth';
          }
        }else{
          mainDiv.style.display = 'block';
          dispMessage.innerHTML = 'Problem With Email ID';
        }
      }else{
        mainDiv.style.display = 'block';
        dispMessage.innerHTML = 'Problem With username';
      }
    }else{
      mainDiv.style.display = 'block';
      dispMessage.innerHTML = 'Problem With Your Name';
    }
  }

  shuffleVerify(){
    var verifyPass = document.querySelector('#newPasswordVerify');
    var verifyEye  = document.querySelector('#verifyEye');
    if (verifyEye.classList.contains('fa-eye-slash')) {
      verifyEye.classList.remove('fa-eye-slash');
      verifyEye.classList.add('fa-eye');
      verifyPass.type = 'text';
    } else {
      verifyEye.classList.remove('fa-eye');
      verifyEye.classList.add('fa-eye-slash');
      verifyPass.type = 'password';
    }
  }

  shufflePass(){
    var newPass = document.querySelector('#newPassword');
    var passEye  = document.querySelector('#passEye');
    if (passEye.classList.contains('fa-eye-slash')) {
      passEye.classList.remove('fa-eye-slash');
      passEye.classList.add('fa-eye');
      newPass.type = 'text';
    } else {
      passEye.classList.remove('fa-eye');
      passEye.classList.add('fa-eye-slash');
      newPass.type = 'password';
    }
  }
  
  checkNewPassword(){
    var error = document.getElementById('newPasswordError');
    var input = document.getElementById('newPassword');
    newPass = input.value;
    this.checkVerifyPassword();
    error.style.display = 'block';
    if(newPass.length <= 8){
      error.innerHTML = 'Enter new password';
      error.style.color = '#ff3e00';
    }else if(checkPasswordStrength(input) == 'Weak'){
      error.innerHTML = 'Weak password';
      error.style.color = '#ff3e00';
      isNewPass = false;
    }else if(checkPasswordStrength(input) == 'Medium'){
      error.innerHTML = 'Medium password';
      error.style.color = 'lime';
      isNewPass = true;
    }else if(checkPasswordStrength(input) == 'Strong'){
      error.innerHTML = 'Strong password';
      error.style.color = 'lime';
      isNewPass = true;
    }else{
      error.innerHTML = 'New password required';
      error.style.color = 'orange';
    }
  }

  checkVerifyPassword(){  
    var error = document.getElementById('verifyError');
    var input = document.getElementById('newPasswordVerify');
    error.style.display = 'block';
    if (!isNewPass) {
      error.innerHTML = 'New password required';
      error.style.color = '#ff3e00';
    }else if(newPass == input.value){
      error.innerHTML = 'Password matched';
      error.style.color = 'lime';
      verifyPass = true;
    }else{
      error.innerHTML = 'Password not matched';
      error.style.color = '#ff3e00';
    }
  }

// Used when editing details
  checkCurrentPassword1(){
    var error = document.getElementById('passwordError');
    var input = document.getElementById('currentPasswordDet').value;
    currentPassDet = input;
    error.style.display = 'inline';
    if (input <= 0) {
      error.innerHTML = 'Current password needed';
      error.style.color = '#ff3e00';
      isCurrentPassDet = false;
    }else{
      error.style.display = 'none';
      isCurrentPass = true;
    }
  }

  // Used when password is updated
  checkCurrentPassword2(){
    var error = document.getElementById('currentasswordError');
    var input = document.getElementById('currentPassword').value;
    currentPass = input;
    error.style.display = 'block';
    if (input <= 0) {
      error.innerHTML = 'Please enter current password';
      error.style.color = '#ff3e00';
      isCurrentPass = false;
    }else{
      error.style.display = 'none';
      isCurrentPass = true;
    }
  }

  createNewPassword(whoCreate){
    var erMessage = document.getElementById('peditmessage');
    var errorDiv = document.querySelector('#pErrorDiv');
    var messageDiv= document.getElementById('pErrorMessage');
    errorDiv.style.display = 'block';
    var adminPass;
    var userOrAdmin;
    var isCurrent;
    if (whoCreate == 'user') {
      adminPass = '';
      userOrAdmin = 'current';
      isCurrent = true;
    }else if (whoCreate == 'admin') {
      userOrAdmin = 'admin';
      adminPass = document.getElementById('adminPassword').value;
      if (adminPass.length < 8) {
        isCurrent = false;
      }else{
        isCurrent = true;
      }
    }else{
      messageDiv.classList.add('alert-danger');
      messageDiv.classList.remove('alert-success');
      erMessage.innerHTML = 'Updater not known';
    }

    if(!isCurrent){
      messageDiv.classList.add('alert-danger');
      messageDiv.classList.remove('alert-success');
      erMessage.innerHTML = `Check ${userOrAdmin} password`;
    }else if (!isNewPass) {
      messageDiv.classList.add('alert-danger');
      messageDiv.classList.remove('alert-success');
      erMessage.innerHTML = `Check new password`;
    }else if(!verifyPass){
      messageDiv.classList.add('alert-danger');
      messageDiv.classList.remove('alert-success');
      erMessage.innerHTML = 'Verify new password';
    }else{
      messageDiv.classList.add('alert-success');
      messageDiv.classList.remove('alert-danger');
      erMessage.innerHTML = 'Creating password';

      const createPassword = async () =>{
        const url = '/.ht/API/password.php?passwordRelated';
        var encyDat = {
          'ePID' : `${ePID}`,
          'newPassword' : `${newPass}`,
          'function' : 'creation',
          'adminPassword' : `${adminPass}`,
          'editor' : `${whoCreate}`
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
          console.log(data);
          if (data.Result) {
            messageDiv.classList.remove('alert-danger');
            messageDiv.classList.add('alert-success');
            erMessage.innerHTML = `${data.message}`;
            setTimeout(function(){
              location.reload();
            }, 3000);
          }else{
            messageDiv.classList.add('alert-danger');
            messageDiv.classList.remove('alert-success');
            erMessage.innerHTML = `${data.message}`;
          }
        }else{
          messageDiv.classList.add('alert-danger');
          messageDiv.classList.remove('alert-success');
          erMessage.innerHTML = `${data.message}`;
        }
      }
      createPassword();

    }
  }

  updatePassword(whoUpdated){
    var erMessage = document.getElementById('peditmessage');
    var errorDiv = document.querySelector('#pErrorDiv');
    var messageDiv= document.getElementById('pErrorMessage');
    errorDiv.style.display = 'block';
    var currentPassword;
    var userOrAdmin;
    var isCurrent;
    if (whoUpdated == 'user') {
      isCurrent = isCurrentPass;
      userOrAdmin = 'current';
    }else if (whoUpdated == 'admin') {
      userOrAdmin = 'admin';
      currentPassword = document.getElementById('adminPassword').value;
      if (currentPassword.length < 8) {
        isCurrent = false;
      }else{
        isCurrent = true;
      }
    }else{
      messageDiv.classList.add('alert-danger');
      messageDiv.classList.remove('alert-success');
      erMessage.innerHTML = 'Updater not known';
    }

    if (!isCurrent) {
      messageDiv.classList.add('alert-danger');
      messageDiv.classList.remove('alert-success');
      erMessage.innerHTML = `Check ${userOrAdmin} password`;
    }else if(!isNewPass){
      messageDiv.classList.add('alert-danger');
      messageDiv.classList.remove('alert-success');
      erMessage.innerHTML = 'Check new password';
    }else if(!verifyPass){
      messageDiv.classList.add('alert-danger');
      messageDiv.classList.remove('alert-success');
      erMessage.innerHTML = 'Verify new password';
    }else{
      messageDiv.classList.remove('alert-danger');
      messageDiv.classList.add('alert-success');
      erMessage.innerHTML = 'Upadting password...';
      const updatePassword = async () =>{
        const url = '/.ht/API/password.php?passwordRelated';
        var encyDat = {
          'ePID' : `${ePID}`,
          'newPassword' : `${newPass}`,
          'currentPassword' : `${currentPassword}`,
          'function' : 'updation',
          'editor' : `${whoUpdated}`
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
            messageDiv.classList.remove('alert-danger');
            messageDiv.classList.add('alert-success');
            erMessage.innerHTML ='Password updated ';
            setTimeout(function(){
              location.reload();
            }, 3000);
          }else{
            messageDiv.classList.add('alert-danger');
            messageDiv.classList.remove('alert-success');
            erMessage.innerHTML = `${data.message}`;
          }
        }else{
            messageDiv.classList.add('alert-danger');
            messageDiv.classList.remove('alert-success');
            erMessage.innerHTML = 'There is an error';
        }
      }
      updatePassword();
    }
  }

  enableEditing(){
    var editFields = document.getElementById('personalInfoEdit');
    var infoFields = document.getElementById('personalInfoShow');

    editFields.style.display = 'block';
    infoFields.style.display = 'none';
  }

  cancelEditing(){
    var editFields = document.getElementById('personalInfoEdit');
    var infoFields = document.getElementById('personalInfoShow');

    infoFields.style.display = 'block';
    editFields.style.display = 'none';
  }

}

let update = new updateDetails();

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


  
   