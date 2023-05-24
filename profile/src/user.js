









class showMenus{
    constructor(){
        document.querySelector('#progressBar').style.display = 'none';
        var params = new URLSearchParams(window.location.search);
        this.optValue = params.get('opt');
        this.dashboardMenu = document.querySelector('#dashboardMenu');
        this.contentMenu = document.querySelector('#contentMenu');
        this.mediaMenu = document.querySelector('#mediaMenu');
        this.privacyMenu = document.querySelector('#privacyMenu');
        this.croppie = null;
    
        this.dashboardDiv = document.querySelector('#dashboardDiv');
        this.contentDiv = document.querySelector('#contentDiv');
        this.mediaDiv = document.querySelector('#mediaDiv');
        this.privacyDiv = document.querySelector('#privacyDiv');

        this.dashboardDiv.style.display = 'none';
        this.contentDiv.style.display = 'none';
        this.mediaDiv.style.display = 'none';
        this.privacyDiv.style.display = 'none';
        this.whoIs = null;
        if (adminLogged) {
          this.whoIs = 'admin';
        }else if(userLogged){
          this.whoIs = 'user';
        }
        // check the hash and display what to show
        if (this.optValue == '' || this.hash === null || this.hash === 'undefined') {
            // Stay on dashboard
            this.dashboardMenu.classList.add('active');
            this.dashboardDiv.style.display = 'block';
        }else if(this.optValue == 'content'){
            this.contentMenu.classList.add('active');
            this.contentDiv.style.display = 'block';
        }else if(this.optValue == 'media'){
            this.mediaMenu.classList.add('active');
            this.mediaDiv.style.display = 'block';
        }else if(this.optValue == 'privacy'){
            this.privacyMenu.classList.add('active');
            this.privacyDiv.style.display = 'block';
        }else{
            this.dashboardMenu.classList.add('active');
            this.dashboardDiv.style.display = 'block';
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
      this.removeImage();
    }

    uploadImage() {
      var removeImageButton = document.querySelector('#removeImage');
      var uploadDbButton = document.querySelector('#uploadDbButton');
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
          this.uploadToServer(base64Image); // Pass the base64Image to uploadToServer()
        });
      });
    }



    uploadToServer(base64Image) {
        var formData = new FormData();
    
        // Append the base64 image to the FormData object

        // Convert the base64 image to a Blob object
        var blob = this.dataURItoBlob(base64Image);

        // Determine the file extension based on the MIME type
        var mimeString = blob.type;
        var fileExtension = mimeString.substring(mimeString.lastIndexOf('/') + 1);
        formData.append('DPimage', blob, 'image'+fileExtension);
    
        // Append other data to the FormData object
        formData.append('ePID', ePID);
        formData.append('ext', fileExtension);
        formData.append('type', 'dpUpload');
        formData.append('editor', this.whoIs);
        // Send the FormData object to the server using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../.ht/API/upload.php', true);
        
        xhr.onreadystatechange = function () {
          if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            // Upload successful, handle the response
            var response = JSON.parse(xhr.responseText);
            console.log(response);
            // Do something with the response
          } else if (xhr.readyState === XMLHttpRequest.DONE && xhr.status !== 200) {
            // Upload failed, handle the error
            console.log('Upload failed. Error: ' + xhr.status);
          }
        };
    
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

