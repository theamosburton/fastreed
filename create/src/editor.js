class Editor{
    constructor(){
        var newBar = document.createElement('span');
        newBar.id = `nav1`;
        newBar.classList.add('nav');
        newBar.classList.add('active');
        this.topBars = document.getElementById("navBars");
        this.topBars.appendChild(newBar);


        var params = new URLSearchParams(window.location.search);
            this.username = '';
        if (params.get('username')) {
            this.username = params.get('username');
            this.whoIs = 'Admin';
        }else{
            this.whoIs = 'User';
        }
        this.storyID = params.get('ID');
        this.editorId = document.getElementById('editTab');

        const fetchWebstoryData = async () =>{
            const url = '/.ht/API/webstories.php';
            var encyDat = {
            'purpose' : 'fetch',
            'whois': `${this.whoIs}`,
            'storyID': `${this.storyID}`,
            'username': `${currentUsername}`
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
              const date = new Date();
              const month = date.toLocaleString('default', { month: 'long' });
              const day = date.getDate();
              const year = date.getFullYear();
              const fDate = `${month} ${day}, ${year}`;
                if (data.Result) {
                    this.version = 100;
                    this.layers = {};
                    this.metaData = {};
                    this.metaData.title = "";
                    this.metaData.description = "";
                    this.metaData.keywords = "";
                    this.metaData.url = "";
                    // JavaScript
                    editor.metaData.timeStamp = Date.now();
                    this.metaData.date = `${fDate}`;
                    this.metaData.storyVisibility = 'Public';
                    this.webstoryData = data.message;
                    var jObject = JSON.parse(this.webstoryData);
                    // checking if story is empty or not
                    if (!jObject.version) {
                      if (window.localStorage.getItem(`${editor.storyID}`)) {
                        this.continueWith('browser');
                      }else{
                        this.storyStatus = 'drafted';
                        this.presentLayerIndex = 0;
                        this.presentLayer  = this.presentLayerIndex + 1 ;
                        this.totalLayers = 1;
                        this.layers['L0'] = {
                            'media': {
                                "blobUrl":'',
                                "styles":{
                                    "overlayOpacity": "0",
                                    "mediaFit":"cover",
                                    "overlayArea": "40"
                                },
                                "type":'',
                                "url":'default',
                                "credit" : 'none'
                            },
                            'title':{
                                "text":'',
                                "fontFamily":"BebasNeue",
                                "fontWeight":"1000",
                                "fontSize":"20px"
                            },
                            'theme':'default'
                        };

                        if (this.editorId.children.length <= 0) {
                          var newLayer = document.createElement('div');
                          newLayer.id = `layer${this.presentLayerIndex}`;
                          newLayer.className = 'layers';
                          var layersTop = document.createElement('div');
                          layersTop.classList.add('layersTop');
                          layersTop.classList.add('defaultFront');
                          layersTop.id = `layersTop${this.presentLayerIndex}`;
                          layersTop.innerHTML = `
                          <div class="title">
                            <div class="box">
                              <span class="date">${this.metaData.date}</span>
                              <span class="titleText"  contenteditable="true" onkeyup="edits.editStoryTitle('')">Edit title for this webstory</span>
                              <div class="creditBox">
                                <span class="imageCredit" onkeyup="mediaCredit()">Media Credit</span>
                              </div>
                            </div>
                          </div>`;
                          var defaultImage = document.createElement('img');
                          defaultImage.src = "/assets/img/default.jpeg";
                          defaultImage.className = 'mediaContent';
                          this.editorId.appendChild(newLayer);

                          newLayer.appendChild(layersTop);
                          newLayer.appendChild(defaultImage);
                        }

                        this.presentLayerDiv = document.getElementById(`layer${this.presentLayerIndex}`);
                        var otherLayers = document.querySelector("#editTab .layers");
                        for (var i = 0; i < otherLayers.length; i++) {
                            otherLayers[i].style.display = "none";
                        }
                        this.presentLayerDiv.style.display = 'flex';
                        document.getElementById('layerNumber').innerHTML = `Layer ${this.presentLayer}`;
                        this.createStylesheet();
                        var dat = {
                          version : this.version+1,
                          layers : this.layers,
                          metaData : this.metaData,
                          storyStatus : 'drafted'
                        };
                        // window.localStorage.setItem(`${editor.storyID}`, JSON.stringify(dat));
                      }
                    }else{
                        this.createExistedLayers();
                    }
                }else{
                    alert(data.message);
                }
            }else{
                alert("Somoething went Wrong");
            }
        }
        fetchWebstoryData();
    }

    createExistedLayers(){
      var jsonString = this.webstoryData;
      var jsObject = JSON.parse(jsonString);
      var storyStatus = JSON.parse(jsObject.storyStatus);
      if (storyStatus.status == 'drafted') {
        document.getElementById('publishStory').innerHTML = 'Publish';
      }else if (storyStatus.status == 'published'){
        var publish =  document.getElementById('publishStory');
        var save =  document.getElementById('saveStory')
        publish.innerHTML = 'Draft';
        save.innerHTML = 'Update';
        publish.setAttribute('onclick', 'editor.draftStory()');
        save.setAttribute('onclick', 'editor.updateStory()');
      }
      // console.log(jsObject);
      var browserData = window.localStorage.getItem(`${editor.storyID}`);
      browserData = JSON.parse(browserData);
      if (browserData) {
        if (browserData.version == jsObject.version) {
          this.metaData = jsObject.metaData;
          this.layers = jsObject.layers;
          this.version = jsObject.version;
          this.storyStatus = storyStatus.status;
          this.createExistedStory();
        }else{
          var alertCont = document.querySelector('.altertContainer');
          alertCont.style.display = 'flex';
          var bv = this.numberToVersion(browserData.version);
          var fv = this.numberToVersion(jsObject.version);
          document.querySelector('.altertDiv').innerHTML =
          `<div class="title">
            Choose Story Version !
          </div>
          <div class="describe">
            We have two different versions of your webstory kindly select the <b> latest one</b> to continue.
          </div>
          <div class="options">
            <div class="option" id="browser" onclick="editor.continueWith('browser')">Browser(v${bv})</div>
            <div class="option" id="fastreed" onclick="editor.continueWith('fastreed')">Fastreed(v${fv})</div>
          </div>`;
        }
      }else{
        this.metaData = jsObject.metaData;
        this.layers = jsObject.layers;
        this.version = jsObject.version;
        this.storyStatus = storyStatus.status;
        this.createExistedStory();
      }

  }
    continueWith(x){
      var jsonString = this.webstoryData;
      var jsObject = JSON.parse(jsonString);
      var browserData = window.localStorage.getItem(`${editor.storyID}`);
      var alertCont = document.querySelector('.altertContainer');
      browserData = JSON.parse(browserData);
      if (x == 'browser') {
        this.metaData = browserData.metaData;
        this.layers = browserData.layers;
        this.version = browserData.version;
        this.storyStatus = browserData.storyStatus;
        alertCont.style.display = 'none';
        this.createExistedStory();
      }else{
        this.metaData = jsObject.metaData;
        this.layers = jsObject.layers;
        this.version = jsObject.version;
        this.storyStatus = jsObject.storyStatus;
        alertCont.style.display = 'none';
        this.createExistedStory();
      }
    }
    createNewLayer(){
      if (this.totalLayers <= 11) {
        this.inBetweenLayersAdd();
        this.totalLayers += 1;
        this.presentLayerIndex += 1;
        this.presentLayer  = this.presentLayerIndex + 1 ;
        this.layers['L'+ this.presentLayerIndex] = {
            'media': {
                "blobUrl":'',
                "styles":{
                    "overlayOpacity": "0",
                    "mediaFit":"cover",
                    "overlayArea": "40"
                },
                "type":'',
                "url":'',
                "credit" : 'none'
            },
            'title':{
                "text":'',
                "fontFamily":"Poppins-medium",
                "fontWeight":"1000",
                "fontSize":"20px"
            },
            'theme':'default',
            'textVisibility': '',
            'otherText': {
              "text":'',
              "fontFamily":"Poppins-regular",
              "fontWeight":"400",
              "fontSize":"16px"
            }
        };
        var newLayer = document.createElement('div');
        newLayer.id = `layer${this.presentLayerIndex}`;
        newLayer.className = 'layers';
        this.editorId.appendChild(newLayer);
        this.presentLayerDiv = document.getElementById(`layer${this.presentLayerIndex}`);
        for (var i = 0; i < this.totalLayers; i++) {
            document.getElementById(`layer${i}`).style.display = 'none';
        }
        var layersTop = document.createElement('div');
        layersTop.classList.add('layersTop');
        layersTop.classList.add('defaultOther');
        layersTop.innerHTML = `
        <div class="title" id="title">
            <span class="titleText" contenteditable="true" onkeyup="edits.editTitle()">Edit title text</span>

            <span class="otherText" contenteditable="true" onkeyup="edits.editText()">Edit description text</span>

            <div class="creditBox">
              <span class="imageCredit"  id="imageCredit" onkeyup="mediaCredit()">Media Credit</span>
            </div>
        </div>
        `;
        var previousElement = document.getElementById(`nav${this.presentLayer-1}`);
        var newBar = document.createElement('span');
        newBar.id = `nav${this.presentLayer}`;
        newBar.classList.add('nav');
        newBar.classList.add('active');

        this.topBars.insertBefore(newBar, previousElement.nextSibling);
        for (var i = 1; i < this.totalLayers; i++) {
          this.topBars.querySelector(`#nav${i}`).classList.remove('active');
        }
        this.topBars.querySelector(`#nav${this.presentLayer}`).classList.add('active');

        var defaultImage = document.createElement('img');
        defaultImage.src = "/assets/img/default.jpeg";
        defaultImage.classList.add('mediaContent');
        newLayer.appendChild(layersTop);
        newLayer.appendChild(defaultImage);
        // newLayer.appendChild(headElement);
        this.presentLayerDiv.style.display = 'flex';
        this.playPauseLastMedia('add');
        this.createStylesheet();
        document.getElementById('layerNumber').innerHTML = `Layer ${this.presentLayer}`;
      }else{
        var alertCont = document.querySelector('.altertContainer');
        alertCont.style.display = 'flex';
        alertCont.id = 'errorConatiner';
        document.querySelector('.altertDiv').innerHTML =
        `<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Sorry!</strong>12 layers Max.
            <button type="button" class="close" data-dismiss="alert" onclick="cancelError()" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>`;
          document.querySelector('.altertDiv').style.background = 'transparent';
      }
    }

    createStylesheet(){
        var styleBox = document.getElementById('objectOptions');
        var styleBoxn = document.createElement('div');
        styleBoxn.id = `styleBox${this.presentLayerIndex}`;
        styleBoxn.classList.add('objectOptionsbody');
        if (this.presentLayerIndex != 0) {
          // For non front pages
          var otherText = `
          <!-- Other Text Styles -->
          <div class="optionsDIv aboutStyles">
              <span class="objectName" onclick="edits.expandOptions('aboutStyles')">
                  <span>Text</span>
                  <i class="upDownIcon fa fa-caret-right"></i>
              </span>
              <div class="options" style="display:none;">


                  <div class="div">
                      <span>Font weight</span>
                      <select onchange="edits.changeOtherFontWeight()" class="otherFontWeight value inputText">
                          <option value="lighter" selected>Light</option>
                          <option value="600">Bold</option>
                          <option value="1000">Bolder</option>
                      </select>
                  </div>
                  <div class="div">
                      <span>Font size</span>
                      <select onchange="edits.changeOtherFontSize('select')"  class="otherFontSize value inputText">
                          <option value="medium">Medium</option>
                          <option value="large">Large</option>
                          <option value="small">Small</option>
                          <option value="x-small">X-Smaller</option>
                      </select>
                      <br/>
                      <span>Custom font size</span>
                      <input class="customOtherFontSize value inputText text" type="text" placeholder="e.g. 1rem, 30px, x-large, .8em etc." onkeyup="edits.changeOtherFontSize('custom')">
                  </div>

                  <div class="div">
                      <span>Font family</span>
                      <select onchange="edits.changeOtherFontFamily('select')"  class="otherFontFamily value inputText">
                          <option value="inherit">Auto</option>
                          <option value="cursive">Cursive</option>
                          <option value="monospace">Monospace</option>
                          <option value="sans-serif">Sans-serif</option>
                      </select>
                      <br/>
                      <span>Custom font family</span>
                      <input type="text" class="otherCustomFontFamily value inputText text" onkeyup="edits.changeOtherFontFamily('custom')" placeholder="e.g. verdana, Sans-serif etc.">
                  </div>
              </div>
          </div>
          <!-- Other Text Styles -->`;
          var textVisi = `<div class="div"  onclick="edits.containsText()">
            <span>Text Visibility</span>
            <i class="toggleText enabledText fa fa-solid fa-lg fa-toggle-on"></i>
          </div>`;
        }else{
          // For front pages
          var otherText = '';
          var textVisi = '';
        }
        styleBoxn.innerHTML = `

                    <!-- Media Options -->
                    <div class="optionsDIv mediaStyles" >
                        <span class="objectName" onclick="edits.expandOptions('mediaStyles')">
                            <span>Media</span>
                            <i class="upDownIcon fa fa-caret-down"></i>
                        </span>

                        <div class="options" style="display: block;">
                            <div class="div">
                                <span>Media Fit</span>
                                <select onchange="edits.mediaFit()" class="mediaFit value inputText">
                                    <option value="fill">Fill</option>
                                    <option value="none">None</option>
                                    <option value="cover" selected>Cover</option>
                                    <option value="contain">Contain</option>
                                </select>
                            </div>


                            <div class="div">
                                <span>Shade Opacity</span>
                                <input onchange="edits.overlayEdit()" class="mediaOverlayOpacity value inputText" type="range" value="10">
                            </div>

                            <div class="div">
                                <span>Media Credit</span>
                                <input class="mediaCredit value inputText text" type="text" placeholder="Blank for none" onkeyup="edits.mediaCredit()">
                            </div>
                            ${textVisi}
                        </div>
                    </div>
                    <!-- Media Options -->

                    <!-- Text Styles -->
                    <div class="optionsDIv titleStyles">
                        <span class="objectName" onclick="edits.expandOptions('titleStyles')">
                            <span>Title</span>
                            <i class="upDownIcon fa fa-caret-right"></i>
                        </span>
                        <div class="options" style="display:none;">
                            <div class="div">
                                <span>Font weight</span>
                                <select onchange="edits.changeFontWeight()" class="titleFontWeight value inputText">
                                    <option value="400">Light</option>
                                    <option value="700" >Bold</option>
                                    <option value="1000" selected>Bolder</option>
                                </select>
                            </div>


                            <div class="div">
                                <span>Font size</span>
                                <select  onchange="edits.changeFontSize('select')" class="titleFontSize value inputText">
                                    <option value="medium">Medium</option>
                                    <option value="large">Large</option>
                                    <option value="larger" selected>X-Larger</option>
                                </select>
                                <br/>
                                <span>Custom font size</span>
                                <input class="customFontSize value inputText text" type="text"  placeholder="e.g. 1rem, 30px, x-large, .8em etc." onkeyup="edits.changeFontSize('custom')">
                            </div>


                            <div class="div">
                                <span>Font family</span>
                                <select  onchange="edits.changeFontFamily('select')"  class="fontFamily value inputText">
                                    <option value="inherit">Auto</option>
                                    <option value="cursive">Cursive</option>
                                    <option value="monospace">Monospace</option>
                                    <option value="sans-serif">Sans-serif</option>
                                </select>
                                <br/>
                                <span>Custom font family</span>
                                <input type="text" class="customFontFamily value inputText text" onkeyup="edits.changeFontFamily('custom')" placeholder="e.g. verdana, Sans-serif etc.">
                            </div>
                        </div>
                    </div>
                    <!-- Text Styles -->
                    ${otherText}
            </div>
        `;
        styleBox.appendChild(styleBoxn);
        for (var i = 0; i < this.totalLayers; i++) {
            document.getElementById(`styleBox${i}`).style.display = 'none';
        }
        document.getElementById(`styleBox${this.presentLayerIndex}`).style.display = 'flex';
    }

    inBetweenLayersAdd(){
        if (this.presentLayerIndex+1 != this.totalLayers) {
            var presentLayer  = this.presentLayerIndex + 1 ;
            var layersAhead = this.totalLayers - presentLayer;
            for (let i = layersAhead; i >= 1; i--) {
                var layersIndex = presentLayer + i - 1;
                document.getElementById(`layer${layersIndex}`).id = `layer${layersIndex + 1}`;
                document.getElementById(`styleBox${layersIndex}`).id = `styleBox${layersIndex + 1}`;
                let newLayer =  layersIndex +1
                this.layers['L' + newLayer] = this.layers['L' + layersIndex];
                delete this.layers['L' + layersIndex];
            }
            // For Bars
            for (var j = layersAhead; j >= 1; j--) {
              var layersIndex = presentLayer + j;
                document.getElementById(`nav${layersIndex}`).id = `nav${layersIndex + 1}`;
            }
        }
    }

    deleteLayer(){
        if (this.totalLayers > 1 && this.presentLayerIndex != 0) {
            this.inBetweenLayersDel();
            delete this.layers['L'+ this.presentLayerIndex];

            var presentLayer  = this.presentLayerIndex + 1 ;
            var layersAhead = this.totalLayers - presentLayer;
            for (let l = 1; l <= layersAhead; l++) {
                var layersIndex = presentLayer + l - 1;
                  let newLayer =  layersIndex -1
                this.layers['L' + newLayer] = this.layers['LL' + layersIndex];
                delete this.layers['LL' + layersIndex];
            }

            this.totalLayers -= 1;
            this.presentLayerDiv = document.getElementById(`styleBox${this.presentLayerIndex}`);
            this.presentLayerDiv.remove();
            document.getElementById(`layer${this.presentLayerIndex}`).remove();
            document.getElementById(`nav${this.presentLayerIndex+1}`).remove();
            this.moveBackward();
        }else{
            alert('Can\'t delete first layer')
        }
    }

    inBetweenLayersDel(){
        if (this.presentLayerIndex+1 != this.totalLayers) {
            var presentLayer  = this.presentLayerIndex + 1 ;
            var layersAhead = this.totalLayers - presentLayer;
            for (let i = layersAhead; i >= 1; i--) {
                var layersIndex = presentLayer + i - 1;
                document.getElementById(`layer${layersIndex}`).id = `layer${layersIndex - 1}`;
                document.getElementById(`styleBox${layersIndex}`).id = `styleBox${layersIndex - 1}`;
            }

            for (let k = 1; k <= layersAhead; k++) {
                var layersIndex = presentLayer + k - 1;
                this.layers['LL' + layersIndex] = this.layers['L' + layersIndex];
                delete this.layers['L' + layersIndex];
            }

            for (var j = layersAhead; j >= 1; j--) {
              var layersIndex = presentLayer + j;
                document.getElementById(`nav${layersIndex}`).id = `nav${layersIndex - 1}`;
            }
        }
    }


    moveForward(){
        var endOfLayers  = this.totalLayers <= this.presentLayerIndex +1;
        if (!endOfLayers) {
            this.presentLayerIndex  += 1;
            this.presentLayerDiv = document.getElementById(`layer${this.presentLayerIndex}`);
            for (var i = 0; i < this.totalLayers; i++) {
                document.getElementById(`layer${i}`).style.display = 'none';
                document.getElementById(`styleBox${i}`).style.display = 'none';
            }
            this.presentLayerDiv.style.display = 'flex';
            document.getElementById(`styleBox${this.presentLayerIndex}`).style.display = 'flex';
            this.playPauseLastMedia('forward');

        }
        this.presentLayer  = this.presentLayerIndex + 1 ;
        for (var i = 1; i < this.totalLayers+1; i++) {
          this.topBars.querySelector(`#nav${i}`).classList.remove('active');
        }
        this.topBars.querySelector(`#nav${this.presentLayer}`).classList.add('active');

        document.getElementById('layerNumber').innerHTML = `Layer ${this.presentLayer}`;

    }

    moveBackward(){
        if (this.presentLayerIndex > 0) {
            this.presentLayerIndex -= 1;
            this.presentLayer  = this.presentLayerIndex + 1 ;
            this.presentLayerDiv = document.getElementById(`layer${this.presentLayerIndex}`);
            for (var i = 0; i < this.totalLayers; i++) {
                document.getElementById(`layer${i}`).style.display = 'none';
                document.getElementById(`styleBox${i}`).style.display = 'none';
            }
            this.presentLayerDiv.style.display = 'flex';
            document.getElementById(`styleBox${this.presentLayerIndex}`).style.display = 'flex';
            this.playPauseLastMedia('backward');

        }
        for (var i = 1; i < this.totalLayers+1; i++) {
          this.topBars.querySelector(`#nav${i}`).classList.remove('active');
        }
        this.topBars.querySelector(`#nav${this.presentLayer}`).classList.add('active');
        document.getElementById('layerNumber').innerHTML = `Layer ${this.presentLayer}`;
    }
    playPauseMedia(){
        var playPauseMedia = document.querySelector(`#layer${this.presentLayerIndex} #playPauseMedia`);
        var video = document.querySelector(`#layer${this.presentLayerIndex} video`);
        var status = playPauseMedia.dataset.status;
        if (playPauseMedia.classList.contains("fa-play")) {
            playPauseMedia.classList.remove("fa-play");
            playPauseMedia.classList.add("fa-pause");
        } else {
            playPauseMedia.classList.remove("fa-pause");
            playPauseMedia.classList.add("fa-play");
        }
        if (status == 'paused') {
            video.play();
            playPauseMedia.dataset.status = 'playing';
            video.loop = !video.loop;
        }else if(status == 'playing'){
            video.pause();
            playPauseMedia.dataset.status = 'paused';
        }else{
            video.pause();
            playPauseMedia.dataset.status = 'paused';
        }
    }
    muteUnmute(){
        var muteUnmute = document.querySelector(`#layer${this.presentLayerIndex} #muteUnmute`);
        var video = document.querySelector(`#layer${this.presentLayerIndex} video`);
        var status = muteUnmute.dataset.status;

        if (muteUnmute.classList.contains("fa-volume-high")) {
            muteUnmute.classList.remove("fa-volume-high");
            muteUnmute.classList.add("fa-volume-xmark");
        } else {
            muteUnmute.classList.remove("fa-volume-xmark");
            muteUnmute.classList.add("fa-volume-high");
        }

        if (status == 'muted') {
            video.muted = false;
            muteUnmute.dataset.status = 'unmuted';
        }else if(status == 'unmuted'){
            video.muted = true;
            muteUnmute.dataset.status = 'muted';
        }else{
            video.muted = true;
            muteUnmute.dataset.status = 'muted';
        }
    }


    playPauseLastMedia(direction){
        let presentLayerIndex = this.presentLayerIndex-1;
        if (direction == 'add') {
            let presentLayerMedia = document.querySelector(`#layer${presentLayerIndex} video`);
            if (presentLayerMedia) {
                presentLayerMedia.pause();
            }
        }else if(direction == 'forward'){
            let presentLayerMedia = document.querySelector(`#layer${presentLayerIndex} video`);
            if (presentLayerMedia) {
                presentLayerMedia.pause();
            }
        }else{
            let presentLayerMedia = document.querySelector(`#layer${presentLayerIndex+2} video`);
            if (presentLayerMedia) {
                presentLayerMedia.pause();
            }
        }
    }


    createExistedStory(){
        this.totalLayers = Object.keys(this.layers).length;
        for (let  i= 0; i < this.totalLayers; i++) {
           if (i == 0) {
             if (this.metaData.title == '') {
               var text = "Edit title for this webstory";
             }else{
               var text = this.metaData.title;
             }
           }else{
             if (this.layers['L'+ i].title.text == '') {
                var text = "Edit title text";
             }else{
                 var text = this.layers['L'+ i].title.text;
             }

             if (this.layers['L'+ i].otherText.text == '') {
               var othertext ="Edit description text";
             }else{
               var othertext =this.layers['L'+ i].otherText.text;
             }
           }
            this.presentLayerIndex = 0;
            this.presentLayer  = this.presentLayerIndex + 1 ;
            var newLayer = document.createElement('div');
            newLayer.id = `layer${i}`;
            newLayer.className = 'layers';
            this.editorId.appendChild(newLayer);
            this.presentLayerDiv = document.getElementById('layer0');
            document.getElementById(`layer${i}`).style.display = 'none';

            var layersTop = document.createElement('div');
            layersTop.classList.add('layersTop');
            if (i != 0) {
              var newBar = document.createElement('span');
              newBar.id = `nav${i+1}`;
              newBar.classList.add('nav');
              newBar.classList.add('active');
              this.topBars.appendChild(newBar);
            }

            if (this.topBars.querySelector(`#nav${i+1}`).classList.contains('active')) {
              this.topBars.querySelector(`#nav${i+1}`).classList.remove('active');
            }
              this.topBars.querySelector(`#nav1`).classList.add('active');

            if (i == 0) {
              layersTop.classList.add(`${this.layers['L'+ i].theme}Front`);
            }else{
              layersTop.classList.add(`${this.layers['L'+ i].theme}Other`);
            }

            if (this.layers['L'+ i].media.credit == "none" || this.layers['L'+ i].media.credit == '') {
              var pl = 'Media Credit';
            }else{
              var pl = this.layers['L'+ i].media.credit;
            }
            if ( i == 0) {
                layersTop.innerHTML = `
                <div class="title">
                  <div class="box">
                    <span class="date">${this.metaData.date}</span>
                    <span class="titleText" contenteditable="true" onkeyup="edits.editStoryTitle()">${text}</span>
                    <div class="creditBox">
                      <span class="imageCredit"  onkeyup="mediaCredit()">${pl}</span>
                    </div>
                  </div>
                </div>`;
            }else{
                layersTop.innerHTML = `
                <div class="title">
                <span class="titleText" contenteditable="true" onkeyup="edits.editTitle()">${text}</span>
                <span class="otherText" contenteditable="true" onkeyup="edits.editText()">${othertext}</span>
                <div class="creditBox">
                  <span class="imageCredit"  onkeyup="mediaCredit()">${pl}</span>
                </div>
                </div>

                `;
            }
            if (this.layers['L'+ i].media.url == undefined || this.layers['L'+ i].media.url  == "default" || this.layers['L'+ i].media.url  == "") {

              var imageElement = document.createElement('img');
              imageElement.classList.add('mediaContent');
              imageElement.src = "/assets/img/default.jpeg";
              newLayer.appendChild(imageElement);

            }else if (this.layers['L'+ i].media.type == 'image') {
                if (document.getElementById(`videoControls${i+1}`)) {
                    document.getElementById(`videoControls${i+1}`).remove();
                }
                edits.updateMedia('image');
                var layer = document.getElementById(`layer${i}`);
                var imageElement = document.createElement('img');
                imageElement.classList.add('mediaContent');
                imageElement.src = this.layers['L'+ i].media.url;
                layer.appendChild(imageElement);

            }else if(this.layers['L'+ i].media.type  == 'video'){
                edits.updateMedia('video');
                var layerId =  editor.presentLayerIndex;
                var layer = document.getElementById(`layer${i}`);
                var videoElement = document.createElement('video');
                videoElement.src = this.layers['L'+ i].media.url;
                videoElement.type = 'video/mp4';
                videoElement.classList.add('mediaContent');
                var contorlsElements = document.createElement('div');
                contorlsElements.className = 'videoControls';
                contorlsElements.innerHTML = `
                <i class="fa-regular fa-volume-high" id="muteUnmute" data-status="unmuted" onclick="editor.muteUnmute()"></i>
                <i class="fa fa-play" id="playPauseMedia" data-status="paused" onclick="editor.playPauseMedia()"></i>
                `;
                layer.appendChild(contorlsElements);
                layer.appendChild(videoElement);
            }

            newLayer.appendChild(layersTop);
            this.presentLayerDiv.style.display = 'flex';
            // this.playPauseLastMedia('add');
            this.layersAhead = this.totalLayers - this.presentLayer;
            this.layersBack = this.totalLayers - this.layersAhead-1;
            document.getElementById('layerNumber').innerHTML = `Layer ${this.presentLayer}`;
        }
        this.updateStylesheet();
        edits.updateMetaData('update');
    }

    updateStylesheet(){
        var styleBox = document.getElementById('objectOptions');
        for (let j = 0; j < Object.keys(this.layers).length; j++) {
            var mfc = '', mff = '', mfn = '', mfcn = '', moc = '', moo = '', moa = '';

            var tfwb = '', tfwbr = '', tfwl = '', tfsl = '', tfsxl = '', tfsc = '', tfsm = '', tffa = '', tffc = '', tffm = '', tffs = '', tffcs = '';
            var ofwb = '', ofwbr = '', ofwl = '', ofsm = '', ofsl = '', ofsxs = '', ofss = '', ofsc = '',  offa = '', offc = '', offm = '', offs = '', offcs = '';

            // Media //
            if (Object.keys(this.layers['L'+ j].media).length  != 0) {
                if (this.layers['L'+ j].media.styles.mediaFit == 'cover') {
                    mfc = 'selected';
                } else if (this.layers['L'+ j].media.styles.mediaFit == 'fill') {
                    mff = 'selected';
                } else if (this.layers['L'+ j].media.styles.mediaFit == 'none') {
                    mfn = 'selected';
                } else if(this.layers['L'+ j].media.styles.mediaFit == 'contain') {
                    mfcn = 'selected';
                }
                moa = this.layers['L'+ j].media.styles.overlayArea;
                moc = this.layers['L'+ j].media.styles.overlayColor;
                moo = this.layers['L'+ j].media.styles.overlayOpacity;
            }




            // Media //

            // Title //
            if (this.layers['L'+ j].title.fontSize == 'larger') {
                tfsxl = 'selected';
            } else if (this.layers['L'+ j].title.fontSize == 'large') {
                tfsl = 'selected';
            } else if (this.layers['L'+ j].title.fontSize == 'medium') {
                tfsm = 'selected';
            } else {
                tfsc = this.layers['L'+ j].title.fontSize;
            }

            if (this.layers['L'+ j].title.fontFamily == 'cursive') {
                tffc = 'selected';
            } else if (this.layers['L'+ j].title.fontFamily == 'inherit') {
                tffa = 'selected';
            } else if (this.layers['L'+ j].title.fontFamily == 'monospace') {
                tffm = 'selected';
            } else if (this.layers['L'+ j].title.fontFamily == 'sans-serif') {
                tffs = 'selected';
            } else {
                tffcs = this.layers['L'+ j].title.fontFamily;
            }

            if (this.layers['L'+ j].title.fontWeight == '400') {
                tfwl = 'selected';
            } else if (this.layers['L'+ j].title.fontWeight == '600') {
                tfwb = 'selected';
            } else if (this.layers['L'+ j].title.fontWeight == '1000') {
                tfwbr = 'selected';
            }
            // Title //

            // Text //
            if (j != 0) {
              if (this.layers['L'+ j].otherText.fontSize == 'medium') {
                  ofsm = 'selected';
              } else if (this.layers['L'+ j].otherText.fontSize == 'large') {
                  ofsl = 'selected';
              } else if (this.layers['L'+ j].otherText.fontSize == 'small') {
                  ofss = 'selected';
              } else if (this.layers['L'+ j].otherText.fontSize == 'x-small') {
                  ofsxs = 'selected';
              } else {
                  ofsc = this.layers['L'+ j].otherText.fontSize;
              }



            if (this.layers['L'+ j].otherText.fontFamily == 'cursive') {
                offc = 'selected';
            } else if (this.layers['L'+ j].otherText.fontFamily == 'inherit') {
                offa = 'selected';
            } else if (this.layers['L'+ j].otherText.fontFamily == 'monospace') {
                offm = 'selected';
            } else if (this.layers['L'+ j].otherText.fontFamily == 'sans-serif') {
                offs = 'selected';
            } else {
                offcs = this.layers['L'+ j].otherText.fontFamily;
            }

            if (this.layers['L'+ j].otherText.fontWeight == '400') {
                ofwl = 'selected';
            } else if (this.layers['L'+ j].otherText.fontWeight == '600') {
                ofwb = 'selected';
            } else if (this.layers['L'+ j].otherText.fontWeight == '1000') {
                ofwbr = 'selected';
            }
            // Text //
            }

            var styleBoxn = document.createElement('div');
            styleBoxn.id = `styleBox${j}`;
            styleBoxn.classList.add('objectOptionsbody');
            if (j != 0) {
              var otherTextStyle = `
              <!-- Other Text Styles -->
                <div class="optionsDIv aboutStyles">
                    <span class="objectName" onclick="edits.expandOptions('aboutStyles')">
                        <span>Text</span>
                        <i class="upDownIcon fa fa-caret-right"></i>
                    </span>
                    <div class="options" style="display:none;">


                        <div class="div">
                            <span>Font weight</span>
                            <select onchange="edits.changeOtherFontWeight()" class="otherFontWeight value inputText">
                                <option ${ofwl} value="lighter" selected>Light</option>
                                <option ${ofwb} value="600">Bold</option>
                                <option ${ofwbr} value="1000">Bolder</option>
                            </select>
                        </div>
                        <div class="div">
                            <span>Font size</span>
                            <select  onchange="edits.changeOtherFontSize('select')"  class="otherFontSize value inputText">
                                <option  ${ofsm} value="medium">Medium</option>
                                <option  ${ofsl} value="large">Large</option>
                                <option ${ofss} value="small">Small</option>
                                <option ${ofsxs} value="x-small">X-Smaller</option>
                            </select>
                            <br/>
                            <span>Custom font size</span>
                            <input class="customOtherFontSize value inputText text" type="text"  placeholder="e.g. 1rem, 30px, x-large, .8em etc." onkeyup="edits.changeOtherFontSize('custom')" value="${ofsc}">
                        </div>

                        <div class="div">
                            <span>Font family</span>
                            <select onchange="edits.changeOtherFontFamily('select')"  class="otherFontFamily value inputText">
                                <option ${offa} value="inherit">Auto</option>
                                <option  ${offc} value="cursive">Cursive</option>
                                <option  ${offm} value="monospace">Monospace</option>
                                <option  ${offs} value="sans-serif">Sans-serif</option>
                            </select>
                            <br/>
                            <span>Custom font family</span>
                            <input type="text" class="otherCustomFontFamily value inputText text" onkeyup="edits.changeOtherFontFamily('custom')" placeholder="e.g. verdana, Sans-serif etc." value=" ${offcs}">
                        </div>
                    </div>
                </div>
                <!-- Other Text Styles -->
                `;
                var textVisi = `
                <div class="div"  onclick="edits.containsText()">
                  <span>Text Visibility</span>
                  <i class="toggleText enabledText fa fa-solid fa-lg fa-toggle-on"></i>
                </div>`;
            }else{
              var otherTextStyle = ``;
              var textVisi = '';
            }
            styleBoxn.innerHTML = `
                    <!-- Media Options -->
                    <div class="mediaStyles optionsDIv">
                        <span class="objectName" onclick="edits.expandOptions('mediaStyles')">
                            <span>Layer Media</span>
                            <i class="upDownIcon fa fa-caret-down"></i>
                        </span>
                        <div class="options" style="display: block;">
                            <!-- <div class="div">
                                <span>Select Theme</span>
                                <select onchange="edits.editTheme()"  class="editTheme value inputText">
                                    <option value="Default">Default</option>
                                </select>
                            </div> -->

                            <div class="div">
                                <span>Media Fit</span>
                                <select onchange="edits.mediaFit()" class="mediaFit value inputText">
                                    <option ${mff} value="fill">Fill</option>
                                    <option ${mfn} value="none">None</option>
                                    <option ${mfc} value="cover">Cover</option>
                                    <option ${mfcn} value="contain">Contain</option>
                                </select>
                            </div>
                            <div class="div">
                                <span>Shade Opacity</span>
                                <input onchange="edits.overlayEdit()" class="mediaOverlayOpacity value inputText" type="range" value="${moo}">
                            </div>
                            <div class="div">
                                <span>Media Credit</span>
                                <input class="mediaCredit value inputText text" type="text" placeholder="Blank for none" onkeyup="edits.mediaCredit()" value="${this.layers['L'+ j].media.credit}">
                            </div>
                              ${textVisi}
                         </div>
                    </div>

                    <!-- Media Options -->

                    <!-- Text Styles -->
                    <div class="titleStyles optionsDIv">
                        <span class="objectName" onclick="edits.expandOptions('titleStyles')">
                            <span>Title</span>
                            <i class="upDownIcon fa fa-caret-right"></i>
                        </span>
                        <div class="options" style="display:none;">
                            <div class="div">
                                <span>Font weight</span>
                                <select onchange="edits.changeFontWeight()" class="titleFontWeight value inputText">
                                    <option  ${tfwl} value="400">Light</option>
                                    <option  ${tfwb} value="600" >Bold</option>
                                    <option  ${tfwbr} value="1000">Bolder</option>
                                </select>
                            </div>


                            <div class="div">
                                <span>Font size</span>
                                <select  onchange="edits.changeFontSize('select')" class="titleFontSize value inputText">
                                    <option  ${tfsm} value="medium">Medium</option>
                                    <option ${tfsl} value="large">Large</option>
                                    <option ${tfsxl} value="larger">X-Larger</option>
                                </select>
                                <br/>
                                <span>Custom font size</span>
                                <input class="customFontSize value inputText text" type="text"  placeholder="e.g. 1rem, 30px, x-large, .8em etc." onkeyup="edits.changeFontSize('custom')" value="${tfsc}">
                            </div>


                            <div class="div">
                                <span>Font family</span>
                                <select  onchange="edits.changeFontFamily('select')"  class="fontFamily value inputText">
                                    <option ${tffa} value="inherit">Auto</option>
                                    <option ${tffc} value="cursive">Cursive</option>
                                    <option ${tffm} value="monospace">Monospace</option>
                                    <option ${tffs} value="sans-serif">Sans-serif</option>
                                </select>
                                <br/>
                                <span>Custom font family</span>
                                <input type="text" class="customFontFamily value inputText text" onkeyup="edits.changeFontFamily('custom')" placeholder="e.g. verdana, Sans-serif etc." value="${tffcs}">
                            </div>
                        </div>
                    </div>
                    <!-- Text Styles -->
                    ${otherTextStyle}
            </div>
        `;
        styleBox.appendChild(styleBoxn);
        }

        for (var i = 0; i < this.totalLayers; i++) {
            document.getElementById(`styleBox${i}`).style.display = 'none';
        }
        document.getElementById(`styleBox${this.presentLayerIndex}`).style.display = 'flex';
        this.applyStyleSheet();

    }

    applyStyleSheet(){
        for (let j = 0; j < Object.keys(this.layers).length; j++) {
          // For front page and other
            document.querySelector(`#layer${j} .titleText`).style.fontSize = this.layers['L'+ j].title.fontSize;
            document.querySelector(`#layer${j} .titleText`).style.fontFamily = `"${this.layers['L'+ j].title.fontFamily}"`;
            document.querySelector(`#layer${j} .titleText`).style.fontWeight = this.layers['L'+ j].title.fontWeight;

            if (j != 0) {
              // Frr other pages only
              var nm1 = parseFloat(this.layers['L'+ j].otherText.fontSize);
              var nmm1 = nm1 - 6;
              var otherText = nm1;

              var nm2 = parseFloat(this.layers['L'+ j].title.fontSize);
              var nmm2 = nm2 - 6;
              var title = nm2;
              document.querySelector(`#layer${j} .otherText`).style.fontSize = otherText;
              document.querySelector(`#layer${j} .otherText`).style.fontFamily = `"${this.layers['L'+ j].otherText.fontFamily}"`;
              document.querySelector(`#layer${j} .otherText`).style.fontWeight = this.layers['L'+ j].otherText.fontWeight;

              document.querySelector(`#layer${j} .titleText`).style.fontSize = title;
              document.querySelector(`#layer${j} .titleText`).style.fontFamily = `"${this.layers['L'+ j].title.fontFamily}"`;
              document.querySelector(`#layer${j} .titleText`).style.fontWeight = this.layers['L'+ j].title.fontWeight;
            }

            if (j != 0) {
              // Text Visibility
              var tButton = document.querySelector(`#styleBox${j} .toggleText`);
              if (this.layers['L' +j].textVisibility == 'false') {
                this.layers['L' + j].textVisibility = 'false';
                if(tButton.classList.contains('fa-toggle-on')){
                  tButton.classList.remove('fa-toggle-on')
                }
                if(tButton.classList.contains('enabledText')){
                  tButton.classList.remove('enabledText')
                }
                if(!tButton.classList.contains('fa-toggle-off')){
                  tButton.classList.add('fa-toggle-off')
                }
                document.querySelector(`#layer${j} .otherText`).style.display = 'none';
                document.querySelector(`#layer${j} .titleText`).style.display = 'none';
              }else{
                this.layers['L' + j].textVisibility = 'true';
                if(tButton.classList.contains('fa-toggle-off')){
                  tButton.classList.remove('fa-toggle-off')
                }
                if(!tButton.classList.contains('enabledText')){
                  tButton.classList.add('enabledText')
                }
                if(!tButton.classList.contains('fa-toggle-on')){
                  tButton.classList.add('fa-toggle-on')
                }
              }
              // Text Visibility
            }

            if (this.layers['L'+ j].media.url !== undefined && this.layers['L'+ j].media.url  !== "default"  && this.layers['L'+ j].media.url  !== "") {
              if (document.getElementById(`#layer${j} .mediaContent`)) {
                  document.getElementById(`#layer${j} .mediaContent`).style.objectFit = `${this.layers['L'+ j].media.styles.mediaFit}`;
              }

            }



            if (Object.keys(this.layers['L'+ j].media).length  != 0) {
                var overlayOpacity = parseInt(this.layers['L'+ j].media.styles.overlayOpacity, 10);
                document.querySelector(`#layer${j} .layersTop`).style.backgroundColor =  `rgba(0,0,0,${overlayOpacity}%)`;
            }
        }
    }


    numberToVersion(number) {
      if (typeof number !== 'number' || isNaN(number) || number < 0) {
        throw new Error('Input must be a non-negative number');
      }

      const major = Math.floor(number / 100);
      const minor = Math.floor((number % 100) / 10);
      const patch = number % 10;

      return `${major}.${minor}.${patch}`;
    }


    // Publishing
  async publishStory(){
    await this.saveStory();
      var saving = document.getElementById('publishStory');
      saving.innerHTML = "<div class='spinner' style='margin:0px 10px' ></div>";
      var alertCont = document.querySelector('.altertContainer');
      alertCont.style.display = 'flex';
      alertCont.id = 'errorConatiner';
      document.querySelector('.altertDiv').innerHTML =
      `<div class="progress">
        <div class="cancelBar">
        </div>
        <div class="progressBar">
            <div class="progressIcon">
            <div class="spinner" style="width: 75px;height: 75px;border-width:8px;margin:0;">
            </div>
        </div>
        <div class="progressWritten">Checking....</div>
        <div class="progressExtra">

        </div>
      </div>`;
       document.querySelector('.altertDiv').style.background = 'transparent';
       var screenWidth = window.innerWidth;
       if (screenWidth < 800) {
         hideSection('rightSection');
       }
      const x = await this.checkLayers()
      if (!x.length) {
        const metaData = this.checkMetaData();
        if (metaData == '') {
          var alertCont = document.querySelector('.altertContainer');
          alertCont.style.display = 'none';
          var metaErrorBox = document.getElementById('metaErrorBox');
          metaErrorBox.style.display = 'none';
          var inputs = document.querySelectorAll(".inputText");
          inputs.forEach(function(element) {
            element.style.borderColor = 'grey';
          });

          if (this.storyStatus == 'published') {
              await this.updateStory();
          }else{
              await this.publish();
          }

        }else{
          var alertCont = document.querySelector('.altertContainer');
          alertCont.style.display = 'flex';
          alertCont.id = 'errorConatiner';
          document.querySelector('.altertDiv').innerHTML =
          document.querySelector('.altertDiv').innerHTML =
          `<div class="progress">
            <div class="cancelBar">
             <div class="closeButton" onclick="cancelError()"> <i class="fa-solid fa-x"></i> </div>
            </div>
            <div class="progressBar">
                <div class="progressIcon">
                <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <div class="progressExtra">
            <div class="alert alert-warning" role="alert">
               ${metaData}
            </div>
            </div>
          </div>`;
            document.querySelector('.altertDiv').style.background = 'transparent';
            saving.innerHTML = "Publish";
        }
      }else{
        var alertCont = document.querySelector('.altertContainer');
        alertCont.style.display = 'flex';
        alertCont.id = 'errorConatiner';
        document.querySelector('.altertDiv').innerHTML =
        document.querySelector('.altertDiv').innerHTML =
        `<div class="progress">
          <div class="cancelBar">
           <div class="closeButton" onclick="cancelError()"> <i class="fa-solid fa-x"></i> </div>
          </div>
          <div class="progressBar">
              <div class="progressIcon">
              <i class="fa-solid fa-triangle-exclamation"></i>
              </div>
          </div>
          <div class="progressExtra">
          <div class="alert alert-warning" role="alert">
             ${x[0]}
          </div>
          </div>
        </div>`;
          document.querySelector('.altertDiv').style.background = 'transparent';
          saving.innerHTML = "Publish";
      }
    }

  async publish(){
    let self = this;
    var alertCont = document.querySelector('.altertContainer');
    alertCont.style.display = 'flex';
    alertCont.id = 'errorConatiner';
    document.querySelector('.altertDiv').innerHTML =
    `<div class="progress">
      <div class="cancelBar">
      </div>
      <div class="progressBar">
          <div class="progressIcon">
          <div class="spinner" style="width: 75px;height: 75px;border-width:8px;margin:0;">
          </div>
      </div>
      <div class="progressWritten">Verifying....</div>
      <div class="progressExtra">

      </div>
    </div>`;
     document.querySelector('.altertDiv').style.background = 'transparent';
     var screenWidth = window.innerWidth;
     if (screenWidth < 800) {
       hideSection('rightSection');
     }
    var jsObject = {
        layers:editor.layers,
        metaData:editor.metaData,
        version: editor.version
    };
    edits.saveToBrowser();
    var metadata = editor.metaData;
    var jsonData = JSON.stringify(jsObject);
    metadata = JSON.stringify(metadata);
   const url = '/.ht/API/webstories.php';
   var encyDat = {
   'purpose' : 'publish',
   'whois': `${this.whoIs}`,
   'storyID': `${this.storyID}`,
   'data': `${jsonData}`,
   'metaData': `${metadata}`,
   'username': `${this.username}`
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
           var alertCont = document.querySelector('.altertContainer');
           alertCont.style.display = 'flex';
           if (self.storyStatus == 'published') {
             var smessage = 'Story Updated';
           }else{
             var smessage = 'Story Published';
           }
           alertCont.id = 'errorConatiner';
           document.querySelector('.altertDiv').innerHTML =
           `<div class="progress">
             <div class="cancelBar">
          <div class="closeButton" onclick="cancelError()"> <i class="fa-solid fa-x"></i> </div>
             </div>
             <div class="progressBar">
                 <div class="progressIcon">
                 <i class="fas fa-check-circle"></i>
                 </div>
             </div>
             <div class="progressExtra">
             <div class="alert alert-success" role="alert">
                ${smessage}
             </div>
             <div class="alert alert-warning" role="alert">
                <strong>Note</strong>: Photos and videos uploaded to this story will be visible publically.
             </div>
              <div class="link">
                <div class="viewLink" onclick="editor.viewStory('${data.message}/')">
                  <i class="fa-solid fa-paper-plane"></i>
                </div>
                <div class="copyLink"  onclick="editor.copyLink('${window.location.origin}/webstories/${data.message}/')">
                    <i class="fa-solid fa-copy"></i>
                </div>
                <div class="shareLink" onclick="editor.shareLink('${self.metaData.title}', '${self.metaData.description}','${data.message}/')">
                  <i class="fa-solid fa-share-from-square"></i>
                </div>
              </div>
             </div>
           </div>`;
         }, 1000);
       }else{
         var alertCont = document.querySelector('.altertContainer');
         alertCont.style.display = 'flex';
         alertCont.id = 'errorConatiner';

         document.querySelector('.altertDiv').innerHTML =
         `<div class="progress">
           <div class="cancelBar">
          <div class="closeButton" onclick="cancelError()"> <i class="fa-solid fa-x"></i> </div>
           </div>
           <div class="progressBar">
               <div class="progressIcon">
               <div class="spinner" style="width: 75px;height: 75px;border-width:6px;margin:0;">
               </div>
           </div>
           <div class="progressExtra">
           <div class="alert alert-warning" role="alert">
              ${data.message}
           </div>
           </div>
         </div>`;
       }
   }else{
     var alertCont = document.querySelector('.altertContainer');
     alertCont.style.display = 'flex';
     alertCont.id = 'errorConatiner';
     document.querySelector('.altertDiv').innerHTML =
     `<div class="progress">
       <div class="cancelBar">
       <div class="closeButton" onclick="cancelError()"> <i class="fa-solid fa-x"></i> </div>
       </div>
       <div class="progressBar">
           <div class="progressIcon">
           <div class="spinner" style="width: 75px;height: 75px;border-width:6px;margin:0;">
           </div>
       </div>
       <div class="progressExtra">
       <div class="alert alert-warning" role="alert">
          ${data.message}
       </div>
       </div>
     </div>`;
   }
 }
  async checkLayers() {
    const errorArray = [];
    if (this.totalLayers < 4) {
      errorArray.push("At least four layers needed");
    } else {
      for (let i = 0; i < this.totalLayers; i++) {
        const layer = this.layers['L' + i];
        if (layer.media.url === 'default' || layer.media.url === '') {
          if (i == 0) {
            errorArray.push(`Add meta image of story`);
              this.moveToLayer(0)
            break;
          }else{
            errorArray.push(`Add media in Layer ${i + 1}`);
              this.moveToLayer(i)
            break;
          }

        } else {
          const urlExists = await this.checkUrlExists(layer.media.url);
          if (!urlExists) {
            errorArray.push(`Media Link <i>"${layer.media.url}"</i> does not exist in Layer ${i + 1}`);
            this.moveToLayer(i)
            break;
          } else {
            if (this.metaData.title == '') {
              errorArray.push(`Add title of the story`);
              this.moveToLayer(0)
              break;
            }else if (i != 0 && (layer.title.text == '' || this.hasOnlySpaces(layer.title.text))) {
              if (layer.textVisibility == 'true') {
                errorArray.push(`Add title in Layer ${i + 1}`);
                this.moveToLayer(i)
                break;
              }

            }
            // else if (i != 0 && (layer.otherText.text == '' || this.hasOnlySpaces(layer.otherText.text))) {
            //   if (layer.textVisibility == 'true') {
            //     errorArray.push(`Add description text in Layer ${i + 1}`);
            //     this.moveToLayer(i)
            //     break;
            //   }
            // }
          }
        }
      }
    }
    return errorArray;
  }

  async checkUrlExists(url) {
    try {
      const response = await fetch(url, { method: 'HEAD' });

      if (response.ok) {
        return true; // URL exists (HTTP status 200 OK)
      } else {
        return false; // URL does not exist (HTTP status indicates an error)
      }
    } catch (error) {
      return false; // URL does not exist or there was an error
    }
  }
  checkMetaData(){
    let metaError = '';
    let description = this.metaData.description;
    let title = this.metaData.title;
    let keywords = this.metaData.keywords;
    let url = this.metaData.url;
    var inputs = document.querySelectorAll(".inputText");
    inputs.forEach(function(element) {
      element.style.borderColor = 'grey';
    });
    if (this.getWordCount(title) <= 6) {
      metaError = 'Atleast 7 words required in title';
      let err = document.getElementById('titleError');
      err.style.display = 'inline';
      document.getElementById('storyTitle').style.borderColor = 'red';
      openOptions('metadata');
    }else if (url.length <= 20) {
        metaError = 'URL must be atleast 20 character long';
        let err = document.getElementById('urlError');
        err.style.display = 'inline';
        openOptions('metadata');
        document.getElementById('storyUrl').style.borderColor = 'red';
    }else if (this.getWordCount(description) <= 4) {
        metaError = 'Atleast 5 words required in description';
        let err = document.getElementById('descriptionError');
        err.style.display = 'inline';
        document.getElementById('storyDescription').style.borderColor = 'red';
        openOptions('metadata');
    }else if (this.getWordCount(keywords) <= 4) {
        metaError = 'Atleast 5 words required in keywords';
        let err = document.getElementById('keywordsError');
        err.style.display = 'inline';
        document.getElementById('storyKeywords').style.borderColor = 'red';
    }
    return metaError;
  }
  // Publishing
  // saving
  async saveStory(){
    if (editor.metaData.title != '' && editor.metaData.url == '') {
      await this.createUrl();
    }
    editor.metaData.timeStamp = Date.now();
    editor.version += 1;
    editor.metaData.title = this.capitalizeEveryWord(editor.metaData.title);
    editor.metaData.description = this.capitalizeSentences(editor.metaData.description);
    document.getElementById('storyTitle').value = editor.metaData.title;
    document.getElementById('storyDescription').value = editor.metaData.description;
    var jsObject = {
        layers:editor.layers,
        metaData:editor.metaData,
        version: editor.version
    };
    var metadata = editor.metaData;
    var jsonData = JSON.stringify(jsObject);
    metadata = JSON.stringify(metadata);
    var self = this;
    var saving = document.getElementById('saveStory');
    saving.innerHTML = "<div class='spinner' style='margin:0px 10px' ></div>";
    const saveData = async () =>{
        const url = '/.ht/API/webstories.php';
        var encyDat = {
        'purpose' : 'save',
        'whois': `${self.whoIs}`,
        'storyID': `${self.storyID}`,
        'data': `${jsonData}`,
        'metaData': `${metadata}`,
        'username': `${self.username}`,
        'version': `${self.version}`
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
                    saving.innerHTML = 'Saved';
                    var dat = {
                      layers : editor.layers,
                      metaData : editor.metaData,
                      version : editor.version
                    };
                      window.localStorage.setItem(`${editor.storyID}`, JSON.stringify(dat));
                }, 500);
            }else{
                setTimeout(function(){
                    saving.innerHTML = 'Not saved';
                }, 500);
            }
        }else{
            setTimeout(function(){
                saving.innerHTML = 'Not saved';
            }, 500);
        }
    }
    saveData();
  }
  async createUrl(){
      const url = '/.ht/API/webstories.php';
      var encyDat = {
      'purpose' : 'generateUrl',
      'title' : `${editor.metaData.title}`,
      'url' :`${editor.metaData.url}`,
      'storyID' : `${editor.storyID}`
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
              var urlInput = document.getElementById("storyUrl");
              urlInput.value = data.message;
              editor.metaData.url = data.message;
          }else{
          }
      }else{
      }
  }
  // saving

  // Update
  async updateStory(){
    let self = this;
    var alertCont = document.querySelector('.altertContainer');
    alertCont.style.display = 'flex';
    alertCont.id = 'errorConatiner';
    document.querySelector('.altertDiv').innerHTML =
    `<div class="progress">
      <div class="cancelBar">
      </div>
      <div class="progressBar">
          <div class="progressIcon">
          <div class="spinner" style="width: 75px;height: 75px;border-width:8px;margin:0;">
          </div>
      </div>
      <div class="progressWritten">Updating....</div>
      <div class="progressExtra">

      </div>
    </div>`;
     document.querySelector('.altertDiv').style.background = 'transparent';
     var screenWidth = window.innerWidth;
     if (screenWidth < 800) {
       hideSection('rightSection');
     }
    var jsObject = {
        layers:editor.layers,
        metaData:editor.metaData,
        version: editor.version
    };
    var metadata = editor.metaData;
    var jsonData = JSON.stringify(jsObject);
    metadata = JSON.stringify(metadata);
   const url = '/.ht/API/webstories.php';
   var encyDat = {
   'purpose' : 'update',
   'whois': `${this.whoIs}`,
   'storyID': `${this.storyID}`,
   'data': `${jsonData}`,
   'metaData': `${metadata}`,
   'username': `${this.username}`,
   'version': `${editor.version}`,
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
           var alertCont = document.querySelector('.altertContainer');
           alertCont.style.display = 'flex';
           alertCont.id = 'errorConatiner';
           document.querySelector('.altertDiv').innerHTML =
           `<div class="progress">
             <div class="cancelBar">
          <div class="closeButton" onclick="cancelError()"> <i class="fa-solid fa-x"></i> </div>
             </div>
             <div class="progressBar">
                 <div class="progressIcon">
                 <i class="fas fa-check-circle"></i>
                 </div>
             </div>
             <div class="progressExtra">
             <div class="alert alert-success" role="alert">
              Story Updated
             </div>
             <div class="alert alert-warning" role="alert">
                <strong>Note</strong>: Photos and videos uploaded to this story will be visible publically.
             </div>
              <div class="link">
                <div class="viewLink" onclick="editor.viewStory('${data.message}/')">
                  <i class="fa-solid fa-paper-plane"></i>
                </div>
                <div class="copyLink"  onclick="editor.copyLink('${window.location.origin}/webstories/${data.message}/')">
                    <i class="fa-solid fa-copy"></i>
                </div>
                <div class="shareLink" onclick="editor.shareLink('${self.metaData.title}', '${self.metaData.description}','${data.message}/')">
                  <i class="fa-solid fa-share-from-square"></i>
                </div>
              </div>
             </div>
           </div>`;
         }, 1000);
       }else{
         var alertCont = document.querySelector('.altertContainer');
         alertCont.style.display = 'flex';
         alertCont.id = 'errorConatiner';

         document.querySelector('.altertDiv').innerHTML =
         `<div class="progress">
           <div class="cancelBar">
          <div class="closeButton" onclick="cancelError()"> <i class="fa-solid fa-x"></i> </div>
           </div>
           <div class="progressBar">
               <div class="progressIcon">
               <div class="spinner" style="width: 75px;height: 75px;border-width:6px;margin:0;">
               </div>
           </div>
           <div class="progressExtra">
           <div class="alert alert-warning" role="alert">
              ${data.message}
           </div>
           </div>
         </div>`;
       }
   }else{
     var alertCont = document.querySelector('.altertContainer');
     alertCont.style.display = 'flex';
     alertCont.id = 'errorConatiner';
     document.querySelector('.altertDiv').innerHTML =
     `<div class="progress">
       <div class="cancelBar">
       <div class="closeButton" onclick="cancelError()"> <i class="fa-solid fa-x"></i> </div>
       </div>
       <div class="progressBar">
           <div class="progressIcon">
           <div class="spinner" style="width: 75px;height: 75px;border-width:6px;margin:0;">
           </div>
       </div>
       <div class="progressExtra">
       <div class="alert alert-warning" role="alert">
          ${data.message}
       </div>
       </div>
     </div>`;
   }
 }
 // update

 async draftStory(){
   var alertCont = document.querySelector('.altertContainer');
   alertCont.style.display = 'flex';
   alertCont.id = 'errorConatiner';
   document.querySelector('.altertDiv').innerHTML =
   `<div class="progress">
     <div class="cancelBar">
     </div>
     <div class="progressBar">
         <div class="progressIcon">
         <div class="spinner" style="width: 75px;height: 75px;border-width:8px;margin:0;">
         </div>
     </div>
     <div class="progressWritten">Updating....</div>
     <div class="progressExtra">

     </div>
   </div>`;
    document.querySelector('.altertDiv').style.background = 'transparent';
    var screenWidth = window.innerWidth;
    if (screenWidth < 800) {
      hideSection('rightSection');
    }

  let self = this;
   const url = '/.ht/API/webstories.php';
   var encyDat = {
   'purpose' : 'draft',
   'whois': `${self.whoIs}`,
   'storyID': `${self.storyID}`,
   'username': `${self.username}`,
   'version': `${self.version}`
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
           var alertCont = document.querySelector('.altertContainer');
           alertCont.id = 'errorConatiner';
           document.querySelector('.altertDiv').innerHTML =
           `<div class="progress">
             <div class="cancelBar">
          <div class="closeButton" onclick="cancelError()"> <i class="fa-solid fa-x"></i> </div>
             </div>
             <div class="progressBar">
                 <div class="progressIcon">
                 <i class="fas fa-check-circle" style="color: orange;"></i>
                 </div>
             </div>
             <div class="progressExtra">
             <div class="alert alert-success" role="alert">
                Story Drafted
             </div>
             <div class="alert alert-warning" role="alert">
                <strong>Note</strong>: Photos and videos uploaded to this story will be visible publically even after being drafted
             </div>
             </div>
           </div>`;
         }, 1000);

       }else{
         var alertCont = document.querySelector('.altertContainer');
         alertCont.style.display = 'flex';
         alertCont.id = 'errorConatiner';
         document.querySelector('.altertDiv').innerHTML =
         `<div class="progress">
           <div class="cancelBar">
          <div class="closeButton" onclick="cancelError()"> <i class="fa-solid fa-x"></i> </div>
           </div>
           <div class="progressBar">
               <div class="progressIcon">
               <div class="spinner" style="width: 75px;height: 75px;border-width:6px;margin:0;">
               </div>
           </div>
           <div class="progressExtra">
           <div class="alert alert-warning" role="alert">
              ${data.message}
           </div>
           </div>
         </div>`;
       }
   }else{
     var alertCont = document.querySelector('.altertContainer');
     alertCont.style.display = 'flex';
     alertCont.id = 'errorConatiner';
     document.querySelector('.altertDiv').innerHTML =
     `<div class="progress">
       <div class="cancelBar">
       <div class="closeButton" onclick="cancelError()"> <i class="fa-solid fa-x"></i> </div>
       </div>
       <div class="progressBar">
           <div class="progressIcon">
           <div class="spinner" style="width: 75px;height: 75px;border-width:6px;margin:0;">
           </div>
       </div>
       <div class="progressExtra">
       <div class="alert alert-warning" role="alert">
          ${data.message}
       </div>
       </div>
     </div>`;
   }
 }
  getWordCount(inputString) {
      // Use a regular expression to split the string into words
      const words = inputString.split(/\s+/);
      return words.length;
  }
  moveToLayer(index){
    this.presentLayerIndex  = index;
    this.presentLayerDiv = document.getElementById(`layer${index}`);
    for (var i = 0; i < this.totalLayers; i++) {
        document.getElementById(`layer${i}`).style.display = 'none';
        document.getElementById(`styleBox${i}`).style.display = 'none';
    }
    this.presentLayerDiv.style.display = 'flex';
    document.getElementById(`styleBox${this.presentLayerIndex}`).style.display = 'flex';
    this.playPauseLastMedia('forward');
    this.presentLayer  = index + 1 ;
    for (var i = 1; i < this.totalLayers+1; i++) {
      this.topBars.querySelector(`#nav${i}`).classList.remove('active');
    }
    this.topBars.querySelector(`#nav${this.presentLayer}`).classList.add('active');

    document.getElementById('layerNumber').innerHTML = `Layer ${this.presentLayer}`;
    }
  shareLink(title, text, url){
    if (navigator.share) {
        navigator.share({
            title: `${title}`,
            text:  `${text}`,
            url: `/webstories/${url}`
        })
        .then(() => console.log('Shared successfully'))
        .catch((error) => console.error('Sharing failed:', error));
    } else {
        // Fallback for browsers that don't support the Web Share API
        alert('Web Share API is not supported in this browser.');
    }
   }
  copyLink(link){
    const input = document.createElement('input');
     input.value = link;
     document.body.appendChild(input);
     input.select();
     document.execCommand('copy');
     document.body.removeChild(input);
     alert('Link copied')
   }
  viewStory(link){
      window.open(`/webstories/${link}`, '_blank');
  }
  capitalizeEveryWord(inputString) {
      return inputString.replace(/\b\w/g, function (match) {
          return match.toUpperCase();
      });
    }
  capitalizeSentences(inputString) {
        return inputString.replace(/\.(\s|$)|^.|\.\w/g, function (match) {
            return match.toUpperCase();
        });
    }
  capitalizeSentences(paragraph) {
    let sentences = paragraph.split('. ');
    for (let i = 0; i < sentences.length; i++) {
      sentences[i] = sentences[i].charAt(0).toUpperCase() + sentences[i].slice(1);
    }
    return sentences.join('. ');
  }
  hasOnlySpaces(inputString) {
    return /^\s*$/.test(inputString);
  }

}
let editor = new Editor();
