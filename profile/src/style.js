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
                  this.deletingDiv.innerHTML += `<span class="deleting">Deleting Uploads...............<i class="fa-solid fa-check"></i></span>`;
              }else{
                this.deletingDiv.innerHTML += `<span class="deleting">${data.message}...............<i class="fa-solid fa-xmark"></i></span>`;
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
                  this.deletingDiv.innerHTML += `<span class="deleting">Deleting User Contents...............<i class="fa-solid fa-check"></i></span>`;
              }else{
                this.deletingDiv.innerHTML += `<span class="deleting">${data.message}...............<i class="fa-solid fa-xmark"></i></span>`;
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
                  this.deletingDiv.innerHTML += `<span class="deleting">Deleting User Data...............<i class="fa-solid fa-check"></i></span>`;
              }else{
                this.deletingDiv.innerHTML += `<span class="deleting">${data.message}...............<i class="fa-solid fa-xmark"></i></span>`;
              }
            }
            var thisIs = this;
            async function Implementing() {
              thisIs.deletingCriteria.style.display = 'none';
              thisIs.deletingDiv.style.display = 'block';
            
              try {
                await deleteUploads();
                await deleteContents();
                await deleteUserData();
            
                if (thisIs.uploadDeleted && thisIs.contentDeleted && thisIs.userDataDeleted) {
                  thisIs.deletingDiv.innerHTML += `<span class="deleting">Finish Deleting...............<i class="fa-solid fa-check"></i></span>`;
                  setTimeout(function (){
                    location.reload();
                  }, 3000);
                } else {
                  thisIs.deletingDiv.innerHTML += `<span class="deleting">Problem in deleting...............<i class="fa-solid fa-xmark"></i></span>`;
                }
              } catch (error) {
                thisIs.deletingDiv.innerHTML += `<span class="deleting">Something wrong happened...............<i class="fa-solid fa-xmark"></i></span>`;
              }
            }
            
          Implementing();
            
        }else{
            this.errorDiv.style.display = 'block';
            this.dErrorDivInside.style.display = 'block';
            this.dispMessage.innerHTML = 'Username not correct';
        }
    }


    deleteWithPassword(){
        var password = document.querySelector('.delete-account #currentPasswordDelete');
        if (password.value.length <= 1) {
            this.errorDiv.style.display = 'block';
            this.dErrorDivInside.style.display = 'block';
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
                    this.deletingDiv.innerHTML += `<span class="deleting">Deleting Uploads...............<i class="fa-solid fa-check"></i></span>`;
                }else{
                  this.deletingDiv.innerHTML += `<span class="deleting">${data.message}...............<i class="fa-solid fa-xmark"></i></span>`;
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
                    this.deletingDiv.innerHTML += `<span class="deleting">Deleting User Contents...............<i class="fa-solid fa-check"></i></span>`;
                }else{
                  this.deletingDiv.innerHTML += `<span class="deleting">${data.message}...............<i class="fa-solid fa-xmark"></i></span>`;
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
                    this.deletingDiv.innerHTML += `<span class="deleting">Deleting User Data...............<i class="fa-solid fa-check"></i></span>`;
                }else{
                  this.deletingDiv.innerHTML += `<span class="deleting">${data.message}...............<i class="fa-solid fa-xmark"></i></span>`;
                }
            }

            var thisIs = this;
            async function Implementing() {
              thisIs.deletingCriteria.style.display = 'none';
              thisIs.deletingDiv.style.display = 'block';
            
              try {
                await deleteUploads();
                await deleteContents();
                await deleteUserData();
            
                if (thisIs.uploadDeleted && thisIs.contentDeleted && thisIs.userDataDeleted) {
                  thisIs.deletingDiv.innerHTML += `<span class="deleting">Finish Deleting...............<i class="fa-solid fa-check"></i></span>`;
                  setTimeout(function (){
                    location.reload();
                  }, 3000);
                } else {
                  thisIs.deletingDiv.innerHTML += `<span class="deleting">Problem in deleting...............<i class="fa-solid fa-xmark"></i></span>`;
                }
              } catch (error) {
                console.log(error);
                thisIs.deletingDiv.innerHTML += `<span class="deleting">Something wrong happened...............<i class="fa-solid fa-xmark"></i></span>`;
              }
            }
            
          Implementing();
        }
    }
}
var deleteAccount = new DeleteAccount();