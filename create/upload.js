class uploadMedia{
   //*******Photo/Video Upload************/
   constructor(){
    this.whoIs = whoIs;
    var fileInput = document.getElementById("uploadNewMedia");
    this.uploadingBar = document.getElementById('uploadingBar');
    this.uploadingMessage = document.querySelector('#uploadingBar #uploadMessage');
    this.uploadProgress = document.querySelector('#uploadingBar #uploadProgress #progress');
    this.uploadingBar.style.display = 'block';
    this.uploadNew = document.querySelector('.refreshUpload #uploadNew input');

    if (fileInput.files && fileInput.files[0]) {
      if (fileInput.files[0].type.startsWith('image/')) {
        this.uploadImages(fileInput);
      }else if(fileInput.files[0].type.startsWith('video/')){
        this.uploadVideo(fileInput);
      }else{
        this.uploadingBar.classList.add('warning');
        this.uploadingMessage.innerHTML = 'Video or Photo required';
      }
    }
  }

  //*******Image Upload************/ 
  uploadImages(fileInput){
    var self = this;
    if (self.uploadingBar.classList.contains('warning')) {
        self.uploadingBar.classList.remove('warning');
    }
    self.uploadingMessage.innerHTML = 'Uploading...';
    self.uploadNew.style.display = 'none';
    self.uploadProgress.style.display = 'block';
    var binaryImage;
    var reader = new FileReader();
    reader.onload = function (e) {
      binaryImage = e.target.result;
      var blobImage = self.dataURItoBlob(binaryImage)
      self.uploadImageToServer(blobImage);
    };
    reader.readAsDataURL(fileInput.files[0]);
  }

  uploadImageToServer(binaryFile) {
    var self = this;
      var formData = new FormData();
      // Determine the file extension based on the MIME type
      var mimeString = binaryFile.type;
      var fileExtension = mimeString.substring(mimeString.lastIndexOf('/') + 1);
      formData.append('media', binaryFile, 'uploadFile.'+fileExtension);
  
      // Append other data to the FormData object
      formData.append('ePID', ePID);
      formData.append('ext', fileExtension);
      formData.append('type', 'image');
      formData.append('editor', this.whoIs);
      // Send the FormData object to the server using AJAX
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '../.ht/API/upload.php', true);


      // Track the progress of the upload
      xhr.upload.addEventListener('progress', (event) => {
        if (event.lengthComputable) {
          var percentComplete = (event.loaded / event.total) * 100;
          self.uploadProgress.style.display = 'block';
          self.uploadProgress.width = `${percentComplete.toFixed(2)}%`;
          self.uploadNew.style.display = 'flex';
        }
      });

      xhr.addEventListener('load', () => {
        if (xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          if (response.Result) {
            self.uploadingBar.style.display = 'none';
            self.uploadNew.style.display = 'flex';
            uploadsDataClass.fetchUploads();
          }else{
            self.uploadingBar.style.display = 'flex';
            self.uploadingBar.classList.add('warning');
            self.uploadingMessage.innerHTML = `${response.message}`;
            self.uploadNew.style.display = 'flex';
          }
        } else {
            self.uploadingBar.style.display = 'flex';
            self.uploadingBar.classList.add('warning');
            self.uploadingMessage.innerHTML = 'Something went wrong';
            self.uploadNew.style.display = 'flex';
            console.log('Upload failed. Error: ' + xhr.status);
        }
      });
  
      xhr.send(formData);
  }
  //*******Image Upload************/ 


  //*******Video Upload************/ 
  uploadVideo(fileInput) {
    var self = this;
    if (self.uploadingBar.classList.contains('warning')) {
        self.uploadingBar.classList.remove('warning');
    }
    self.uploadingMessage.innerHTML = 'Uploading...';
    self.uploadNew.style.display = 'none';
    self.uploadProgress.style.display = 'block';
    var reader = new FileReader();
    reader.onload = function (e) {
      var file = fileInput.files[0];
      self.uploadVideoToServer(file);
    };
    reader.readAsDataURL(fileInput.files[0]);
  }

  uploadVideoToServer(file) {
    var self = this;
    var filename = file.name;
    var extension = filename.split('.').pop();
    var formData = new FormData();
    formData.append('type', 'video');
    formData.append('ePID', ePID);
    formData.append('ext', extension);
    formData.append('editor', this.whoIs);
    formData.append('media', file);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../.ht/API/upload.php', true);
    xhr.upload.addEventListener('progress', (event) => {
      if (event.lengthComputable) {
        var percentComplete = (event.loaded / event.total) * 100;
        self.uploadProgress.width = `${percentComplete.toFixed(2)}%`;
      }
    });

    xhr.addEventListener('load', () => {
      if (xhr.status === 200) {
        // Upload successful, handle the response
        var response = JSON.parse(xhr.responseText);
        if (response.Result) {
            self.uploadingBar.style.display = 'none';
            self.uploadNew.style.display = 'flex';
            uploadsDataClass.fetchUploads();
        }else{
            self.uploadingBar.style.display = 'flex';
            self.uploadingBar.classList.add('warning');
            self.uploadingMessage.innerHTML = `${response.message}`;
            self.uploadNew.style.display = 'flex';
        }
      } else {
        self.uploadingBar.style.display = 'flex';
        self.uploadingBar.classList.add('warning');
        self.uploadingMessage.innerHTML = 'Something went wrong';
        self.uploadNew.style.display = 'flex';
        console.log('Upload failed. Error: ' + xhr.status);
      }
    });
    xhr.send(formData);

  }

  
  //*******Video Upload************/ 

  //*******Photo/Video Upload************/
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
}
 