









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
    }

    uploadImage() {
      var removeImageButton = document.querySelector('#removeImage');
      removeImageButton.style.display = 'block';
      removeImageButton.addEventListener('click', () => {
        this.removeImage();
      });

      document.querySelector('#uploadDbButton').style.display = 'block';
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
    }

    removeImage() {
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

