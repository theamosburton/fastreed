class showMenus{
    constructor(){
      this.hashValue = window.location.hash.substr(1);
      this.optValue = this.hashValue;
      this.dashboardMenu = document.querySelector('#dashboardMenu');
      this.dashboardDiv = document.querySelector('#dashboardDiv');
      this.othersMenus = document.getElementsByClassName('menus');
      this.othersDivs = document.getElementsByClassName('contentView');

      this.croppie = null;
      this.whoIs = null;
      if (adminLogged) {
        this.whoIs = 'Admin';
      }else{
        this.whoIs = 'User';
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

    // For dp upload only
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
          this.uploadImageToServer(binaryImage, 'dpUpload', 'justArgument'); // Pass the base64Image to uploadToServer()
        });
      });
    }

    //*******Photo/Video Upload************/
    uploadMedia() {
      var fileInput = document.getElementById("uploadInputImage");
      var uploadProgressDiv = document.getElementById('uploadProgressDiv');
      if (document.getElementById('noUploads')) {
        document.getElementById('noUploads').remove();
      }

      if (fileInput.files && fileInput.files[0]) {
        if (fileInput.files[0].type.startsWith('image/')) {
          this.uploadImages(fileInput);
        }else if(fileInput.files[0].type.startsWith('video/')){
          this.uploadVideo(fileInput);
        }else{
          var uploadBox = document.getElementById('tempUploadBox');
          uploadBox.style.display = 'flex';
          uploadBox.style.display = 'flex';
          uploadProgressDiv.innerHTML = 'Video or Image';
          uploadProgressDiv.style.backgroundColor = 'red';
          uploadProgressDiv.style.width = '100%';
        }

      }
    }

    //*******Image Upload************/
    uploadImages(fileInput){
      var self = this;
      var binaryImage;
      var imagePreview = document.getElementById("tempImage");
      imagePreview.innerHTML = '';
      var tempBox = document.getElementById('tempUploadBox');
      tempBox.style.display = 'flex';
      var reader = new FileReader();
      reader.onload = function (e) {
        var imgElement = document.createElement("img");
        imgElement.src = e.target.result;
        imgElement.style.maxWidth = "100%";
        imgElement.style.maxHeight = "100%";
        imagePreview.appendChild(imgElement);
        binaryImage = e.target.result;
        var blobImage = self.dataURItoBlob(binaryImage)
        console.log(blobImage);
        self.uploadImageToServer(blobImage, 'image', tempBox);
      };
      reader.readAsDataURL(fileInput.files[0]);
    }

    uploadImageToServer(binaryFile, utype, tempBox) {
        var formData = new FormData();
        // Determine the file extension based on the MIME type
        var mimeString = binaryFile.type;
        var fileExtension = mimeString.substring(mimeString.lastIndexOf('/') + 1);
        formData.append('media', binaryFile, 'uploadFile.'+fileExtension);

        // Append other data to the FormData object
        formData.append('ePID', ePID);
        formData.append('ext', fileExtension);
        formData.append('type', utype);
        formData.append('editor', this.whoIs);
        // Send the FormData object to the server using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../.ht/API/upload.php', true);


        // Track the progress of the upload
        if (utype == 'dpUploads') {
          xhr.upload.addEventListener('progress', (event) => {
            if (event.lengthComputable) {
              var percentComplete = (event.loaded / event.total) * 100;
              document.querySelector('#uploadDbButton').innerHTML = `Uploading ${percentComplete.toFixed(2)}%`;
              // Update the UI or perform actions based on the progress
            }
          });
        }else if(utype == 'image'){
          xhr.upload.addEventListener('progress', (event) => {
            if (event.lengthComputable) {
              var percentComplete = (event.loaded / event.total) * 100;
              var progress = document.querySelector('#uploadProgressDiv');
              progress.innerHTML = `${percentComplete.toFixed(2)}%`;
              progress.style.width = `${percentComplete.toFixed(2)}%`;
              // Update the UI or perform actions based on the progress
            }
          });
        }
        xhr.addEventListener('load', () => {
          if (xhr.status === 200) {
            // Upload successful, handle the response
            var response = JSON.parse(xhr.responseText);
            if (response.Result) {
              if (utype == 'dpUploads') {
                document.querySelector('#uploadDbButton').innerHTML = `Processing...`;
                setTimeout(function(){
                  location.reload();
                }, 3000);
              }else if (utype == 'image') {
                document.querySelector('#uploadProgressDiv').innerHTML = `Processing...`;
                setTimeout(function(){
                  location.reload();
                }, 3000);
              }
            }else{
              document.querySelector('#uploadProgressDiv').innerHTML = 'Someting Went Wrong' ;
              document.querySelector('#uploadProgressDiv').style.backgroundColor = 'red';
              setTimeout(function(){
                tempBox.style.display = "none";
              }, 3000);
            }
          } else {
            document.querySelector('#uploadProgressDiv').innerHTML = 'Someting Went Wrong' ;
            document.querySelector('#uploadProgressDiv').style.backgroundColor = 'red';
            setTimeout(function(){
              tempBox.style.display = "none";
            }, 3000);
          }
        });

        xhr.send(formData);
    }
    //*******Image Upload************/


    //*******Video Upload************/
    uploadVideo(fileInput) {
      var self = this;
      var videoPreview = document.getElementById('tempVideo');
      var tempBoxVideo = document.getElementById('tempUploadBoxVideo');
      videoPreview.innerHTML = '';
      tempBoxVideo.style.display = 'flex';
      var reader = new FileReader();
      reader.onload = function (e) {
        var videoElement = document.createElement("video");
        videoElement.style.maxWidth = "100%";
        videoElement.style.maxHeight = "100%";
        videoElement.style.width = "100%";
        videoElement.style.height = "100%";
        var videoURL = URL.createObjectURL(fileInput.files[0]);
        videoElement.src = videoURL;
        videoPreview.appendChild(videoElement);
        var file = fileInput.files[0];
        self.uploadVideoToServer(file, 'video', tempBoxVideo);
      };
      reader.readAsDataURL(fileInput.files[0]);
    }
    uploadVideoToServer(file, utype, tempBoxVideo) {
      var filename = file.name;
      var extension = filename.split('.').pop();
      var formData = new FormData();
      formData.append('type', utype);
      formData.append('ePID', ePID);
      formData.append('ext', extension);
      formData.append('editor', this.whoIs);
      formData.append('media', file);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '../.ht/API/upload.php', true);
      xhr.upload.addEventListener('progress', (event) => {
        if (event.lengthComputable) {
          var percentComplete = (event.loaded / event.total) * 100;
          var progress = document.querySelector('#uploadProgressDivVideo');
          progress.innerHTML = `${percentComplete.toFixed(2)}%`;
          progress.style.width = `${percentComplete.toFixed(2)}%`;
        }
      });

      xhr.addEventListener('load', () => {
        if (xhr.status === 200) {
          // Upload successful, handle the response
          var response = JSON.parse(xhr.responseText);
          if (response.Result) {
            document.querySelector('#uploadProgressDivVideo').innerHTML = `Processing...`;
            setTimeout(function(){
              location.reload();
          }, 3000);
          }else{
            document.querySelector('#uploadProgressDivVideo').innerHTML = 'Someting Went Wrong' ;
            document.querySelector('#uploadProgressDivVideo').style.backgroundColor = 'red';
            setTimeout(function(){
              tempBoxVideo.style.display = 'none';
            }, 3000);
          }
        } else {
          document.querySelector('#uploadProgressDivVideo').innerHTML = 'Someting Went Wrong' ;
          document.querySelector('#uploadProgressDivVideo').style.backgroundColor = 'red';
          setTimeout(function(){
            tempBoxVideo.style.display = 'none';
          }, 3000);
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
var showMenu = new showMenus();


function restrictMedia(ID, status, ostatus, element){
  var spinner =  document.createElement('div');
  spinner.classList.add('spinner');
  spinner.style.marginRight = '0px';
  var e = document.querySelector(`#${element} .checkbox`);
  var s = document.getElementById(`${element}`);
  e.style.display = 'none';
  s.appendChild(spinner);
  var spinner
  if (adminLogged) {
    whoIs = 'Admin';
  }else{
    whoIs = 'User';
    currentUsername = '';
  }
    const changeStatus = async () =>{
      const url = '/.ht/API/deletePic.php';
      var encyDat = {
        'purpose': 'report',
        'imgID': `${ID}`,
        'whois':`${whoIs}`,
        'value':`${status}`,
        'personID' : ' '
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
          spinner.remove();
            e.style.display = 'flex';
          if(e.classList.contains('fa-square')){
            e.classList.remove('fa-square')
            e.classList.add('fa-square-check')
          }else if (e.classList.contains('fa-square-check')) {
            e.classList.add('fa-square')
            e.classList.remove('fa-square-check')
          }
        }else{
          alert(`${data.message}`);
        }
      }else{
        alert( "Can't Update");
      }
  }
  changeStatus();

}


function copyLink(path){
   // The link to be copied
   const linkToCopy = 'https://'+domainName+path;

   // Create a hidden textarea element
   const textarea = document.getElementById("linkToCopy");
   textarea.value = linkToCopy;

   // Select the text inside the textarea
   textarea.select();

   // Copy the selected text to the clipboard
   document.execCommand("copy");

   // Deselect the textarea to avoid displaying the selection
   window.getSelection().removeAllRanges();

  alert('Link copied');
}


function changeImageVisibility(imgID, value, divID, OLDvisible){
  if (adminLogged) {
    var whoIs = 'Admin';
  }else{
    var whoIs = 'User';
  }
  var impactId;
  var foll = document.querySelector('#followersOption i');
  var self = document.querySelector('#selfOption i');
  var everyoneA = document.querySelector('#everyoneOptionA i');
  var everyoneU = document.querySelector('#everyoneOptionU i');
  var spinner = '<div class="spinner" style="margin-right:0px"></div>';
  if (foll.innerHTML == spinner || self.innerHTML == spinner || everyoneU.innerHTML == spinner || everyoneA.innerHTML == spinner) {
    alert('Already Editing');
  }else if (value == 'followers') {
    impactId = foll;
    if (foll.classList.contains('fa-square')) {
      foll.classList.remove('fa-square');
    }
    foll.style.display = 'block';
    foll.innerHTML = spinner;

    if (self.classList.contains('fa-square-check')) {
      self.classList.remove('fa-square-check');
    }
    self.classList.add('fa-square');


    if (everyoneU.classList.contains('fa-square-check')) {
      everyoneU.classList.remove('fa-square-check');
    }
    everyoneU.classList.add('fa-square');

    if (everyoneA.classList.contains('fa-square-check')) {
      everyoneA.classList.remove('fa-square-check');
    }
    everyoneA.classList.add('fa-square');

  }else if(value == 'anon'){
    impactId = everyoneA;
    if (everyoneA.classList.contains('fa-square')) {
      everyoneA.classList.remove('fa-square');
    }
    everyoneA.style.display = 'block';
    everyoneA.innerHTML = '<div class="spinner" style="margin-right:0px"></div>';

    if (self.classList.contains('fa-square-check')) {
      self.classList.remove('fa-square-check');
    }
    self.classList.add('fa-square');


    if (everyoneU.classList.contains('fa-square-check')) {
      everyoneU.classList.remove('fa-square-check');
    }
    everyoneU.classList.add('fa-square');

    if (foll.classList.contains('fa-square-check')) {
      foll.classList.remove('fa-square-check');
    }
    foll.classList.add('fa-square');
  }else if(value == 'users'){

    impactId = everyoneU;
    if (everyoneU.classList.contains('fa-square')) {
      everyoneU.classList.remove('fa-square');
    }
    everyoneU.style.display = 'block';
    everyoneU.innerHTML = '<div class="spinner" style="margin-right:0px"></div>';

    if (self.classList.contains('fa-square-check')) {
      self.classList.remove('fa-square-check');
    }
    self.classList.add('fa-square');


    if (everyoneA.classList.contains('fa-square-check')) {
      everyoneA.classList.remove('fa-square-check');
    }
    everyoneA.classList.add('fa-square');

    if (foll.classList.contains('fa-square-check')) {
      foll.classList.remove('fa-square-check');
    }
    foll.classList.add('fa-square');
  }else{
    impactId = self;
    if (self.classList.contains('fa-square')) {
      self.classList.remove('fa-square');
    }
    self.style.display = 'block';
    self.innerHTML = '<div class="spinner" style="margin-right:0px"></div>';

    if (foll.classList.contains('fa-square-check')) {
      foll.classList.remove('fa-square-check');
    }
    foll.classList.add('fa-square');


    if (everyoneU.classList.contains('fa-square-check')) {
      everyoneU.classList.remove('fa-square-check');
    }
    everyoneU.classList.add('fa-square');

    if (everyoneA.classList.contains('fa-square-check')) {
      everyoneA.classList.remove('fa-square-check');
    }
    everyoneA.classList.add('fa-square');
  }
    var field = document.querySelector(`#${divID} .imgDiv`);
    const changeVisibility = async () =>{
      const url = '/.ht/API/deletePic.php';
      var encyDat = {
        'purpose': 'visibility',
        'imgID': `${imgID}`,
        'personID':`${ePID}`,
        'whois':`${whoIs}`,
        'value':`${value}`
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
          impactId.classList.add('fa-square-check');
          impactId.innerHTML = '';
          impactId.style.display = 'flex';
          field.setAttribute('onclick', field.getAttribute('onclick').replace(OLDvisible,value ));
        }else{
          alert(`${data.message}`);
        }
      }else{
        alert( "Can't Change");
      }
    }
    changeVisibility();

}

function deleteImage(imgID, ext, what, ID){
  if (adminLogged) {
    var whoIs = 'Admin';
  }else{
    var whoIs = 'User';
  }
  var delIcon = document.querySelector('.imgOptions #deleteImageIcon');
  delIcon.classList.remove('fa-trash');
  delIcon.innerHTML = '<div class="spinner"></div>';
  delIcon.style.display = "block";
  delIcon.style.backgroundColor = "transparent";
  delIcon.style.boxShadow = "none";

  const deleteImageAPI = async () =>{
    const url = '/.ht/API/deletePic.php';
    var encyDat = {
      'purpose':'delete',
      'imgID': `${imgID}`,
      'extension': `${ext}`,
      'personID':`${ePID}`,
      'whois':`${whoIs}`,
      'what' : `${what}`
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
        setTimeout(function(){
          delIcon.innerHTML = '';
          if (delIcon.classList.contains('fa-circle-info')) {
              delIcon.classList.remove('fa-circle-info');
          }
          if (delIcon.classList.contains('fa-trash')) {
            delIcon.classList.remove('fa-trash');
          }
          delIcon.classList.add('fa-check');
          delIcon.style.backgroundColor = "lime";
          delIcon.style.boxShadow = "0 0 10px";

          setTimeout(function(){
            var showImageDiv = document.getElementById('imageShowDiv');
            showImageDiv.style.display = "none";
            document.getElementById(`${ID}`).remove();
          }, 300);
        }, 500);

      }else{
        if (delIcon.classList.contains('fa-circle-info')) {
            delIcon.classList.remove('fa-circle-info');
        }
        if (delIcon.classList.contains('fa-trash')) {
          delIcon.classList.remove('fa-trash');
        }
        delIcon.classList.add('fa-circle-info');
        delIcon.innerHTML = "";
        delIcon.style.display = 'flex';
        delIcon.style.backgroundColor = 'red';
      }
    }else{
      delOpt.innerHTML = "Can't Delete";
    }
  }
  deleteImageAPI();
}


function requestCreation(){
  var field =  document.getElementById('reqCreation');
  const requestCreate = async () =>{
    const url = '/.ht/API/reqCreation.php';
    var encyDat = {
      'purpose' : 'request',
      'personID' : `${ePID}`,
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
        field.innerHTML = 'Requested';
        location.reload();
      }else{
        field.innerHTML = `${data.message}`;
      }
    }else{
      field.innerHTML = 'Something went wrong';
    }
  }

  requestCreate();
}


function responseCreation(val){
  var button = document.querySelector(`.reqCreationButton.${val}button`);
  const responseCreate = async () =>{
    const url = '/.ht/API/reqCreation.php';
    var encyDat = {
      'purpose' : 'response',
      'personID' : `${ePID}`,
      'value' : `${val}`
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
        if (val == 'ACC') {
          button.innerHTML = 'Accepted';
        }else{
          button.innerHTML = 'Rejected';
        }
        location.reload();
      }else{
        button.innerHTML = `${data.message}`;
      }
    }else{
      button.innerHTML = 'Something went wrong';
    }
  }

  responseCreate();
}


function InitializeWebstory(x){
  var reqCreation = document.querySelector('#reqCreation');
  reqCreation.querySelector('i').style.display = 'none';
  reqCreation.innerHTML = 'Initializing...<div  class="spinner" style="border: 4px solid white; border-left: 4px solid rgb(32,33,35); display:inline-block; margin:0; margin-left: 15px; "></div>';

  // Checking if he can create stories or not
  const canCreateStories = async() => {
    const url = '/.ht/API/reqCreation.php';
    var encyDat = {
      'purpose' : 'check',
      'personID' : `${ePID}`,
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
        reqCreation.innerHTML='Creating...<div  class="spinner" style="border: 4px solid white; border-left: 4px solid rgb(32,33,35); display:inline-block; margin:0; margin-left: 15px; "></div>';
        if (x == 'admin') {
          window.location.href = `/create/?type=webstory&editor=Admin&username=${currentUsername}`;
        }else{
          window.location.href = "/create/?type=webstory";
        }
      }else{
        reqCreation.innerHTML= `${data.message}`;
      }
    }else{
      reqCreation.innerHTML= `${data.message}`;
    }
  }
  canCreateStories();
}

function editStory(x, u){
  if (u == '') {
    window.location.href = `/create/?type=webstory&ID=${x}`;
  }else{
    window.location.href = `/create/?type=webstory&editor=Admin&ID=${x}&username=${u}`;
  }

}


function deleteStory(storyID, divID){
  var storyDIV = document.querySelector(`#webstory${divID}`);
  if (adminLogged) {
    whoIs = 'admin';
  }else{
    whoIs = 'user';
    currentUsername = '';
  }

  const deleteS = async ()=>{
    const url = '/.ht/API/webstories.php';
    var encyDat = {
      'whois':`${whoIs}`,
      'purpose' : 'delete',
      'username' : `${currentUsername}`,
      'storyID':`${storyID}`
    };
    const response = await fetch(url, {
        method: 'post',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(encyDat)
      });
    var data = await response.json();

    if (!data) {
      alert('Something went wrong');
    }else if(!data.Result){
      alert(`${data.message}`);
    }else{
      storyDIV.querySelector('.background .title').innerHTML = 'Deleting...';

      setTimeout(function(){
        setTimeout(function(){
          storyDIV.style.display = 'none';
        }, 300);
        storyDIV.style.marginLeft = '-210px';

      }, 1000);

    }
  }
  deleteS();
}
