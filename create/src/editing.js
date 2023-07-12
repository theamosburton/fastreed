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
    selectMedia(link, type, olink){
        var hsLeft = document.getElementById('hsLeft');
        var hsRight = document.getElementById('hsRight');
        var leftSection = document.getElementById('leftSection');
        if (document.getElementById(`placeholder${editor.presentLayerIndex}`)) {
          document.getElementById(`placeholder${editor.presentLayerIndex}`).remove();
        }else if(document.getElementById(`mediaContent${editor.presentLayerIndex}`)){
          document.getElementById(`mediaContent${editor.presentLayerIndex}`).remove();
        }
        
        if (type == 'image') {
          edits.modifyMedia('image', link, olink);
          var layerId =  editor.presentLayerIndex;
          var layer = document.getElementById(`layer${layerId}`);
          var imageElement = document.createElement('img');
          imageElement.id = `mediaContent${editor.presentLayerIndex}`;
          imageElement.src = link;
          layer.appendChild(imageElement);
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
    
          layer.appendChild(contorlsElements);
          layer.appendChild(videoElement);
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
        document.getElementById(`mediaStyles${this.editor.presentLayerIndex}`).style.display = 'flex';
        this.editor.layers[this.editor.presentLayerIndex].media.type = type;
        this.editor.layers[this.editor.presentLayerIndex].media.blobUrl = blobUrl;
        this.editor.layers[this.editor.presentLayerIndex].media.url = url;

    }

    overlayOpacity(){
      if (this.editor.layers[this.editor.presentLayerIndex].media.blobUrl == undefined) {
        alert('Add photo or video');
      }else{
        var mediaOverlayOpacity = document.getElementById(`mediaOverlayOpacity${this.editor.presentLayerIndex}`);
        this.editor.layers[this.editor.presentLayerIndex].media.styles.overlayOpacity = `${mediaOverlayOpacity.value}`;
        this.editor.mediaOverlayDiv.style.opacity =`${mediaOverlayOpacity.value}%`;
        console.log(this.editor.mediaOverlayDiv.style.opacity);
      }
        
    }

    mediaOverlayColor(){
      if (this.editor.layers[this.editor.presentLayerIndex].media.blobUrl == undefined) {
        alert('Add photo or video');
      }else{
        var overlayColor = document.getElementById(`mediaOverlayColor${this.editor.presentLayerIndex}`);
        console.log(this.editor.layers[this.editor.presentLayerIndex]);
        this.editor.layers[this.editor.presentLayerIndex].media.styles.overlayColor = overlayColor.value;
        
       this.editor.mediaOverlayDiv.style.backgroundColor =`${overlayColor.value}`;
      }
    }

    mediaFit(){
      if (this.editor.layers[this.editor.presentLayerIndex].media.blobUrl == undefined) {
        alert('Add photo or video');
      }else{
        var mediaFit = document.getElementById(`mediaFit${this.editor.presentLayerIndex}`);
        var mediaContent = document.getElementById(`mediaContent${this.editor.presentLayerIndex}`);
        this.editor.layers[this.editor.presentLayerIndex].media.styles.mediaFit = mediaFit.value;
        mediaContent.style.objectFit = `${mediaFit.value}`;
      }
    }
    // Media Editing



    // Title Editing
    changeFontSize(x){
      if(document.getElementById(`titleText${this.editor.presentLayerIndex}`).value == ''){
        alert('Add Title!');
      }else{
        if (x == 'self') {
          var fontSize = document.getElementById(`titleFontSize${this.editor.presentLayerIndex}`);
          var applyto = document.querySelector(`#title${this.editor.presentLayerIndex} .titleText`);
          applyto.style.fontSize = `${fontSize.value}`;
          this.editor.layers[this.editor.presentLayerIndex].title.fontSize = `${fontSize.value}`;
        }else{
          var fontSize = document.getElementById(`customFontSize${this.editor.presentLayerIndex}`);
          var applyto = document.querySelector(`#title${this.editor.presentLayerIndex} .titleText`);
          applyto.style.fontSize = `${fontSize.value}`;
          this.editor.layers[this.editor.presentLayerIndex].title.fontSize = `${fontSize.value}`;
        }
      }
    }
    changeFontFamily(x){
      if(document.getElementById(`titleText${this.editor.presentLayerIndex}`).value == ''){
        alert('Add Title!');
      }else{
        var applyto = document.querySelector(`#title${this.editor.presentLayerIndex} .titleText`);
        if (x == 'self') {
          var fontFamily = document.getElementById(`customFontFamily${this.editor.presentLayerIndex}`);
          applyto.style.fontFamily = `${fontFamily.value}`;
          this.editor.layers[this.editor.presentLayerIndex].title.fontFamily = `${fontFamily.value}`;
        }else{
          var fontFamily = document.getElementById(`fontFamily${this.editor.presentLayerIndex}`);
          applyto.style.fontFamily = `${fontFamily.value}`;
          this.editor.layers[this.editor.presentLayerIndex].title.fontFamily = `${fontFamily.value}`;
      }
    }
  }
    changeText(){
      var text = document.getElementById(`titleText${this.editor.presentLayerIndex}`);
      var applyto = document.querySelector(`#title${this.editor.presentLayerIndex} .titleText`);
      applyto.innerHTML = `${text.value}`;
      this.editor.layers[this.editor.presentLayerIndex].title.text = `${text.value}`;
    }
    changeFontWeight(){
      if(document.getElementById(`titleText${this.editor.presentLayerIndex}`).value == ''){
        alert('Add Title!');
      }else{
        var fontWeight = document.getElementById(`titleFontWeight${this.editor.presentLayerIndex}`);
        var applyto = document.querySelector(`#title${this.editor.presentLayerIndex} .titleText`);
        applyto.style.fontWeight = `${fontWeight.value}`;
        this.editor.layers[this.editor.presentLayerIndex].title.fontWeight = `${fontWeight.value}`;
      }
      
    }
    // Title editing


    // Text Editing
    changeOtherFontSize(x){
      if (document.getElementById(`otherText${this.editor.presentLayerIndex}`).value == '') {
        alert('Add text!');
      }else{
        if (x == self) {
          var fontSize = document.getElementById(`otherFontSize${this.editor.presentLayerIndex}`);
          var applyto = document.querySelector(`#text${this.editor.presentLayerIndex} .titleText`);
          applyto.style.fontSize = `${fontSize.value}`;
          this.editor.layers[this.editor.presentLayerIndex].otherText.fontSize = `${fontSize.value}`;
        }else{
          var fontSize = document.getElementById(`customOtherFontSize${this.editor.presentLayerIndex}`);
          var applyto = document.querySelector(`#text${this.editor.presentLayerIndex} .titleText`);
          applyto.style.fontSize = `${fontSize.value}`;
          this.editor.layers[this.editor.presentLayerIndex].otherText.fontSize = `${fontSize.value}`;
        }
        
      }
    }

    changeOtherText(){
      var text = document.getElementById(`otherText${this.editor.presentLayerIndex}`);
      var applyto = document.querySelector(`#text${this.editor.presentLayerIndex} .titleText`);
      applyto.innerHTML = `${text.value}`;
      this.editor.layers[this.editor.presentLayerIndex].otherText.text = `${text.value}`;
    }
    changeOtherFontWeight(){
      if (document.getElementById(`otherText${this.editor.presentLayerIndex}`).value == '') {
        alert('Add text!');
      }else{
        var fontWeight = document.getElementById(`otherFontWeight${this.editor.presentLayerIndex}`);
        var applyto = document.querySelector(`#text${this.editor.presentLayerIndex} .titleText`);
        applyto.style.fontWeight = `${fontWeight.value}`;
        this.editor.layers[this.editor.presentLayerIndex].otherText.fontWeight = `${fontWeight.value}`;
      }
    }
    changeOtherFontFamily(x){
      if (document.getElementById(`otherText${this.editor.presentLayerIndex}`).value == '') {
        alert('Add text!');
      }else{
        var applyto = document.querySelector(`#text${this.editor.presentLayerIndex} .titleText`);
        if (x == 'self') {
          var fontFamily = document.getElementById(`otherCustomFontFamily${this.editor.presentLayerIndex}`);
          applyto.style.fontFamily = `${fontFamily.value}`;
          this.editor.layers[this.editor.presentLayerIndex].otherText.fontFamily = `${fontFamily.value}`;
        }else{
          var fontFamily = document.getElementById(`otherFontFamily${this.editor.presentLayerIndex}`);
          applyto.style.fontFamily = `${fontFamily.value}`;
          this.editor.layers[this.editor.presentLayerIndex].otherText.fontFamily = `${fontFamily.value}`;
        }
      }
    }
    // Text Editing



}

let edits = new Edits();
