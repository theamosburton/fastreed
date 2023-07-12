class Edits{
    constructor(){
        this.editor = editor;
    }


    //Expanding Options
    expandOptions(id, func){
        var option = document.querySelector(`#${id} .options`);
        var icon = document.querySelector(`#${id} .objectName i`);
        if (func == 'block' || func == 'none') {
            if (func == 'none') {
                option.style.display = 'none';
                icon.classList.add('fa-caret-right');
                icon.classList.remove('fa-caret-down');
            }else{
                icon.classList.add('fa-caret-down');
                icon.classList.remove('fa-caret-right');
                option.style.display = 'block';
            }
        }else{
            if (option.style.display == 'block') {
                option.style.display = 'none';
                icon.classList.add('fa-caret-right');
                icon.classList.remove('fa-caret-down');
            }else{
                icon.classList.add('fa-caret-down');
                icon.classList.remove('fa-caret-right');
                option.style.display = 'block';
            }
        }
        
    }

    // Media Editing
    selectMedia(link, type,olink){
        var hsLeft = document.getElementById('hsLeft');
        var hsRight = document.getElementById('hsRight');
        var leftSection = document.getElementById('leftSection');
        if (type == 'image') {
          edits.modifyMedia('image', link, olink);
          var layerId =  editor.presentLayerIndex;
          var layer = document.getElementById(`layer${layerId}`);
          var imageElement = document.createElement('img');
          imageElement.id = `mediaContent${editor.presentLayerIndex}`;
          imageElement.src = link;
          var overlay = document.createElement('div');
          overlay.classList.add('overlay');
          overlay.id = `overlay${layerId}`;
    
          var layersTop = document.createElement('div');
          layersTop.classList.add('layersTop');
          layersTop.innerHTML = `
          <div class="title" id="title${editor.presentLayerIndex}">
            <span class="titleText" >Enter Title/heading</span>
          </div>
          <div class="text" id="text${editor.presentLayerIndex}">
            <span class="titleText" >Enter more text..</span>
          </div>
          <div class="caption" id="caption${editor.presentLayerIndex}">
            <span>Caption(optional)</span>
          </div>
          `;
    
          layer.appendChild(imageElement);
          layer.appendChild(overlay);
          layer.appendChild(layersTop);
          editor.mediaOverlayDiv = document.getElementById(`overlay${editor.presentLayerIndex}`);
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
          edits.modifyMedia('video', link, olink);
          var layerId =  editor.presentLayerIndex;
          var layer = document.getElementById(`layer${layerId}`);
    
          var videoElement = document.createElement('video');
          videoElement.src = link;
          videoElement.type = 'video/mp4';
          videoElement.id = `mediaContent${editor.presentLayerIndex}`;
          var contorlsElements = document.createElement('div');
          contorlsElements.id = `videoControls${editor.presentLayer}`;
          contorlsElements.className = 'videoControls';
          contorlsElements.innerHTML = `
          <i class="fa-regular fa-volume-high" id="muteUnmute" data-status="unmuted" onclick="editor.muteUnmute()"></i>
          <i class="fa fa-play" id="playPauseMedia" data-status="paused" onclick="editor.playPauseMedia()"></i>
          `;
    
          var overlay = document.createElement('div');
          overlay.classList.add('overlay');
          overlay.id = `overlay${layerId}`;
          
          var layersTop = document.createElement('div');
          layersTop.classList.add('layersTop');
          layersTop.innerHTML = `
          <div class="title" id="title${editor.presentLayerIndex}">
            <span class="titleText" >Enter Title/heading</span>
          </div>
          <div class="text" id="text${editor.presentLayerIndex}">
            <span class="titleText" >Enter more text..</span>
          </div>
          <div class="caption" id="caption${editor.presentLayerIndex}">
            <span>Caption(optional)</span>
          </div>
          `;
    
          layer.appendChild(contorlsElements);
          layer.appendChild(videoElement);
          layer.appendChild(overlay);
          layer.appendChild(layersTop);
          editor.mediaOverlayDiv = document.getElementById(`overlay${editor.presentLayerIndex}`);
          editor.playPauseMedia();
          editor. muteUnmute();
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
    deleteMedia(type){
        if(type == 'image'){
            var media = document.querySelector(`#${this.editor.presentLayerDiv.id} img`);
        }else if(type == 'video'){
            var media = document.querySelector(`#${this.editor.presentLayerDiv.id} video`);
        }
        this.editor.presentLayerDiv.innerHTML =`
            <div class="placeholder">
                <p> Add</p>
                <p> Photo/Video</p>
                <small> Recomended ratios are </small>
            <small> 9:16, 3:4 and 2:3 </small>
            </div>`;
        media.remove();
        this.editor.layers[this.editor.presentLayerIndex].media = {};
    }
    modifyMedia(type, blobUrl, url){
        var deleteMediaButton = document.getElementById('deleteMedia');
        if (type == 'image') {
            deleteMediaButton.setAttribute("onclick", "edits.deleteMedia('image')");
        }else if(type == 'video'){
            deleteMediaButton.setAttribute("onclick", "edits.deleteMedia('video')");
        }else{
            deleteMediaButton.removeAttribute("onclick");
        }
        document.getElementById('mediaStyles').style.display = 'flex';
        this.editor.presentLayerDiv.innerHTML = '';
        this.editor.layers[this.editor.presentLayerIndex].media.type = type;
        this.editor.layers[this.editor.presentLayerIndex].media.blobUrl = blobUrl;
        this.editor.layers[this.editor.presentLayerIndex].media.url = url;
    }

    overlayOpacity(){
        var mediaOverlayOpacity = document.getElementById('mediaOverlayOpacity');
        this.editor.layers[this.editor.presentLayerIndex].media.style = {};
        this.editor.layers[this.editor.presentLayerIndex].media.style.overlayOpacity = `${mediaOverlayOpacity.value}`;
        this.editor.mediaOverlayDiv.style.opacity =`${mediaOverlayOpacity.value}%`;
    }
    mediaOverlayColor(){
        var overlayColor = document.getElementById('mediaOverlayColor');
        console.log(this.editor.layers[this.editor.presentLayerIndex]);
        this.editor.layers[this.editor.presentLayerIndex].media.style = {};
        this.editor.layers[this.editor.presentLayerIndex].media.style.overlayColor = overlayColor.value;
        
       this.editor.mediaOverlayDiv.style.backgroundColor =`${overlayColor.value}`;
    }
    mediaFit(){
        var mediaFit = document.getElementById('mediaFit');
        var mediaContent = document.getElementById(`mediaContent${this.editor.presentLayerIndex}`);
        this.editor.layers[this.editor.presentLayerIndex].media.style = {};
        this.editor.layers[this.editor.presentLayerIndex].media.style.mediaFit = mediaFit.value;
        mediaContent.style.objectFit = `${mediaFit.value}`;
    }
    // Media Editing

}

let edits = new Edits();
