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
  expandOptions(className){
      var option = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .${className} .options`);
      var icon =   document.querySelector(`#styleBox${this.editor.presentLayerIndex} .${className} .upDownIcon`);
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
  expandStaticOptions(className){
      var option = document.querySelector(`#metaData .${className} .options`);
      var icon =   document.querySelector(`#metaData .${className} .upDownIcon`);
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

  expandMoreOptions(className){
      var option = document.querySelector(`#moreInfo .${className} .options`);
      var icon =   document.querySelector(`#moreInfo .${className} .upDownIcon`);
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

  togglePromotion(element){
    var span = element.querySelector('span');
    var icon = element.querySelector('i');
    if (icon.classList.contains('enabledText')) {
      if (icon.classList.contains('fa-toggle-on')) {
        icon.classList.remove('fa-toggle-on');
        icon.classList.add('fa-toggle-off');
      }
      icon.classList.remove('enabledText');
      icon.classList.add('disabledText');
      span.innerHTML = 'Disabled';
      this.editor.metaData.selfPromotion = false;
    }else{
      if (icon.classList.contains('fa-toggle-off')) {
        icon.classList.remove('fa-toggle-off');
        icon.classList.add('fa-toggle-on');
      }
      icon.classList.add('enabledText');
      icon.classList.remove('disabledText');
      span.innerHTML = 'Enabled';
      this.editor.metaData.selfPromotion = true;
    }
    this.saveToBrowser();
  }

  // Media Editing
  selectMedia(link, type, olink){
      var hsLeft = document.getElementById('hsLeft');
      var hsRight = document.getElementById('hsRight');
      var leftSection = document.getElementById('leftSection');
      if (type == 'image') {
        if(document.querySelector(`#layer${this.editor.presentLayerIndex} .mediaContent`)){
          document.querySelector(`#layer${this.editor.presentLayerIndex} .mediaContent`).remove();
        }
        var layerId =  editor.presentLayerIndex;
        if (document.querySelector(`#layer${this.editor.presentLayerIndex} .videoControls`)) {
            document.querySelector(`#layer${this.editor.presentLayerIndex} .videoControls`).remove();
        }
        edits.modifyMedia('image', link, olink);

        var layer = document.getElementById(`layer${layerId}`);
        var imageElement = document.createElement('img');
        imageElement.className = `mediaContent`;
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

        // Text Visibility
        if (editor.presentLayerIndex != 0) {
          var button = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .toggleText`);
          if (this.editor.layers['L' + this.editor.presentLayerIndex].textVisibility == '' || this.editor.layers['L' + this.editor.presentLayerIndex].textVisibility == 'false') {
            this.editor.layers['L' + this.editor.presentLayerIndex].textVisibility = 'false';
            if(button.classList.contains('fa-toggle-on')){
              button.classList.remove('fa-toggle-on')
            }
            if(button.classList.contains('enabledText')){
              button.classList.remove('enabledText')
            }
            if(!button.classList.contains('fa-toggle-off')){
              button.classList.add('fa-toggle-off')
            }
            document.querySelector(`#layer${this.editor.presentLayerIndex} .otherText`).style.display = 'none';
            document.querySelector(`#layer${this.editor.presentLayerIndex} .titleText`).style.display = 'none';
          }else{
            this.editor.layers['L' + this.editor.presentLayerIndex].textVisibility = 'true';
            if(button.classList.contains('fa-toggle-off')){
              button.classList.remove('fa-toggle-off')
            }
            if(!button.classList.contains('enabledText')){
              button.classList.add('enabledText')
            }
            if(!button.classList.contains('fa-toggle-on')){
              button.classList.add('fa-toggle-on')
            }
          }
        }

        if (editor.presentLayerIndex == 0) {
          alert('Please use photo for thumbnail');
        }else{
          if(document.querySelector(`#layer${this.editor.presentLayerIndex} .mediaContent`)){
            document.querySelector(`#layer${this.editor.presentLayerIndex} .mediaContent`).remove();
          }
          edits.modifyMedia('video', link, olink);
          var layerId =  editor.presentLayerIndex;
          var layer = document.getElementById(`layer${layerId}`);

          var videoElement = document.createElement('video');
          videoElement.src = link;
          videoElement.type = 'video/mp4';
          videoElement.classList.add('mediaContent');
          var contorlsElements = document.createElement('div');
          contorlsElements.className = 'videoControls';
          contorlsElements.innerHTML = `
          <i class="fa-regular fa-volume-high" id="muteUnmute" data-status="unmuted" onclick="editor.muteUnmute()"></i>
          <i class="fa fa-play" id="playPauseMedia" data-status="paused" onclick="editor.playPauseMedia()"></i>
          <i class="fa fa-paper-plane"></i>
          `;

          layer.appendChild(contorlsElements);
          layer.appendChild(videoElement);
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
    document.querySelector(`#layer${this.editor.presentLayerIndex} .mediaContent`).remove();
    // adding text if not exists
    var button = document.querySelector(`#layer${this.editor.presentLayerIndex} .toggleText`);
    if (this.editor.presentLayerIndex != 0) {
      this.editor.layers['L' + this.editor.presentLayerIndex].textVisibility = '';
      var text = document.querySelector(`#layer${this.editor.presentLayerIndex} .otherText`);
      var title = document.querySelector(`#layer${this.editor.presentLayerIndex} .titleText`);
      if (title.style.display == 'none') {
        title.style.display = 'flex';
      }
      if (text.style.display == 'none') {
        text.style.display = 'flex';
      }
      if(button.classList.contains('fa-toggle-off')){
        button.classList.remove('fa-toggle-off')
      }
      if(!button.classList.contains('enabledText')){
        button.classList.add('enabledText')
      }
      if(!button.classList.contains('fa-toggle-on')){
        button.classList.add('fa-toggle-on')
      }
    }

    if (type == 'video') {
      document.querySelector(`#layer${this.editor.presentLayerIndex} .videoControls`).remove();
    }

    var tmpImage = document.createElement('img');
    tmpImage.className =`mediaContent`;
    tmpImage.src = "/assets/img/default.jpeg";
    document.getElementById(`layer${this.editor.presentLayerIndex}`).appendChild(tmpImage);
    this.editor.layers['L' + this.editor.presentLayerIndex].media.url = 'default';
    this.editor.layers['L' + this.editor.presentLayerIndex].media.blobUrl = 'default';
    this.editor.layers['L' + this.editor.presentLayerIndex].media.type = '';
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
  modifyMedia(type, blobUrl, url){
      var deleteMediaButton = document.getElementById('deleteMedia');
      if (type == 'image') {
          deleteMediaButton.setAttribute("onclick", "edits.deleteMedia('image')");
          if (this.editor.presentLayerIndex != 0) {
            var button = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .toggleText`);
             if(button.classList.contains('fa-toggle-off')){
                button.classList.remove('fa-toggle-off')
              }
              if(!button.classList.contains('enabledText')){
                button.classList.add('enabledText')
              }
              if(!button.classList.contains('fa-toggle-on')){
                button.classList.add('fa-toggle-on')
              }
              document.querySelector(`#layer${this.editor.presentLayerIndex} .otherText`).style.display = 'flex';
              document.querySelector(`#layer${this.editor.presentLayerIndex} .titleText`).style.display = 'flex';
          }

      }else if(type == 'video'){
          deleteMediaButton.setAttribute("onclick", "edits.deleteMedia('video')");
      }else{
          deleteMediaButton.removeAttribute("onclick");
      }
      document.querySelector(`#styleBox${this.editor.presentLayerIndex} .mediaStyles`).style.display = 'flex';
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
      this.saveToBrowser();
  }
  overlayEdit(){
    this.overlayOpacity = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .mediaOverlayOpacity`).value;
    var overlayOpacity = this.overlayOpacity;
    this.editor.layers['L' + this.editor.presentLayerIndex].media.styles.overlayOpacity = overlayOpacity;

    document.querySelector(`#layer${this.editor.presentLayerIndex} .layersTop`).style.backgroundColor = `rgba(0,0,0,${overlayOpacity}%)`;
    this.saveToBrowser();

  }
  mediaFit(){
    if (this.editor.layers['L' + this.editor.presentLayerIndex].media.blobUrl == undefined) {
      alert('Add photo or video');
    }else{
      var mediaFit = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .mediaFit`);
      var mediaContent = document.querySelector(`#layer${this.editor.presentLayerIndex} .mediaContent`);
      this.editor.layers['L' + this.editor.presentLayerIndex].media.styles.mediaFit = mediaFit.value;
      mediaContent.style.objectFit = `${mediaFit.value}`;
    }
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }

  mediaCredit(){
    var applyFrom = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .mediaCredit`);

    var applyto =  document.querySelector(`#layer${this.editor.presentLayerIndex} .imageCredit`);
    applyto.innerHTML = applyFrom.value;
    this.editor.layers['L' + this.editor.presentLayerIndex].media.credit = `${applyFrom.value}`;
    this.saveToBrowser();
  }

  mediaTitle(input){
    var applyto =  document.querySelector(`#styleBox${this.editor.presentLayerIndex} .mediaTitle`);
    applyto.innerHTML = input.value;
    this.editor.layers['L' + this.editor.presentLayerIndex].media.title = `${input.value}`;
    this.saveToBrowser();
  }
    // Media Editing


// Text Visibility
   containsText(){
     // Text Visibility
     var button = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .toggleText`);
     if(button.classList.contains('fa-toggle-on')){
       if (this.editor.layers['L' + this.editor.presentLayerIndex].media.type == 'video') {
         button.classList.remove('fa-toggle-on')
         if(button.classList.contains('enabledText')){
           button.classList.remove('enabledText')
         }
         if(!button.classList.contains('fa-toggle-off')){
           button.classList.add('fa-toggle-off')
         }
         this.editor.layers['L' + this.editor.presentLayerIndex].textVisibility = 'false';
         document.querySelector(`#layer${this.editor.presentLayerIndex} .otherText`).style.display = 'none';
         document.querySelector(`#layer${this.editor.presentLayerIndex} .titleText`).style.display = 'none';
       }else{
         alert('You can hide text only in videos');
       }
     }else{
       button.classList.remove('fa-toggle-off')
       if(!button.classList.contains('enabledText')){
         button.classList.add('enabledText')
       }
       if(!button.classList.contains('fa-toggle-on')){
         button.classList.add('fa-toggle-on')
       }
       this.editor.layers['L' + this.editor.presentLayerIndex].textVisibility = 'true';
       document.querySelector(`#layer${this.editor.presentLayerIndex} .otherText`).style.display = 'flex';
       document.querySelector(`#layer${this.editor.presentLayerIndex} .titleText`).style.display = 'flex';
     }
     this.saveToBrowser();
   }
  // Title Editing
  changeFontSize(x){
    if (x == 'select') {
      var fontSize = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .titleFontSize`);
      var applyto = document.querySelector(`#layer${this.editor.presentLayerIndex} .titleText`);
      var appliedSize = parseFloat(fontSize.value);
      if (this.editor.presentLayerIndex == 0) {
        var percent  = (35 * appliedSize) / 100;
      }else{
        var percent  = (13 * appliedSize) / 100;
      }
      appliedSize = appliedSize - percent;
      appliedSize = `${appliedSize}px`;
      applyto.style.fontSize = appliedSize;
      this.editor.layers['L' + this.editor.presentLayerIndex].title.fontSize = `${fontSize.value}`;
    }else{
      var fontSize = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .customFontSize`);
      var applyto = document.querySelector(`#layer${this.editor.presentLayerIndex} .titleText`);
      var appliedSize = parseFloat(fontSize.value);
      if (this.editor.presentLayerIndex == 0) {
        var percent  = (35 * appliedSize) / 100;
      }else{
        var percent  = (13 * appliedSize) / 100;
      }
      appliedSize = appliedSize - percent;
      appliedSize = `${appliedSize}px`;
      applyto.style.fontSize = appliedSize;
      this.editor.layers['L' + this.editor.presentLayerIndex].title.fontSize =  `${fontSize.value}`;
    }
    if (this.version+1 == editor.version) {
    this.version += 1;
    }
    this.saveToBrowser();
  }
  changeFontFamily(x){
    var applyto = document.querySelector(`#layer${this.editor.presentLayerIndex} .titleText`);
    if (x == 'custom') {
      var fontFamily = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .customFontFamily`);
      applyto.style.fontFamily = `${fontFamily.value}`;
      this.editor.layers['L' + this.editor.presentLayerIndex].title.fontFamily = `${fontFamily.value}`;
    }else{
      var fontFamily = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .fontFamily`);
      applyto.style.fontFamily = `${fontFamily.value}`;
      this.editor.layers['L' + this.editor.presentLayerIndex].title.fontFamily = `${fontFamily.value}`;
    }
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
  editTitle(element){
    this.editor.layers['L' + this.editor.presentLayerIndex].title.text = element.textContent;
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }

  changeFontWeight(){
    var fontWeight = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .titleFontWeight`);
    var applyto = document.querySelector(`#layer${this.editor.presentLayerIndex} .titleText`);
    applyto.style.fontWeight = `${fontWeight.value}`;
    this.editor.layers['L' + this.editor.presentLayerIndex].title.fontWeight = `${fontWeight.value}`;
    if (this.version+1 == editor.version) {
      this.version += 1;
     }
    this.saveToBrowser();
  }
  // Title editing


  // Text Editing
  changeOtherFontSize(x){
    if (x == 'select') {
      var fontSize = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .otherFontSize`);
      var applyto = document.querySelector(`#layer${this.editor.presentLayerIndex} .otherText`);
      var appliedSize = parseFloat(fontSize.value);
      if (this.editor.presentLayerIndex == 0) {
        var percent  = (35 * appliedSize) / 100;
      }else{
        var percent  = (10 * appliedSize) / 100;
      }
      appliedSize = appliedSize - percent;
      appliedSize = `${appliedSize}px`;
      applyto.style.fontSize = appliedSize;
      this.editor.layers['L' + this.editor.presentLayerIndex].otherText.fontSize = `${fontSize.value}`;
    }else{
      var fontSize = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .customOtherFontSize`);
      var applyto = document.querySelector(`#layer${this.editor.presentLayerIndex} .otherText`);
      var appliedSize = parseFloat(fontSize.value);
      if (this.editor.presentLayerIndex == 0) {
        var percent  = (35 * appliedSize) / 100;
      }else{
        var percent  = (10 * appliedSize) / 100;
      }
      appliedSize = appliedSize - percent;
      appliedSize = `${appliedSize}px`;
      applyto.style.fontSize = appliedSize;
      this.editor.layers['L' + this.editor.presentLayerIndex].otherText.fontSize = `${fontSize.value}`;
    }
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
  editText(element){
    this.editor.layers['L' + this.editor.presentLayerIndex].otherText.text = element.textContent;

    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
  changeOtherFontWeight(){
    var fontWeight = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .otherFontWeight`);
    var applyto = document.querySelector(`#layer${this.editor.presentLayerIndex} .otherText`);
    applyto.style.fontWeight = `${fontWeight.value}`;
    this.editor.layers['L' + this.editor.presentLayerIndex].otherText.fontWeight = `${fontWeight.value}`;
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
  changeOtherFontFamily(x){
    var applyto = document.querySelector(`#layer${this.editor.presentLayerIndex} .otherText`);
    if (x == 'custom') {
      var fontFamily = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .otherCustomFontFamily`);
      applyto.style.fontFamily = `${fontFamily.value}`;
      this.editor.layers['L' + this.editor.presentLayerIndex].otherText.fontFamily = `${fontFamily.value}`;
    }else{
      var fontFamily = document.querySelector(`#styleBox${this.editor.presentLayerIndex} .otherFontFamily`);
      applyto.style.fontFamily = `${fontFamily.value}`;
      this.editor.layers['L' + this.editor.presentLayerIndex].otherText.fontFamily = `${fontFamily.value}`;
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
    var category = document.getElementById("storyCategory");
    if (x == 'update') {
      url.value = this.editor.metaData.url;
      title.value =  this.editor.metaData.title
      description.value =  this.editor.metaData.description;
      keywords.value = this.editor.metaData.keywords;
      category.value =  this.editor.metaData.category;
    }else{
      const start = title.selectionStart;
      const end = title.selectionEnd;
      this.editor.metaData.url = url.value;
      this.editor.metaData.title = title.value;
      this.editor.metaData.description = description.value;
      this.editor.metaData.keywords = keywords.value;
      this.editor.metaData.category = category.value;
      title.setSelectionRange(start, end);
      if (description.value == '') {
        document.querySelector('#layer0 .titleText').innerHTML = title.value;
      }else if(title.value == ''){
        document.querySelector('#layer0 .titleText').innerHTML = 'Edit title for this webstory';
      }else{
        document.querySelector('#layer0 .titleText').innerHTML = title.value;
      }
    }
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }

  editStoryDescription(){
    var descriptionIn = document.getElementById("storyDescription");
    this.editor.metaData.description = description.innerHTML;
    descriptionIn.value = description.innerHTML;
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }

  editStoryTitle(){
    var titleIn = document.getElementById("storyTitle");
    var title = document.querySelector(`#layer${this.editor.presentLayerIndex} .titleText`);
    const start = window.getSelection().getRangeAt(0).startOffset;
    const end = window.getSelection().getRangeAt(0).endOffset;
    const text = title.textContent;
    titleIn.value = text;
    title.textContent = text;
    this.editor.metaData.title = text;
    // Restore cursor position
    const range = document.createRange();
    const sel = window.getSelection();
    range.setStart(title.firstChild, start);
    range.setEnd(title.firstChild, end);
    sel.removeAllRanges();
    sel.addRange(range);


    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
   // Meta Data //

   // Theme //
  editTheme(){
       var theme = document.getElementById(`editTheme${this.editor.presentLayerIndex}`).value;
       this.editor.layers['L' + this.editor.presentLayerIndex].theme = `${theme}`;
   }
   //  Theme //

  storyVisibility(){
    var storyVisibility = document.querySelector(`#storyVisibility`).value;
    this.editor.metaData.storyVisibility = `${storyVisibility}`;
    if (this.version+1 == editor.version) {
      this.version += 1;
    }
    this.saveToBrowser();
  }
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
