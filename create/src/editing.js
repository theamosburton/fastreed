class Edits{
  constructor(){
      this.editor = editor;
      this.version;
  }

  initializeVars(){
    this.overlayArea = this.editor.layers['L' + this.editor.presentLayerIndex].media.styles.overlayArea;
    this.overlayOpacity = this.editor.layers['L' + this.editor.presentLayerIndex].media.styles.overlayOpacity;
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


      if (type == 'image') {
        if (document.getElementById(`placeholder${editor.presentLayerIndex}`)) {
          document.getElementById(`placeholder${editor.presentLayerIndex}`).remove();
        }else if(document.getElementById(`mediaContent${editor.presentLayerIndex}`)){
          document.getElementById(`mediaContent${editor.presentLayerIndex}`).remove();
        }
        var layerId =  editor.presentLayerIndex;
        if (document.getElementById(`videoControls${layerId+1}`)) {
          document.getElementById(`videoControls${layerId+1}`).remove();
        }
        edits.modifyMedia('image', link, olink);

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
        if (editor.presentLayerIndex == 0) {
          alert('Please use photo for thumbnail');
        }else{
          if (document.getElementById(`placeholder${editor.presentLayerIndex}`)) {
            document.getElementById(`placeholder${editor.presentLayerIndex}`).remove();
          }else if(document.getElementById(`mediaContent${editor.presentLayerIndex}`)){
            document.getElementById(`mediaContent${editor.presentLayerIndex}`).remove();
          }
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
      if (this.version+1 == editor.version) {
        this.version += 1;
      }
      this.saveToBrowser();
    }
  deleteMedia(type){
      if(type == 'image'){
          var media = document.querySelector(`#${this.editor.presentLayerDiv.id} img`);
      }else if(type == 'video'){
          var media = document.querySelector(`#${this.editor.presentLayerDiv.id} video`);
      }

      var overlay = document.createElement('div');
      overlay.classList.add('overlay');
      overlay.id = `overlay${this.presentLayerIndex}`;

      var layersTop = document.createElement('div');
      layersTop.classList.add('layersTop');
      layersTop.innerHTML = `
      <div class="title" id="title${this.presentLayerIndex}">
      <span class="titleText" id="titleText${this.presentLayerIndex}" contenteditable="true" onkeypress="edits.editTitle('titleText${this.presentLayerIndex}')">Enter Title/heading</span>
      </div>
      <div class="text" id="text${this.presentLayerIndex}">
      <span class="titleText" contenteditable="true" id="otherText${this.presentLayerIndex}" contenteditable="true" onkeypress="edits.editText('otherText${this.presentLayerIndex}')">Enter more text..</span>
      </div>`;

      this.editor.presentLayerDiv.innerHTML = `<div class="placeholder" id="placeholder${this.editor.presentLayerIndex}">
          <p> Add</p>
          <p> Photo/Video</p>
          <small> Recomended ratios are </small>
          <small> 9:16, 3:4 and 2:3 </small>
      </div>`;

      this.editor.presentLayerDiv.appendChild(overlay);
      this.editor.presentLayerDiv.appendChild(layersTop);
      media.remove();
      this.editor.layers['L' + this.editor.presentLayerIndex].media = {};
      if (this.version+1 == editor.version) {
        this.version += 1;
      }
      this.saveToBrowser();
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
      this.editor.layers['L' + this.editor.presentLayerIndex].media.type = type;
      this.editor.layers['L' + this.editor.presentLayerIndex].media.blobUrl = blobUrl;
      this.editor.layers['L' + this.editor.presentLayerIndex].media.url = url;
      if (this.version+1 == editor.version) {
        this.version += 1;
      }
      this.saveToBrowser();
  }
  updateMedia(type){
      var deleteMediaButton = document.getElementById('deleteMedia');
      if (type == 'image') {
          deleteMediaButton.setAttribute("onclick", "edits.deleteMedia('image')");
      }else if(type == 'video'){
          deleteMediaButton.setAttribute("onclick", "edits.deleteMedia('video')");
      }else{
          deleteMediaButton.removeAttribute("onclick");
      }
  }
  overlayEdit(x){
    this.initializeVars();
    if (x == 'overlayArea') {
      this.overlayArea = document.getElementById(`overlayArea${this.editor.presentLayerIndex}`).value;
    }else if(x == 'overlayOpacity'){
      this.overlayOpacity = document.getElementById(`mediaOverlayOpacity${this.editor.presentLayerIndex}`).value;
    }


    var overlayArea = this.overlayArea;
    var overlayOpacity = this.overlayOpacity;
    this.editor.layers['L' + this.editor.presentLayerIndex].media.styles.overlayOpacity = overlayOpacity;
    this.editor.layers['L' + this.editor.presentLayerIndex].media.styles.overlayArea = overlayArea;
    if (this.editor.layers['L' + this.editor.presentLayerIndex].media.blobUrl == undefined || this.editor.layers['L' + this.editor.presentLayerIndex].media.blobUrl == '') {
      alert('Add photo or video');
    }else if (overlayArea == '100') {
      document.querySelector(`#layer${this.editor.presentLayerIndex} .layersTop`).style.backgroundImage = `linear-gradient(182deg, rgba(0, 0, 0, ${overlayOpacity}%), rgba(0, 0, 0, ${overlayOpacity}%), rgba(0, 0, 0, ${overlayOpacity}%),rgba(0, 0, 0, ${overlayOpacity}%),rgba(0, 0, 0, ${overlayOpacity}%))`;

    }else if(overlayArea >= '80'){
      document.querySelector(`#layer${this.editor.presentLayerIndex} .layersTop`).style.backgroundImage = `linear-gradient(180deg, rgba(0, 0, 0, ${overlayOpacity}%), rgba(0, 0, 0, ${overlayOpacity}%), rgba(0, 0, 0, ${overlayOpacity}%),rgba(0, 0, 0, ${overlayOpacity}%),rgba(0, 0, 0, ${overlayOpacity}%))`;

    }else if(overlayArea >= '60'){
      document.querySelector(`#layer${this.editor.presentLayerIndex} .layersTop`).style.backgroundImage = `linear-gradient(180deg, rgba(255, 255, 255, 0), rgba(0, 0, 0, ${overlayOpacity}%), rgba(0, 0, 0, ${overlayOpacity}%),rgba(0, 0, 0, ${overlayOpacity}%),rgba(0, 0, 0, ${overlayOpacity}%))`;
    }else if(overlayArea >= '40'){
        document.querySelector(`#layer${this.editor.presentLayerIndex} .layersTop`).style.backgroundImage = `linear-gradient(180deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0), rgba(0, 0, 0, ${overlayOpacity}%),rgba(0, 0, 0, ${overlayOpacity}%), rgba(0, 0, 0, ${overlayOpacity}%))`;
    }else if(overlayArea >= '20'){
      document.querySelector(`#layer${this.editor.presentLayerIndex} .layersTop`).style.backgroundImage = `linear-gradient(180deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0), rgba(255, 255, 255, 0),rgba(0, 0, 0, ${overlayOpacity}%),rgba(0, 0, 0, ${overlayOpacity}%))`;
    }
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();

  }
  mediaFit(){
    if (this.editor.layers['L' + this.editor.presentLayerIndex].media.blobUrl == undefined) {
      alert('Add photo or video');
    }else{
      var mediaFit = document.getElementById(`mediaFit${this.editor.presentLayerIndex}`);
      var mediaContent = document.getElementById(`mediaContent${this.editor.presentLayerIndex}`);
      this.editor.layers['L' + this.editor.presentLayerIndex].media.styles.mediaFit = mediaFit.value;
      mediaContent.style.objectFit = `${mediaFit.value}`;
    }
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
    // Media Editing

  // Title Editing
  changeFontSize(x){
    if(document.getElementById(`titleText${this.editor.presentLayerIndex}`).value == ''){
      alert('Add Title!');
    }else{
      if (x == 'select') {
        var fontSize = document.getElementById(`titleFontSize${this.editor.presentLayerIndex}`);
        var applyto = document.querySelector(`#title${this.editor.presentLayerIndex} .titleText`);
        applyto.style.fontSize = `${fontSize.value}`;
        this.editor.layers['L' + this.editor.presentLayerIndex].title.fontSize = `${fontSize.value}`;
      }else{
        var fontSize = document.getElementById(`customFontSize${this.editor.presentLayerIndex}`);
        var applyto = document.querySelector(`#title${this.editor.presentLayerIndex} .titleText`);
        applyto.style.fontSize = `${fontSize.value}`;
        this.editor.layers['L' + this.editor.presentLayerIndex].title.fontSize = `${fontSize.value}`;
      }
    }
    if (this.version+1 == editor.version) {
  this.version += 1;
}
  }
  changeFontFamily(x){
    if(document.getElementById(`titleText${this.editor.presentLayerIndex}`).value == ''){
      alert('Add Title!');
    }else{
      var applyto = document.querySelector(`#title${this.editor.presentLayerIndex} .titleText`);
      if (x == 'custom') {
        var fontFamily = document.getElementById(`customFontFamily${this.editor.presentLayerIndex}`);
        applyto.style.fontFamily = `${fontFamily.value}`;
        this.editor.layers['L' + this.editor.presentLayerIndex].title.fontFamily = `${fontFamily.value}`;
      }else{
        var fontFamily = document.getElementById(`fontFamily${this.editor.presentLayerIndex}`);
        applyto.style.fontFamily = `${fontFamily.value}`;
        this.editor.layers['L' + this.editor.presentLayerIndex].title.fontFamily = `${fontFamily.value}`;
      }
    }
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
  editTitle(x){
    var text = document.getElementById(`${x}`);
    this.editor.layers['L' + this.editor.presentLayerIndex].title.text = `${text.innerHTML}`;
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
  changeFontWeight(){
    if(document.getElementById(`titleText${this.editor.presentLayerIndex}`).value == ''){
      alert('Add Title!');
    }else{
      var fontWeight = document.getElementById(`titleFontWeight${this.editor.presentLayerIndex}`);
      var applyto = document.querySelector(`#title${this.editor.presentLayerIndex} .titleText`);
      applyto.style.fontWeight = `${fontWeight.value}`;
      this.editor.layers['L' + this.editor.presentLayerIndex].title.fontWeight = `${fontWeight.value}`;
    }
   if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
  // Title editing


  // Text Editing
  changeOtherFontSize(x){
    if (document.getElementById(`otherText${this.editor.presentLayerIndex}`).value == '') {
      alert('Add text!');
    }else{
      if (x == 'select') {
        var fontSize = document.getElementById(`otherFontSize${this.editor.presentLayerIndex}`);
        var applyto = document.querySelector(`#text${this.editor.presentLayerIndex} .titleText`);
        applyto.style.fontSize = `${fontSize.value}`;
        this.editor.layers['L' + this.editor.presentLayerIndex].otherText.fontSize = `${fontSize.value}`;
      }else{
        var fontSize = document.getElementById(`customOtherFontSize${this.editor.presentLayerIndex}`);
        var applyto = document.querySelector(`#text${this.editor.presentLayerIndex} .titleText`);
        applyto.style.fontSize = `${fontSize.value}`;
        this.editor.layers['L' + this.editor.presentLayerIndex].otherText.fontSize = `${fontSize.value}`;
      }
    }
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
  editText(x){
    var text = document.getElementById(`${x}`);
    this.editor.layers['L' + this.editor.presentLayerIndex].otherText.text = `${text.innerHTML}`;
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
  changeOtherFontWeight(){
    if (document.getElementById(`otherText${this.editor.presentLayerIndex}`).value == '') {
      alert('Add text!');
    }else{
      var fontWeight = document.getElementById(`otherFontWeight${this.editor.presentLayerIndex}`);
      var applyto = document.querySelector(`#text${this.editor.presentLayerIndex} .titleText`);
      applyto.style.fontWeight = `${fontWeight.value}`;
      this.editor.layers['L' + this.editor.presentLayerIndex].otherText.fontWeight = `${fontWeight.value}`;
    }
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
  changeOtherFontFamily(x){
    if (document.getElementById(`otherText${this.editor.presentLayerIndex}`).value == '') {
      alert('Add text!');
    }else{
      var applyto = document.querySelector(`#text${this.editor.presentLayerIndex} .titleText`);
      if (x == 'custom') {
        var fontFamily = document.getElementById(`otherCustomFontFamily${this.editor.presentLayerIndex}`);
        applyto.style.fontFamily = `${fontFamily.value}`;
        this.editor.layers['L' + this.editor.presentLayerIndex].otherText.fontFamily = `${fontFamily.value}`;
      }else{
        var fontFamily = document.getElementById(`otherFontFamily${this.editor.presentLayerIndex}`);
        applyto.style.fontFamily = `${fontFamily.value}`;
        this.editor.layers['L' + this.editor.presentLayerIndex].otherText.fontFamily = `${fontFamily.value}`;
      }
    }
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
  // Text Editing

   // Meta Data //
  updateMetaData(x){
    var url = document.getElementById("storyUrl");
    var title = document.getElementById("storyTitle");
    var description = document.getElementById("storyDescription");
    var keywords = document.getElementById("storyKeywords");
    if (x == 'update') {
      url.value = this.editor.metaData.url;
      title.value =  this.editor.metaData.title
      description.value =  this.editor.metaData.description;
      keywords.value = this.editor.metaData.keywords;
    }else{
      this.editor.metaData.url = url.value;
      this.editor.metaData.title = title.value;
      this.editor.metaData.description = description.value;
      this.editor.metaData.keywords =keywords.value;
      if (description.value == '') {
        document.getElementById('otherText0').innerHTML = 'Enter story description..';
        document.getElementById('titleText0').innerHTML = title.value;
      }else if(title.value == ''){
        document.getElementById('otherText0').innerHTML = description.value;
        document.getElementById('titleText0').innerHTML = 'Enter Story Title';
      }else{
        document.getElementById('otherText0').innerHTML = description.value;
        document.getElementById('titleText0').innerHTML = title.value;
      }
    }
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }

  editStoryDescription(x){
    var descriptionIn = document.getElementById("storyDescription");
    var description = document.getElementById(`${x}`);
    this.editor.metaData.description = description.innerHTML;
    descriptionIn.value = description.innerHTML;
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }

  editStoryTitle(x){
    var titleIn = document.getElementById("storyTitle");
    var title = document.getElementById(`${x}`);
    this.editor.metaData.title = title.innerHTML;
    titleIn.value = title.innerHTML;
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
   // Meta Data //

  saveToBrowser(){
    var dat = {
      version : editor.version,
      layers : editor.layers,
      metaData : editor.metaData
    };
    window.localStorage.setItem(`${editor.storyID}`, JSON.stringify(dat));
  }

hexToRgb(hexColor) {
    // Remove the # symbol if present
    hexColor = hexColor.replace("#", "");

    // Convert the hex values to decimal values
    const r = parseInt(hexColor.substring(0, 2), 16);
    const g = parseInt(hexColor.substring(2, 4), 16);
    const b = parseInt(hexColor.substring(4, 6), 16);

    // Return the RGB format with commas
    return `${r}, ${g}, ${b}`;
  }
}



let edits = new Edits();
