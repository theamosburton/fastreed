

class showMenus{
    constructor(){
        document.querySelector('#progressBar').style.display = 'none';
        var params = new URLSearchParams(window.location.search);
        this.optValue = params.get('opt');
        this.dashboardMenu = document.querySelector('#dashboardMenu');
        this.contentMenu = document.querySelector('#contentMenu');
        this.mediaMenu = document.querySelector('#mediaMenu');
        this.privacyMenu = document.querySelector('#privacyMenu');

    
        this.dashboardDiv = document.querySelector('#dashboardDiv');
        this.contentDiv = document.querySelector('#contentDiv');
        this.mediaDiv = document.querySelector('#mediaDiv');
        this.privacyDiv = document.querySelector('#privacyDiv');

        this.dashboardDiv.style.display = 'none';
        this.contentDiv.style.display = 'none';
        this.mediaDiv.style.display = 'none';
        this.privacyDiv.style.display = 'none';

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


    uploadFile(file) {

        document.querySelector('#progressBar').style.display = 'block';
        var xhr = new XMLHttpRequest();
      
        // Progress event handler
        xhr.upload.addEventListener('progress', function(event) {
          if (event.lengthComputable) {
            var percentComplete = (event.loaded / event.total) * 100;
            var percentComplete = percentComplete.toFixed(1);
            document.querySelector('#uploadLabel').innerHTML = `Upload ${percentComplete}%`;
            document.querySelector('#progressTotal').style.width = `${percentComplete}%`;
          }
        });
      
        // Load event handler
        xhr.addEventListener('load', function() {
            location.reload();
        });
      
        // Error event handler
        xhr.addEventListener('error', function() {
          console.log('Upload failed!');
        });
      
        // Set the upload URL and method
        var url = '/fastreedusercontent/upload.php';
        var method = 'POST';
      
        // Open the request
        xhr.open(method, url, true);
      
        // Create a FormData object and append the file
        var formData = new FormData();
        formData.append('file', file);
      
        // Send the request with the FormData
        xhr.send(formData);
      }
      
       
      
}

new showMenus();
document.getElementById('newUpload').addEventListener('change', function(event) {
    var inputFile = event.target.files[0];
    new showMenus().uploadFile(inputFile);
  });