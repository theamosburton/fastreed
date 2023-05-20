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
    this.validateName();
    this.checkDOB()
    this.checkUsername();
    this.validateGender();
    this.checkWebsite();
    this.checkAbout();
  }

  validateName() {
    var nameRegex =/^[a-zA-Z]+(?: [a-zA-Z]+)*(?:\. [a-zA-Z]+)?$/;
    this.fullName = document.querySelector('#fullName').value;
    var uError = document.querySelector('#nameErrorMessage');
    uError.style.color = 'orange';
    uError.innerHTML = 'Checking...';
    if(nameRegex.test(this.name)){
      uError.innerHTML = '&#x2713;';
      uError.style.color = 'lime';
      this.isFullName = true;
    }else{
      uError.innerHTML = 'Invalid Name';
    }
  }


  validateGender(){
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
    var message = document.querySelector('#message');
    messageDiv.classList.remove('alert-success');
    messageDiv.classList.add('alert-danger');
    messageDiv.style.display = 'block';
    mainDiv.style.display = 'none';
    if (this.isFullName) {
      if (this.isUsername) {
        if (this.isEmail) {
          if (this.DOB) {
            mainDiv.style.display = 'block';
            message.innerHTML = 'Updating...';
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
                'editor':'admin'
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
                  mainDiv.style.display = 'block';
                  messageDiv.classList.add('alert-success');
                  messageDiv.classList.remove('alert-danger');
                  message.innerHTML = 'Updated Successfully';
                  setTimeout(function(){
                    window.location.href = `/users/${$username}`;
                  }, 3000);
                }else{
                  mainDiv.style.display = 'block';
                  message.innerHTML = 'Somthing Wrong at our end';
                }
              }else{
                mainDiv.style.display = 'block';
                message.innerHTML = 'Somthing Wrong at our end';
              }
            }
            updateDetails();
          }else{
            mainDiv.style.display = 'block';
              message.innerHTML = 'Problem With Date of Birth';
          }
        }else{
          mainDiv.style.display = 'block';
            message.innerHTML = 'Problem With Email ID';
        }
      }else{
        mainDiv.style.display = 'block';
        message.innerHTML = 'Problem With username';
      }
    }else{
      mainDiv.style.display = 'block';
      message.innerHTML = 'Problem With Your Name';
    }
  }



  editByUser(){
    var messageDiv = document.querySelector('#uAlert');
    var mainDiv = document.querySelector('#updateAlert');
    var message = document.querySelector('#message');
    messageDiv.classList.remove('alert-success');
    messageDiv.classList.add('alert-danger');
    messageDiv.style.display = 'block';
    mainDiv.style.display = 'none';
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
          if (this.DOB) {
            mainDiv.style.display = 'block';
            message.innerHTML = 'Updating...';
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
                'editor':'user'
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
                  mainDiv.style.display = 'block';
                  messageDiv.classList.add('alert-success');
                  messageDiv.classList.remove('alert-danger');
                  message.innerHTML = 'Updated Successfully';
                  setTimeout(function(){
                    location.reload();
                  }, 3000);
                }else{
                  mainDiv.style.display = 'block';
                  message.innerHTML = 'Somthing Wrong at our end';
                }
              }else{
                mainDiv.style.display = 'block';
                message.innerHTML = 'Somthing Wrong at our end';
              }
            }
            updateDetails();
          }else{
            mainDiv.style.display = 'block';
              message.innerHTML = 'Problem With Date of Birth';
          }
        }else{
          mainDiv.style.display = 'block';
            message.innerHTML = 'Problem With Email ID';
        }
      }else{
        mainDiv.style.display = 'block';
        message.innerHTML = 'Problem With username';
      }
    }else{
      mainDiv.style.display = 'block';
      message.innerHTML = 'Problem With Your Name';
    }
  }

}

let update = new updateDetails();



  
   