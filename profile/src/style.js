class DeleteAccount{

    constructor(){
        this.errorDiv = document.querySelector('#dErrorDiv');
        this.dErrorDivInside = document.querySelector('#dErrorDiv #dErrorDivInside');
        this.deletingDiv = document.querySelector('.delete-account .deleting-progress');
        this.deletingCriteria = document.querySelector('.delete-account .delete-criteria');
        this.dispMessage = document.getElementById('editDelmessage');
        this.errorDiv.style.display = 'none';
        this.dErrorDivInside.style.display = 'none';
        this.uploadDeleted;
        this.contentDeleted;
        this.userDataDeleted;
    }

    deleteWithUsername(){
        var username = document.querySelector('.delete-account #currentUsernameDelete');
        if (username.value == currentUsername) {
            this.deletingDiv.style.display = 'block';
            this.deletingCriteria.style.display = 'none';
            // Deleting Uploads
            const deleteUploads = async()=>{
                const url = '/.ht/API/deleteAccount.php';
                var encyDat = {
                  'personID' : `${ePID}`,
                  'username' : `${username.value}`,
                  'name' : 'uploads',
                  'with': 'username'
                };
                const response = await fetch(url, {
                    method: 'post',
                    headers: {
                      'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(encyDat)
                  });
                var data = await response.json();
                if (data.Result) {
                    this.uploadDeleted = true;
                    document.querySelector('#deleteUploads i').style.display= 'inline';
                }else{
                    uploadDeleted = false;
                    document.querySelector('#deleteUploads i').style.display= 'inline';
                    document.querySelector('#deleteUploads i').style.display.classList.remove('fa-check');
                    document.querySelector('#deleteUploads i').style.display.classList.add('fa-xmark');
                }
            }
            // deleting Contents
            const deleteContents = async()=>{
                const url = '/.ht/API/deleteAccount.php';
                var encyDat = {
                  'personID' : `${ePID}`,
                  'username' : `${username.value}`,
                  'name' : 'contents',
                  'with': 'username'
                };
                const response = await fetch(url, {
                    method: 'post',
                    headers: {
                      'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(encyDat)
                  });
                var data = await response.json();
                if (data.Result) {
                    this.contentDeleted = true;
                    document.querySelector('#deleteContents i').style.display= 'inline';
                }else{
                    document.querySelector('#deleteContents i').style.display= 'inline';
                    document.querySelector('#deleteContents i').style.display.classList.remove('fa-check');
                    document.querySelector('#deleteContents i').style.display.classList.add('fa-xmark');
                }
            }

            // Deleting User Data
            const deleteUserData = async()=>{
                const url = '/.ht/API/deleteAccount.php';
                var encyDat = {
                  'personID' : `${ePID}`,
                  'username' : `${username.value}`,
                  'name' : 'userData',
                  'with': 'username'
                };
                const response = await fetch(url, {
                    method: 'post',
                    headers: {
                      'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(encyDat)
                  });
                var data = await response.json();
                if (data.Result) {
                  this.userDataDeleted = true;
                    document.querySelector('#deleteUserData i').style.display= 'inline';
                }else{
                    document.querySelector('#deleteUserData i').style.display= 'inline';
                    document.querySelector('#deleteUserData i').style.display.classList.remove('fa-check');
                    document.querySelector('#deleteUserData i').style.display.classList.add('fa-xmark');
                }
            }

            // Implementing
            deleteUploads();
            deleteContents();
            deleteUserData();

            if (this.uploadDeleted && this.contentDeleted && this.userDataDeleted) {
                document.querySelector('#deleteFinish i').style.display= 'inline';
            }else{
                this.deletingDiv.style.display = 'none';
                this.deletingCriteria.style.display = 'block';
                this.errorDiv.style.display = 'block';
                this.dErrorDivInside.style.display = 'block';
                this.dispMessage.innerHTML = 'Problem With deleting';
            }
        }else{
            this.errorDiv.style.display = 'block';
            this.dErrorDivInside.style.display = 'block';
            this.dispMessage.innerHTML = 'Username not correct';
        }
    }


    deleteWithPassword(){
        var password = document.querySelector('.delete-account #currentPasswordDelete');
        if (password.value.length <= 1) {
            this.dispMessage.innerHTML = 'Password required';
        }else{
            this.deletingDiv.style.display = 'block';
            this.deletingCriteria.style.display = 'none';
            // Deleting Uploads
            const deleteUploads = async()=>{
                const url = '/.ht/API/deleteAccount.php';
                var encyDat = {
                  'personID' : `${ePID}`,
                  'password' : `${password.value}`,
                  'name' : 'uploads',
                  'with': 'password'
                };
                const response = await fetch(url, {
                    method: 'post',
                    headers: {
                      'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(encyDat)
                  });
                var data = await response.json();
                if (data.Result) {
                    this.uploadDeleted = true;
                    document.querySelector('#deleteUploads i').style.display= 'block';
                }else{
                    document.querySelector('#deleteUploads i').style.display= 'block';
                    document.querySelector('#deleteUploads i').style.display.classList.remove('fa-check');
                    document.querySelector('#deleteUploads i').style.display.classList.add('fa-xmark');
                }
            }
            // deleting Contents
            const deleteContents = async()=>{
                const url = '/.ht/API/deleteAccount.php';
                var encyDat = {
                  'personID' : `${ePID}`,
                  'password' : `${password.value}`,
                  'name' : 'contents',
                  'with': 'password'
                };
                const response = await fetch(url, {
                    method: 'post',
                    headers: {
                      'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(encyDat)
                  });
                var data = await response.json();
                if (data.Result) {
                    this.contentDeleted = true;
                    document.querySelector('#deleteContents i').style.display= 'block';
                }else{
                    document.querySelector('#deleteContents i').style.display= 'block';
                    document.querySelector('#deleteContents i').style.display.classList.remove('fa-check');
                    document.querySelector('#deleteContents i').style.display.classList.add('fa-xmark');
                }
            }

            // Deleting User Data
            const deleteUserData = async()=>{
                const url = '/.ht/API/deleteAccount.php';
                var encyDat = {
                  'personID' : `${ePID}`,
                  'password' : `${password.value}`,
                  'name' : 'userData',
                  'with': 'password'
                };
                const response = await fetch(url, {
                    method: 'post',
                    headers: {
                      'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(encyDat)
                  });
                var data = await response.json();
                if (data.Result) {
                    this.userDataDeleted = true;
                    document.querySelector('#deleteUserData i').style.display= 'block';
                }else{
                    document.querySelector('#deleteUserData i').style.display= 'block';
                    document.querySelector('#deleteUserData i').style.display.classList.remove('fa-check');
                    document.querySelector('#deleteUserData i').style.display.classList.add('fa-xmark');
                }
            }

            // Implementing
            deleteUploads();
            deleteContents();
            deleteUserData();

            if (this.uploadDeleted && this.contentDeleted && this.userDataDeleted) {
                document.querySelector('#deleteFinish i').style.display= 'block';
            }else{
                this.errorDiv.style.display = 'block';
                this.dErrorDivInside.style.display = 'block';
                this.dispMessage.innerHTML = 'Problem With deleting';
            }
        }
    }
}
var deleteAccount = new DeleteAccount();