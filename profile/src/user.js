class showMenus{
    constructor(x){
        var params = new URLSearchParams(window.location.search);
        this.optValue = params.get('opt');
        this.dashboardMenu = document.querySelector('#dashboardMenu');
        this.dashboardDiv = document.querySelector('#dashboardDiv');
        this.croppie = null;
        this.whoIs = null;
        if (typeof adminLogged !== 'undefined') {
          if (adminLogged) {
            this.whoIs = 'admin';
          }
        }else if(typeof userLogged !== 'undefined'){
          if (userLogged) {
            this.whoIs = 'user';
          }
        }
        // check the hash and display what to show
        if (this.optValue == '' || this.optValue === null || this.optValue === 'undefined') {
            // Stay on dashboard
            this.dashboardMenu.classList.add('active');
            this.dashboardDiv.style.display = 'block';
            this.dashboardMenu.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }else{
            var showDiv = document.getElementById(`${this.optValue}Div`);
            var showMenu = document.getElementById(`${this.optValue}Menu`);
            showMenu.classList.add('active');
            showDiv.style.display = 'block';
            showMenu.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    reload(){
        window.location.href =  window.location.href;
    }

    uploadDp(){
      document.getElementById('uploadDP').style.display = 'flex';
      disbaleScroll();
    }
    cancelDpUpload(){
      document.getElementById('uploadDP').style.display = 'none';
      enableScroll();
      
    }

    uploadImage() {
      document.querySelector('#uploadDbButton').innerHTML = `Upload`;
      var removeImageButton = document.querySelector('#removeImage');
      var uploadDbButton = document.querySelector('#uploadDbButton');
      document.querySelector('.uploadDpDiv .uploadDpContainer #message').style.display = 'block';
      
      removeImageButton.style.display = 'block';
      removeImageButton.addEventListener('click', () => {
        this.removeImage();
      });

      uploadDbButton.style.display = 'block';
      document.getElementById('uploadFileLabel').style.display = 'none';
      var aspectRatio = 1; // Specify your desired aspect ratio here
      var boundaryWidth = 230; // Width of the boundary
      var boundaryHeight = boundaryWidth / aspectRatio; // Calculate height based on the aspect ratio
  
      this.croppie = new Croppie(document.getElementById('croppieContainer'), {
        viewport: { width: '100%', height: '100%', type: 'free' },
        boundary: { width: boundaryWidth, height: boundaryHeight },
        enableOrientation: true
      });
  
      var fileInput = document.getElementById('uploadInputFile');
      var file = fileInput.files[0];
      var reader = new FileReader();
  
      reader.onload = (e) => {
        var image = new Image();
        image.src = e.target.result;
  
        image.onload = () => {
          this.croppie.bind({
            url: image.src,
            orientation: 1
          });
        };
      };
      reader.readAsDataURL(file);
      uploadDbButton.addEventListener('click', () => {
        this.croppie.result({ format: 'base64', size: 'original' }).then((base64Image) => {
          var binaryImage = this.dataURItoBlob(base64Image);
          this.uploadToServer(binaryImage); // Pass the base64Image to uploadToServer()
        });
      });
    }

    uploadToServer(binaryImage) {
        var formData = new FormData();


        // Determine the file extension based on the MIME type
        var mimeString = binaryImage.type;
        var fileExtension = mimeString.substring(mimeString.lastIndexOf('/') + 1);
        formData.append('DPimage', binaryImage, 'image'+fileExtension);
    
        // Append other data to the FormData object
        formData.append('ePID', ePID);
        formData.append('ext', fileExtension);
        formData.append('type', 'dpUpload');
        formData.append('editor', this.whoIs);
        // Send the FormData object to the server using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../.ht/API/upload.php', true);


        // Track the progress of the upload
        xhr.upload.addEventListener('progress', (event) => {
          if (event.lengthComputable) {
            var percentComplete = (event.loaded / event.total) * 100;
            document.querySelector('#uploadDbButton').innerHTML = `Uploading ${percentComplete.toFixed(2)}%`;
            // Update the UI or perform actions based on the progress
          }
        });


        // Handle the upload completion
        xhr.addEventListener('load', () => {
          if (xhr.status === 200) {
            // Upload successful, handle the response
            var response = JSON.parse(xhr.responseText);
            if (response.Result) {
              location.reload();
            }else{
              document.querySelector('.uploadDpDiv .uploadDpContainer #errorMessage').style.display = 'block' ;
              document.querySelector('.uploadDpDiv .uploadDpContainer #errorMessage').innerHTML = 'Someting Went Wrong' ;
            }
            
            // Do something with the response
          } else {
            // Upload failed, handle the error
            console.log('Upload failed. Error: ' + xhr.status);
          }
        });
    
        xhr.send(formData);
    }
    
    dataURItoBlob(dataURI) {
      // Convert base64 to raw binary data held in a string
      var byteString = atob(dataURI.split(',')[1]);
    
      // Separate the MIME type from the base64 data
      var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
    
      // Write the bytes of the string to an ArrayBuffer
      var ab = new ArrayBuffer(byteString.length);
      var ia = new Uint8Array(ab);
      for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
      }
    
      // Create a Blob object from the ArrayBuffer
      return new Blob([ab], { type: mimeString });
    }
    

    removeImage() {
      document.querySelector('#uploadDbButton').style.display = 'none';
      document.querySelector('#removeImage').style.display = 'none';
      if (this.croppie) {
        this.croppie.destroy(); // Destroy the Croppie instance
        this.croppie = null;
      }
      document.getElementById('uploadInputFile').value = ''; // Clear the file input value
      document.getElementById('uploadFileLabel').style.display = 'flex'; // Show the upload file label
      // Add any additional code to reset the UI or perform other tasks
    }
    
      
}
document.addEventListener('DOMContentLoaded', function() {
    new showMenus();
});


function follow(){
  var followButton = document.getElementById('followButton');
  followButton.innerHTML = 'Wait...';

  const followUser = async () =>{
      const url = '/.ht/API/follow.php/?follow';
      var encyDat = {
        'username': `${currentUsername}`
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
          followButton.innerHTML = 'Followed';
          followButton.onclick.setAttribute( "onClick", "javascript: unfollow();");
        }else{
        followButton.innerHTML = `${data.message}`;
        }
      }else{
          followButton.innerHTML = `${data.message}`;
      }
    }
    followUser();
}


function unfollow(){
  var followButton = document.getElementById('followButton');
  followButton.innerHTML = 'Wait...';

  const unfollowUser = async () =>{
      const url = '/.ht/API/follow.php/?unfollow';
      var encyDat = {
        'username': `${currentUsername}`
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
          followButton.innerHTML = 'Follow';
          followButton.onclick.setAttribute( "onClick", "javascript: follow();");
        }else{
          followButton.innerHTML = "Can't  unfollow";
        }
      }else{
          followButton.innerHTML = "Can't  unfollow";
      }
    }
    unfollowUser();
}

