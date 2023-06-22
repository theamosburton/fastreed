class uploadsData{
  constructor(){
    window.onload = function() {
      this.uploadsData = {};
      this.uploadsCount = Object.keys(uploads).length;
      for (let u = 0; u < this.uploadsCount; u++) {
        var ulink = uploads['up' + u].link;
        var type = uploads['up' + u].type;
        if (type == 'photos') {
          const fetchPhtos = async()  =>{
            fetch(ulink)
            .then(response => response.blob())
            .then(blob => {
              var imgURL = URL.createObjectURL(blob);
              this.uploadsData['upload' + u] = {};
              this.uploadsData['upload' + u].orglink = ulink;
              var uploadedDiv = document.getElementById('uploads');
              uploadedDiv.innerHTML += ` 
              <div draggable="true" class="uploadContent" id="media${u}" onclick="selectMedia('${imgURL}', 'image')">
                  <img src="${imgURL}">
                  <div class="fileInfo">
                      <i class="fa fa-image fa-sm whatIcon"></i>
                  </div>
              </div>
              `;
            });
          }
          fetchPhtos();
        }else if(type == 'videos'){
          const fetchVideos = async() =>{
            fetch(ulink)
            .then(response => response.blob())
            .then(blob => {
              var videoURL = URL.createObjectURL(blob);
              this.uploadsData['upload' + u] = {};
              this.uploadsData['upload' + u].orglink = ulink;
              var uploadedDiv = document.getElementById('uploads');
              uploadedDiv.innerHTML += `
                  <div draggable="true" class="uploadContent" id="media${u}" onclick="selectMedia('${videoURL}', 'video')">
                      <video><source src="${videoURL}" type="video/mp4"></video>
                      <div class="fileInfo">
                          <i class="fa fa-video fa-sm whatIcon"></i>
                      </div>
                  </div>
              `;
            });
          }
          fetchVideos();
        }
      }
    };
  }
}

var uploadsDataClass = new uploadsData();


  function hideSection(id){
    var sectionID = document.getElementById(`${id}`);
    sectionID.style.display = 'none';
    var hsLeft = document.getElementById('hsLeft');
    var hsRight = document.getElementById('hsRight');
    hsLeft.style.display = 'flex';
    hsRight.style.display = 'flex';
  }

  function showSection(section, id2){
    var Section = document.getElementById(`${section}`);
    var hsLeft = document.getElementById('hsLeft');
    var hsRight = document.getElementById('hsRight');
    var lefthideMe = document.getElementById(`${id2}`);
    lefthideMe.style.display = 'flex';
    hsLeft.style.display = 'none';
    hsRight.style.display = 'none';
    Section.style.display = 'flex';
  }

  function selectMedia(link, type){
    var editorId = document.getElementById(`editTab`);
    var hsLeft = document.getElementById('hsLeft');
    var hsRight = document.getElementById('hsRight');
    var leftSection = document.getElementById('leftSection');
    while (editorId.firstChild) {
      editorId.removeChild(editorId.firstChild);
    }
    if (type == 'image') {
      console.log('h');
      var imageElement = document.createElement('img');
      imageElement.src = link;
      editorId.appendChild(imageElement);
      var screenWidth = window.innerWidth;
      if (screenWidth < 600) {
        if (leftSection.style.display = 'flex') {
          leftSection.style.display = 'none';
          hsLeft.style.display = 'flex';
          hsRight.style.display = 'flex';
        }
      }
    }else if(type == 'video'){
      var videoElement = document.createElement('video');
      videoElement.src = link;
      videoElement.type = 'video/mp4';
      editorId.appendChild(videoElement);
      var screenWidth = window.innerWidth;
      if (screenWidth < 600) {
        if (leftSection.style.display = 'flex') {
          leftSection.style.display = 'none';
          hsLeft.style.display = 'flex';
          hsRight.style.display = 'flex';
        }
      }
    }
  }