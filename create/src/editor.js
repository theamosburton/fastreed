class Editor{
    constructor(){
        var params = new URLSearchParams(window.location.search);
        if (params.get('username')) {
            this.whoIs = whoIs;
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
            'storyID': `${this.storyID}`
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
                    this.layers = [];
                    this.metaData = {};
                    this.metaData.title = "";
                    this.metaData.description = "";
                    this.metaData.keywords = "";
                    this.metaData.url = "";
                    this.webstoryData = data.message;
                    if (this.webstoryData == '{}') {
                        this.presentLayerIndex = 0;
                        this.presentLayer  = this.presentLayerIndex + 1 ;
                        this.totalLayers = 1;
                        
                        this.layers[0] = {
                            'media': {
                                "blobUrl":'',
                                "styles":{
                                    "overlayColor": "#000000",
                                    "overlayOpacity": "1",
                                    "mediaFit":"cover"
                                },
                                "type":'',
                                "url":''
                            },
                            'title':{
                                "text":'',
                                "fontFamily":"inherit",
                                "fontWeight":"1000",
                                "fontSize":"larger"
                            },
                            'otherText': {
                                "text":'',
                                "fontFamily":"inherit",
                                "fontWeight":"400",
                                "fontSize":"medium"
                            }
                        };

                        if (this.editorId.children.length <= 0) {
                            var overlay = document.createElement('div');
                            overlay.classList.add('overlay');
                            overlay.id = `overlay${this.presentLayerIndex}`;

                            var layersTop = document.createElement('div');
                            layersTop.classList.add('layersTop');
                            layersTop.innerHTML = `
                            <div class="title" id="title${this.presentLayerIndex}">
                            <span class="titleText" id="titleText${this.presentLayerIndex}" contenteditable="true" onkeyup="edits.editTitle('titleText${this.presentLayerIndex}', '')">Enter Title/heading</span>
                            </div>
                            <div class="text" id="text${this.presentLayerIndex}">
                            <span class="titleText" contenteditable="true" id="otherText${this.presentLayerIndex}" contenteditable="true" onkeyup="edits.editText('otherText${this.presentLayerIndex}', '')">Enter more text..</span>
                            </div>`;
                            var newLayer = document.createElement('div');
                            newLayer.id = `layer${this.presentLayerIndex}`;
                            newLayer.className = 'layers';
                            newLayer.innerHTML = `<div class="placeholder" id="placeholder${this.presentLayerIndex}">
                                <p> Add</p>
                                <p> Photo/Video</p>
                                <small> Recomended ratios are </small>
                                <small> 9:16, 3:4 and 2:3 </small>
                            </div>`;
                            this.editorId.appendChild(newLayer);
                            newLayer.appendChild(overlay);
                            newLayer.appendChild(layersTop);
                        }
                    
                        this.presentLayerDiv = document.getElementById(`layer${this.presentLayerIndex}`);
                        var otherLayers = document.querySelector("#editTab .layers");
                        for (var i = 0; i < otherLayers.length; i++) {
                            otherLayers[i].style.display = "none";
                        }
                        
                        this.layersAhead = this.totalLayers - this.presentLayer;
                        if (this.layersAhead) {
                            this.layersBack = this.totalLayers-this.layersAhead-1;
                        }else{
                            this.layersBack = 0;
                        }
                        document.getElementById('forwardIcon').innerHTML = `${this.layersAhead}&nbsp;`;
                        document.getElementById('backwardIcon').innerHTML = `&nbsp;${this.layersBack}`;
                        this.presentLayerDiv.style.display = 'flex';
                        document.getElementById('layerNumber').innerHTML = `Layer ${this.presentLayer}`;
                        this.createStylesheet();
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
        this.layers = jsObject.layers;
        this.metaData = jsObject.metaData;
        this.updateStory();
    }

    createNewLayer(){  
        this.inBetweenLayersAdd();
        this.totalLayers += 1;
        this.presentLayerIndex += 1;
        this.presentLayer  = this.presentLayerIndex + 1 ;
        this.layers[this.presentLayerIndex] = {
            'media': {
                "blobUrl":'',
                "styles":{
                    "overlayColor": "#000000",
                    "overlayOpacity": "1",
                    "mediaFit":"cover"
                },
                "type":'',
                "url":''
            },
            'title':{
                "text":'',
                "fontFamily":"inherit",
                "fontWeight":"1000",
                "fontSize":"larger"
            },
            'otherText': {
                "text":'',
                "fontFamily":"inherit",
                "fontWeight":"100",
                "fontSize":"medium"
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

        var overlay = document.createElement('div');
        overlay.classList.add('overlay');
        overlay.id = `overlay${this.presentLayerIndex}`;

        var layersTop = document.createElement('div');
        layersTop.classList.add('layersTop');
        layersTop.innerHTML = `
        <div class="title" id="title${this.presentLayerIndex}">
        <span class="titleText" id="titleText${this.presentLayerIndex}" contenteditable="true" onkeyup="edits.editTitle('titleText${this.presentLayerIndex}')">Enter Title/heading</span>
        </div>
        <div class="text" id="text${this.presentLayerIndex}">
        <span class="titleText" contenteditable="true" id="otherText${this.presentLayerIndex}" contenteditable="true" onkeyup="edits.editText('otherText${this.presentLayerIndex}')">Enter more text..</span>
        </div>`;

        newLayer.innerHTML = `<div class="placeholder" id="placeholder${this.presentLayerIndex}">
            <p> Add</p>
            <p> Photo/Video</p>
            <small> Recomended ratios are </small>
            <small> 9:16, 3:4 and 2:3 </small>
        </div>
        `;
        newLayer.appendChild(overlay);
        newLayer.appendChild(layersTop);
        this.presentLayerDiv.style.display = 'flex';
        this.playPauseLastMedia('add');
        this.layersAhead = this.totalLayers - this.presentLayer;
        this.layersBack = this.totalLayers-this.layersAhead-1;
        document.getElementById('forwardIcon').innerHTML = `${this.layersAhead}&nbsp;`;
        document.getElementById('backwardIcon').innerHTML = `&nbsp;${this.layersBack}`;
        document.getElementById('layerNumber').innerHTML = `Layer ${this.presentLayer}`;
        this.createStylesheet();
    }

    createStylesheet(){
        var styleBox = document.getElementById('objectOptions');
        var styleBoxn = document.createElement('div');
        styleBoxn.id = `styleBox${this.presentLayerIndex}`;
        styleBoxn.classList.add('objectOptionsbody');
        styleBoxn.innerHTML = `
                    <!-- Media Options -->
                    <div class="optionsDIv" id="mediaStyles${this.presentLayerIndex}">
                        <span class="objectName" onclick="edits.expandOptions('mediaStyles${this.presentLayerIndex}','')">
                            <span>Layer Media</span>  
                            <i class="fa fa-caret-right"></i>
                        </span>
                        <div class="options" style="display: none;">
                            <div class="div">
                                <span>Media Fit</span>
                                <select onchange="edits.mediaFit()" id="mediaFit${this.presentLayerIndex}" class="value inputText">
                                    <option value="fill">Fill</option>
                                    <option value="none">None</option>
                                    <option value="cover" selected>Cover</option>
                                    <option value="contain">Contain</option>
                                </select>
                            </div>
                            <div class="div">
                                <span>Overlay Colour</span>
                                <input onchange="edits.mediaOverlayColor()" class="value inputText" type="color" id="mediaOverlayColor${this.presentLayerIndex}" name="favcolor" value="#000000">
                            </div>

                            <div class="div">
                                <span>Overlay Opacity</span>
                                <input onchange="edits.overlayOpacity()" class="value inputText" type="range" id="mediaOverlayOpacity${this.presentLayerIndex}" value="1">
                            </div>
                        </div>
                    </div>
                    <!-- Media Options -->
                    
                    <!-- Text Styles -->
                    <div class="optionsDIv" id="titleStyles${this.presentLayerIndex}">
                        <span class="objectName" onclick="edits.expandOptions('titleStyles${this.presentLayerIndex}')"> 
                            <span>Title</span>  
                            <i class="fa fa-caret-right"></i>
                        </span>
                        <div class="options" style="display:none;">
                            <div class="div">
                                <span>Font weight</span>
                                <select id="titleFontWeight${this.presentLayerIndex}" onchange="edits.changeFontWeight()" class="value inputText">
                                    <option value="400">Light</option>
                                    <option value="700" >Bold</option>
                                    <option value="1000" selected>Bolder</option>
                                </select>
                            </div>


                            <div class="div">
                                <span>Font size</span>
                                <select name="" id="titleFontSize${this.presentLayerIndex}" onchange="edits.changeFontSize('select')" class="value inputText">
                                    <option value="medium">Medium</option>
                                    <option value="large">Large</option>
                                    <option value="larger" selected>X-Larger</option>
                                </select>
                                <br/>
                                <span>Custom font size</span>
                                <input class="value inputText text" type="text" id="customFontSize${this.presentLayerIndex}" placeholder="e.g. 1rem, 30px, x-large, .8em etc." onkeyup="edits.changeFontSize('custom')">
                            </div>


                            <div class="div">
                                <span>Font family</span>
                                <select id="fontFamily${this.presentLayerIndex}" onchange="edits.changeFontFamily('select')"  class="value inputText">
                                    <option value="inherit">Auto</option>
                                    <option value="cursive">Cursive</option>
                                    <option value="monospace">Monospace</option>
                                    <option value="sans-serif">Sans-serif</option>
                                </select>
                                <br/>
                                <span>Custom font family</span>
                                <input type="text" class="value inputText text" onkeyup="edits.changeFontFamily('custom')" id="customFontFamily${this.presentLayerIndex}" placeholder="e.g. verdana, Sans-serif etc.">
                            </div>
                        </div>
                    </div>
                    <!-- Text Styles -->



                    <!-- Other Text Styles -->
                    <div class="optionsDIv" id="aboutStyles${this.presentLayerIndex}">
                        <span class="objectName" onclick="edits.expandOptions('aboutStyles${this.presentLayerIndex}')">
                            <span>Text</span>  
                            <i class="fa fa-caret-right"></i>
                        </span>
                        <div class="options" style="display:none;">


                            <div class="div">
                                <span>Font weight</span>
                                <select onchange="edits.changeOtherFontWeight()" id="otherFontWeight${this.presentLayerIndex}" class="value inputText">
                                    <option value="lighter" selected>Light</option>
                                    <option value="600">Bold</option>
                                    <option value="1000">Bolder</option>
                                </select>
                            </div>
                            <div class="div">
                                <span>Font size</span>
                                <select id="otherFontSize${this.presentLayerIndex}" onchange="edits.changeOtherFontSize('select')"  class="value inputText">
                                    <option value="medium">Medium</option>
                                    <option value="large">Large</option>
                                    <option value="small">Small</option>
                                    <option value="x-small">X-Smaller</option>
                                </select>
                                <br/>
                                <span>Custom font size</span>
                                <input class="value inputText text" type="text" id="customOtherFontSize${this.presentLayerIndex}" placeholder="e.g. 1rem, 30px, x-large, .8em etc." onkeyup="edits.changeOtherFontSize('custom')">
                            </div>

                            <div class="div">
                                <span>Font family</span>
                                <select id="otherFontFamily${this.presentLayerIndex}" onchange="edits.changeOtherFontFamily('select')"  class="value inputText">
                                    <option value="inherit">Auto</option>
                                    <option value="cursive">Cursive</option>
                                    <option value="monospace">Monospace</option>
                                    <option value="sans-serif">Sans-serif</option>
                                </select>
                                <br/>
                                <span>Custom font family</span>
                                <input type="text" class="value inputText text" onkeyup="edits.changeOtherFontFamily('custom')" id="otherCustomFontFamily${this.presentLayerIndex}" placeholder="e.g. verdana, Sans-serif etc.">
                            </div>
                        </div>
                    </div>
                    <!-- Other Text Styles -->
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
            }

        }
    }

    deleteLayer(){
        if (this.totalLayers > 1 && this.presentLayerIndex != 0) {
            this.inBetweenLayersDel();
            this.layers.splice(this.presentLayerIndex, 1);
            this.totalLayers -= 1;
            this.presentLayerDiv = document.getElementById(`styleBox${this.presentLayerIndex}`);
            this.presentLayerDiv.remove();
            document.getElementById(`layer${this.presentLayerIndex}`).remove();
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
        this.layersAhead = this.totalLayers - this.presentLayer;
        this.layersBack = this.totalLayers-this.layersAhead-1;
        document.getElementById('forwardIcon').innerHTML = `${this.layersAhead}&nbsp;`;
        document.getElementById('backwardIcon').innerHTML = `&nbsp;${this.layersBack}`;
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
        this.layersAhead = this.totalLayers - this.presentLayer;
        this.layersBack = this.totalLayers-this.layersAhead-1;
        document.getElementById('forwardIcon').innerHTML = `${this.layersAhead}&nbsp;`;
        document.getElementById('backwardIcon').innerHTML = `&nbsp;${this.layersBack}`;
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

    saveStory(){
        var jsObject = {
            "layers":editor.layers,
            "metaData":editor.metaData
        };
        console.log(jsObject);
        var jsonData = JSON.stringify(jsObject);
        
        var self = this;
        var saving = document.getElementById('saveStory');
        saving.innerHTML = "<div class='spinner' style='margin:0px 10px' ></div>";
        const saveData = async () =>{
            const url = '/.ht/API/webstories.php';
            var encyDat = {
            'purpose' : 'update',
            'whois': `${self.whoIs}`,
            'storyID': `${self.storyID}`,
            'data': `${jsonData}`
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



    updateStory(){
        edits.updateMetaData('update');
        this.totalLayers = this.layers.length;
       
        for (let  i= 0; i < this.totalLayers; i++) {
            if (this.layers[i].title.text == '') {
                var text = "Enter Title/heading";
            }else{
                var text = this.layers[i].title.text;
            }

            if (this.layers[i].otherText.text == '') {
                var othertext = "Enter more text..";
            }else{
                var othertext = this.layers[i].otherText.text;
            }

            

            this.presentLayerIndex = 0;
            this.presentLayer  = this.presentLayerIndex + 1 ;
            var newLayer = document.createElement('div');
            newLayer.id = `layer${i}`;
            newLayer.className = 'layers';
            
            this.editorId.appendChild(newLayer);
            this.presentLayerDiv = document.getElementById('layer0');
            document.getElementById(`layer${i}`).style.display = 'none';
            var overlay = document.createElement('div');
            overlay.classList.add('overlay');
            overlay.id = `overlay${i}`;
    
            var layersTop = document.createElement('div');
            layersTop.classList.add('layersTop');
            layersTop.innerHTML = `
            <div class="title" id="title${this.presentLayerIndex}" >
            <span class="titleText" id="titleText${i}" contenteditable="true" onkeyup="edits.editTitle('titleText${i}')">${text}</span>
            </div>
            <div class="text" id="text${i}">
            <span class="titleText" contenteditable="true" id="otherText${i}" contenteditable="true" onkeyup="edits.editText('otherText${i}')">${othertext}</span>
            </div>`;
            if (this.layers[i].media == '{}') {
                newLayer.innerHTML = `
                <div class="placeholder" id="placeholder${i}">
                    <p> Add</p>
                    <p> Photo/Video</p>
                    <small> Recomended ratios are </small>
                    <small> 9:16, 3:4 and 2:3 </small>
                </div>
            `;
            }else{
                if (this.layers[i].media.type == 'image') {
                    var layerId =  i;
                    if (document.getElementById(`videoControls${layerId+1}`)) {
                      document.getElementById(`videoControls${layerId+1}`).remove();
                    }
                    edits.updateMedia('image');
                    
                    var layer = document.getElementById(`layer${layerId}`);
                    var imageElement = document.createElement('img');
                    imageElement.id = `mediaContent${i}`;
                    imageElement.src = this.layers[i].media.url;
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
                  }else if(this.layers[i].media.type  == 'video'){
                    edits.updateMedia('video');
                    var layerId =  editor.presentLayerIndex;
                    var layer = document.getElementById(`layer${layerId}`);
              
                    var videoElement = document.createElement('video');
                    videoElement.src = this.layers[i].media.url;
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
            
            newLayer.appendChild(overlay);
            newLayer.appendChild(layersTop);
            this.presentLayerDiv.style.display = 'flex';
            this.playPauseLastMedia('add');
            this.layersAhead = this.totalLayers - this.presentLayer;
            this.layersBack = this.totalLayers - this.layersAhead-1;
            document.getElementById('forwardIcon').innerHTML = `${this.layersAhead}&nbsp;`;
            document.getElementById('backwardIcon').innerHTML = `&nbsp;${this.layersBack}`;
            document.getElementById('layerNumber').innerHTML = `Layer ${this.presentLayer}`;
        }
       
        this.updateStylesheet();
    }


    updateStylesheet(){
        var styleBox = document.getElementById('objectOptions');
        for (let j = 0; j < this.layers.length; j++) {
            var mfc = '', mff = '', mfn = '', mfcn = '', moc = '', moo = '';

            var tfwb = '', tfwbr = '', tfwl = '', tfsl = '', tfsxl = '', tfsc = '', tfsm = '', tffa = '', tffc = '', tffm = '', tffs = '', tffcs = '';
            var ofwb = '', ofwbr = '', ofwl = '', ofsm = '', ofsl = '', ofsxs = '', ofss = '', ofsc = '',  offa = '', offc = '', offm = '', offs = '', offcs = '';
            
            // Media //
            if (this.layers[j].media.mediaFit == 'cover') {
                mfc = 'selected';
            } else if (this.layers[j].media.mediaFit == 'fill') {
                mff = 'selected';
            } else if (this.layers[j].media.mediaFit == 'none') {
                mfn = 'selected';
            } else if(this.layers[j].media.mediaFit == 'contain') {
                mfcn = 'selected';
            }
            moc = this.layers[j].media.styles.overlayColor;
            moo = this.layers[j].media.styles.overlayOpacity;
            // Media //
            
            // Title //
            if (this.layers[j].title.fontSize == 'larger') {
                tfsxl = 'selected';
            } else if (this.layers[j].title.fontSize == 'large') {
                tfsl = 'selected';
            } else if (this.layers[j].title.fontSize == 'medium') {
                tfsm = 'selected';
            } else {
                tfsc = this.layers[j].title.fontSize;
            }

            if (this.layers[j].title.fontFamily == 'cursive') {
                tffc = 'selected';
            } else if (this.layers[j].title.fontFamily == 'inherit') {
                tffa = 'selected';
            } else if (this.layers[j].title.fontFamily == 'monospace') {
                tffm = 'selected';
            } else if (this.layers[j].title.fontFamily == 'sans-serif') {
                tffs = 'selected';
            } else {
                tffcs = this.layers[j].title.fontFamily;
            }

            if (this.layers[j].title.fontWeight == '400') {
                tfwl = 'selected';
            } else if (this.layers[j].title.fontWeight == '600') {
                tfwb = 'selected';
            } else if (this.layers[j].title.fontWeight == '1000') {
                tfwbr = 'selected';
            }
            // Title //

            // Text //
            if (this.layers[j].otherText.fontSize == 'medium') {
                ofsm = 'selected';
            } else if (this.layers[j].otherText.fontSize == 'large') {
                ofsl = 'selected';
            } else if (this.layers[j].otherText.fontSize == 'small') {
                ofss = 'selected';
            } else if (this.layers[j].otherText.fontSize == 'x-small') {
                ofsxs = 'selected';
            } else {
                ofsc = this.layers[j].otherText.fontSize;
            }

            if (this.layers[j].otherText.fontFamily == 'cursive') {
                offc = 'selected';
            } else if (this.layers[j].otherText.fontFamily == 'inherit') {
                offa = 'selected';
            } else if (this.layers[j].otherText.fontFamily == 'monospace') {
                offm = 'selected';
            } else if (this.layers[j].otherText.fontFamily == 'sans-serif') {
                offs = 'selected';
            } else {
                offcs = this.layers[j].otherText.fontFamily;
            }

            if (this.layers[j].otherText.fontWeight == '400') {
                ofwl = 'selected';
            } else if (this.layers[j].otherText.fontWeight == '600') {
                ofwb = 'selected';
            } else if (this.layers[j].otherText.fontWeight == '1000') {
                ofwbr = 'selected';
            }
            // Text //

            var styleBoxn = document.createElement('div');
            styleBoxn.id = `styleBox${j}`;
            styleBoxn.classList.add('objectOptionsbody');
            styleBoxn.innerHTML = `
                    <!-- Media Options -->
                    <div class="optionsDIv" id="mediaStyles${j}">
                        <span class="objectName" onclick="edits.expandOptions('mediaStyles${j}','')">
                            <span>Layer Media</span>  
                            <i class="fa fa-caret-right"></i>
                        </span>
                        <div class="options" style="display: none;">
                            <div class="div">
                                <span>Media Fit</span>
                                <select onchange="edits.mediaFit()" id="mediaFit${j}" class="value inputText">
                                    <option ${mff} value="fill">Fill</option>
                                    <option ${mfn} value="none">None</option>
                                    <option ${mfc} value="cover" selected>Cover</option>
                                    <option ${mfcn} value="contain">Contain</option>
                                </select>
                            </div>
                            <div class="div">
                                <span>Overlay Colour</span>
                                <input onchange="edits.mediaOverlayColor()" class="value inputText" type="color" id="mediaOverlayColor${j}" name="favcolor" value="${moc}">
                            </div>

                            <div class="div">
                                <span>Overlay Opacity</span>
                                <input onchange="edits.overlayOpacity()" class="value inputText" type="range" id="mediaOverlayOpacity${j}" value="${moo}">
                            </div>
                        </div>
                    </div>
                    <!-- Media Options -->
                    
                    <!-- Text Styles -->
                    <div class="optionsDIv" id="titleStyles${j}">
                        <span class="objectName" onclick="edits.expandOptions('titleStyles${j}')"> 
                            <span>Title</span>  
                            <i class="fa fa-caret-right"></i>
                        </span>
                        <div class="options" style="display:none;">
                            <div class="div">
                                <span>Font weight</span>
                                <select id="titleFontWeight${j}" onchange="edits.changeFontWeight()" class="value inputText">
                                    <option  ${tfwl} value="400">Light</option>
                                    <option  ${tfwb} value="600" >Bold</option>
                                    <option  ${tfwbr} value="1000">Bolder</option>
                                </select>
                            </div>


                            <div class="div">
                                <span>Font size</span>
                                <select name="" id="titleFontSize${j}" onchange="edits.changeFontSize('select')" class="value inputText">
                                    <option  ${tfsm} value="medium">Medium</option>
                                    <option ${tfsl} value="large">Large</option>
                                    <option ${tfsxl} value="larger">X-Larger</option>
                                </select>
                                <br/>
                                <span>Custom font size</span>
                                <input class="value inputText text" type="text" id="customFontSize${j}" placeholder="e.g. 1rem, 30px, x-large, .8em etc." onkeyup="edits.changeFontSize('custom')" value="${tfsc}">
                            </div>


                            <div class="div">
                                <span>Font family</span>
                                <select id="fontFamily${j}" onchange="edits.changeFontFamily('select')"  class="value inputText">
                                    <option ${tffa} value="inherit">Auto</option>
                                    <option ${tffc} value="cursive">Cursive</option>
                                    <option ${tffm} value="monospace">Monospace</option>
                                    <option ${tffs} value="sans-serif">Sans-serif</option>
                                </select>
                                <br/>
                                <span>Custom font family</span>
                                <input type="text" class="value inputText text" onkeyup="edits.changeFontFamily('custom')" id="customFontFamily${j}" placeholder="e.g. verdana, Sans-serif etc." value="${tffcs}">
                            </div>
                        </div>
                    </div>
                    <!-- Text Styles -->



                    <!-- Other Text Styles -->
                    <div class="optionsDIv" id="aboutStyles${j}">
                        <span class="objectName" onclick="edits.expandOptions('aboutStyles${j}')">
                            <span>Text</span>  
                            <i class="fa fa-caret-right"></i>
                        </span>
                        <div class="options" style="display:none;">


                            <div class="div">
                                <span>Font weight</span>
                                <select onchange="edits.changeOtherFontWeight()" id="otherFontWeight${j}" class="value inputText">
                                    <option ${ofwl} value="lighter" selected>Light</option>
                                    <option ${ofwb} value="600">Bold</option>
                                    <option ${ofwbr} value="1000">Bolder</option>
                                </select>
                            </div>
                            <div class="div">
                                <span>Font size</span>
                                <select id="otherFontSize${j}" onchange="edits.changeOtherFontSize('select')"  class="value inputText">
                                    <option  ${ofsm} value="medium">Medium</option>
                                    <option  ${ofsl} value="large">Large</option>
                                    <option ${ofss} value="small">Small</option>
                                    <option ${ofsxs} value="x-small">X-Smaller</option>
                                </select>
                                <br/>
                                <span>Custom font size</span>
                                <input class="value inputText text" type="text" id="customOtherFontSize${j}" placeholder="e.g. 1rem, 30px, x-large, .8em etc." onkeyup="edits.changeOtherFontSize('custom')" value="${ofsc}">
                            </div>

                            <div class="div">
                                <span>Font family</span>
                                <select id="otherFontFamily${j}" onchange="edits.changeOtherFontFamily('select')"  class="value inputText">
                                    <option ${offa} value="inherit">Auto</option>
                                    <option  ${offc} value="cursive">Cursive</option>
                                    <option  ${offm} value="monospace">Monospace</option>
                                    <option  ${offs} value="sans-serif">Sans-serif</option>
                                </select>
                                <br/>
                                <span>Custom font family</span>
                                <input type="text" class="value inputText text" onkeyup="edits.changeOtherFontFamily('custom')" id="otherCustomFontFamily${j}" placeholder="e.g. verdana, Sans-serif etc." value=" ${offcs}">
                            </div>
                        </div>
                    </div>
                    <!-- Other Text Styles -->
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
        for (let j = 0; j < this.layers.length; j++) {
            document.getElementById(`titleText${j}`).style.fontSize = this.layers[j].title.fontSize; 
            document.getElementById(`titleText${j}`).style.fontFamily = this.layers[j].title.fontFamily; 
            document.getElementById(`titleText${j}`).style.fontWeight = this.layers[j].title.fontWeight; 

            document.getElementById(`otherText${j}`).style.fontSize = this.layers[j].otherText.fontSize; 
            document.getElementById(`otherText${j}`).style.fontFamily = this.layers[j].otherText.fontFamily; 
            document.getElementById(`otherText${j}`).style.fontWeight = this.layers[j].otherText.fontWeight; 

            
            if (document.getElementById(`mediaContent${j}`)) {
                document.getElementById(`mediaContent${j}`).style.objectFit = `${this.layers[j].media.styles.mediaFit}`;
            }
            document.getElementById(`overlay${j}`).style.opacity  = `${this.layers[j].media.styles.overlayOpacity}%`;
            document.getElementById(`overlay${j}`).style.backgroundColor  = this.layers[j].media.styles.overlayColor;

        }
        
    }


}
let editor = new Editor();




    
