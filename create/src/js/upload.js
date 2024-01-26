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

    console.log(this.uploadProgress);

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
          self.uploadProgress.style.width = `${percentComplete.toFixed(2)}%`;
        }
      });

      xhr.addEventListener('load', () => {
        if (xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          if (response.Result) {
            self.uploadingBar.style.display = 'none';
            self.uploadNew.style.display = 'flex';
            fetchMultimedia.fetchUploads();
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
        self.uploadProgress.style.width = `${percentComplete.toFixed(2)}%`;
      }
    });

    xhr.addEventListener('load', () => {
      if (xhr.status === 200) {
        // Upload successful, handle the response
        var response = JSON.parse(xhr.responseText);
        if (response.Result) {
            self.uploadingBar.style.display = 'none';
            self.uploadNew.style.display = 'flex';
            fetchMultimedia.fetchUploads();
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


// To fetch media
class fetchMedia{
  constructor(){
    this.uploadsData = {};
    this.uploads = {};
    this.uploadsCount;
    this.allMediaIDs = [];
    this.totalMedia = 0;
    this.fetchUploads();
  }

  fetchUploads(){
    var refresh = document.getElementById('rotateRefresh');
    refresh.classList.add('infinite-rotation');
    var self = this;
    async function getUploadsData(){
      const logUrl = '/.ht/API/getUploads.php';
      var encyDat = {};
      const response = await fetch(logUrl, {
          method: 'post',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(encyDat)
        });
      var upData = await response.json();
      if (upData) {
        self.uploadsCount = upData.length;
        for (let i = 0; i < upData.length; i++) {
          self.uploads['up'+ i] = {};
          self.uploads['up' + i].link = upData[i].path;
          self.uploads['up' + i].type = upData[i].what;
          self.allMediaIDs[i] = upData[i].mediaID;
        }
        if (self.totalMedia != self.uploadsCount) {
          
          self.showUploads();
        }else{
          var refreshElement = document.getElementById('rotateRefresh');
          refreshElement.classList.remove('infinite-rotation');
        }
        
      }else{

      }
    }
    getUploadsData();
 }


 showUploads() {
  return new Promise((resolve) => {
    var uploadedDiv = document.getElementById('uploads');
    uploadedDiv.innerHTML = '';
    this.uploadsCount = Object.keys(this.uploads).length;
    var promises = [];

    for (let u = 0; u < this.uploadsCount; u++) {
      var ulink = this.uploads['up' + u].link;
      var type = this.uploads['up' + u].type;

      if (type == 'image') {
        const fetchPhotos = async () => {
          const response = await fetch(ulink);
          const blob = await response.blob();
          var imgURL = URL.createObjectURL(blob);
          this.uploadsData['upload' + u] = {};
          this.uploadsData['upload' + u].orglink = ulink;
          var uploadedDiv = document.getElementById('uploads');
          uploadedDiv.innerHTML += `
            <div draggable="true" class="uploadContent" id="media${u}" onclick="edits.selectMedia('${imgURL}', 'image', '${ulink}')">
                <img src="${imgURL}">
            </div>
            `;
        };

        promises.push(fetchPhotos());
      } else if (type == 'video') {
        const fetchVideos = async () => {
          const response = await fetch(ulink);
          const blob = await response.blob();
          var videoURL = URL.createObjectURL(blob);
          this.uploadsData['upload' + u] = {};
          this.uploadsData['upload' + u].orglink = ulink;
          var uploadedDiv = document.getElementById('uploads');
          uploadedDiv.innerHTML += `
                <div draggable="true" class="uploadContent" id="media${u}" onclick="edits.selectMedia('${videoURL}', 'video', '${ulink}')">
                    <video>
                        <source src="${videoURL}" type="video/mp4">
                    </video>
                    <div class="fileInfo">
                      <svg aria-label="Clip" color="rgb(255, 255, 255)" fill="rgb(255, 255, 255)" height="18" role="img" viewBox="0 0 24 24" width="18">
                      <path d="m12.823 1 2.974 5.002h-5.58l-2.65-4.971c.206-.013.419-.022.642-.027L8.55 1Zm2.327 0h.298c3.06 0 4.468.754 5.64 1.887a6.007 6.007 0 0 1 1.596 2.82l.07.295h-4.629L15.15 1Zm-9.667.377L7.95 6.002H1.244a6.01 6.01 0 0 1 3.942-4.53Zm9.735 12.834-4.545-2.624a.909.909 0 0 0-1.356.668l-.008.12v5.248a.91.91 0 0 0 1.255.84l.109-.053 4.545-2.624a.909.909 0 0 0 .1-1.507l-.1-.068-4.545-2.624Zm-14.2-6.209h21.964l.015.36.003.189v6.899c0 3.061-.755 4.469-1.888 5.64-1.151 1.114-2.5 1.856-5.33 1.909l-.334.003H8.551c-3.06 0-4.467-.755-5.64-1.889-1.114-1.15-1.854-2.498-1.908-5.33L1 15.45V8.551l.003-.189Z" fill-rule="evenodd"></path>
                      </svg>'
                    </div>
                </div>
            `;
        };

        promises.push(fetchVideos());
      }
    }

    

    Promise.all(promises).then(() => {
      this.totalMedia = this.uploadsCount;
      var refreshElement = document.getElementById('rotateRefresh');
      refreshElement.classList.remove('infinite-rotation');
      resolve(); // Resolving the promise when all uploads are loaded and displayed
    });
  });
}


}
var fetchMultimedia = new fetchMedia();