// Developer: Mohd Shafiq Malik
// Last Edit: 28-07-2023
// Non Copyrighted
class uploadsData{
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
            <div draggable="true" class="uploadContent" id="media${u}" onclick="selectMedia('${imgURL}', 'image', '${ulink}')">
                <img src="${imgURL}">
                <div class="fileInfo">
                    <i class="fa fa-image fa-sm whatIcon"></i>
                </div>
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
                <div draggable="true" class="uploadContent" id="media${u}" onclick="selectMedia('${videoURL}', 'video', '${ulink}')">
                    <video>
                        <source src="${videoURL}" type="video/mp4">
                    </video>
                    <div class="fileInfo">
                        <i class="fa fa-video fa-sm whatIcon"></i>
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

  function selectMedia(link, type,olink){
    var hsLeft = document.getElementById('hsLeft');
    var hsRight = document.getElementById('hsRight');
    var leftSection = document.getElementById('leftSection');
    if (type == 'image') {
      layers.modifyMedia('image', link, olink);
      var layerId =  layers.presentLayerIndex;
      var layer = document.getElementById(`layer${layerId}`);
      var imageElement = document.createElement('img');
      imageElement.src = link;
      layer.appendChild(imageElement);

      var screenWidth = window.innerWidth;
      if (screenWidth < 800) {
        if (leftSection.style.display = 'flex') {
          leftSection.style.display = 'none';
          hsLeft.style.display = 'flex';
        }
        if (screenWidth < 600) {
          hsRight.style.display = 'flex';
        }
      }
    }else if(type == 'video'){
      layers.modifyMedia('video', link, olink);
      var layerId =  layers.presentLayerIndex;
      var layer = document.getElementById(`layer${layerId}`);

      var videoElement = document.createElement('video');
      videoElement.src = link;
      videoElement.type = 'video/mp4';
      var contorlsElements = document.createElement('div');
      contorlsElements.id = `videoControls${layers.presentLayer}`;
      contorlsElements.className = 'videoControls';
      contorlsElements.innerHTML = `
      <i class="fa-regular fa-volume-high" id="muteUnmute" data-status="unmuted" onclick="layers.muteUnmute()"></i>
      <i class="fa fa-play" id="playPauseMedia" data-status="paused" onclick="layers.playPauseMedia()"></i>
      `;
      layer.appendChild(contorlsElements);
      layer.appendChild(videoElement);
      layers.playPauseMedia();
      layers. muteUnmute();
      var screenWidth = window.innerWidth;
      if (screenWidth < 800) {
        if (leftSection.style.display = 'flex') {
          leftSection.style.display = 'none';
          hsLeft.style.display = 'flex';
        }
        if (screenWidth < 600) {
          hsRight.style.display = 'flex';
        }
      }
    }
  }