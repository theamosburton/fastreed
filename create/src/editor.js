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
                    this.webstoryData = data.message;
                    if (this.webstoryData != '{0}') {
                        this.presentLayerIndex = 0;
                        this.presentLayer  = this.presentLayerIndex + 1 ;
                        this.totalLayers = 1;
                        this.layers = [];
                        this.layers[0] = {
                            'media': {
                                "blobUrl":'',
                                "styles":{
                                    "overlayColor": "#000000",
                                    "overlayCapacity": "1",
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

                        if (this.editorId.children.length <= 0) {
                            var overlay = document.createElement('div');
                            overlay.classList.add('overlay');
                            overlay.id = `overlay${this.presentLayerIndex}`;

                            var layersTop = document.createElement('div');
                            layersTop.classList.add('layersTop');
                            layersTop.innerHTML = `
                            <div class="title" id="title${this.presentLayerIndex}">
                            <span class="titleText" >Enter Title/heading</span>
                            </div>
                            <div class="text" id="text${this.presentLayerIndex}">
                            <span class="titleText" >Enter more text..</span>
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
        console.log(jsObject);

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
                    "overlayCapacity": "1",
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
        <span class="titleText" >Enter Title/heading</span>
        </div>
        <div class="text" id="text${this.presentLayerIndex}">
        <span class="titleText" >Enter more text..</span>
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
                        <span class="objectName" onclick="edits.expandOptions('titleStyles${this.presentLayerIndex}', '')"> 
                            <span>Title</span>  
                            <i class="fa fa-caret-right"></i>
                        </span>
                        <div class="options" style="display:none;">
                            <div class="div">
                                <input class="value inputText text" type="text" id="titleText${this.presentLayerIndex}" placeholder="Add title" onkeyup="edits.changeText()">
                            </div>
                            <div class="div">
                                <span>Font weight</span>
                                <select id="titleFontWeight${this.presentLayerIndex}" onchange="edits.changeFontWeight()" class="value inputText">
                                    <option value="100">Light</option>
                                    <option value="700" >Bold</option>
                                    <option value="1000" selected>Bolder</option>
                                </select>
                            </div>


                            <div class="div">
                                <span>Font size</span>
                                <select name="" id="titleFontSize${this.presentLayerIndex}" onchange="edits.changeFontSize('self')" class="value inputText">
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
                                <input type="text" class="value inputText text" onkeyup="edits.changeFontFamily('self')" id="customFontFamily${this.presentLayerIndex}" placeholder="e.g. verdana, Sans-serif etc.">
                            </div>
                        </div>
                    </div>
                    <!-- Text Styles -->



                    <!-- Other Text Styles -->
                    <div class="optionsDIv" id="aboutStyles${this.presentLayerIndex}">
                        <span class="objectName" onclick="edits.expandOptions('aboutStyles${this.presentLayerIndex}', '')">
                            <span>Text</span>  
                            <i class="fa fa-caret-right"></i>
                        </span>
                        <div class="options" style="display:none;">
                            <div class="div">
                                <textarea  onkeyup="edits.changeOtherText()" class="value inputText text" name="" id="otherText${this.presentLayerIndex}" cols="22" placeholder="Add text"></textarea>
                            </div>


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
                                <select id="otherFontSize${this.presentLayerIndex}" onchange="edits.changeOtherFontSize()"  class="value inputText">
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
                                <input type="text" class="value inputText text" onkeyup="edits.changeOtherFontFamily('self')" id="otherCustomFontFamily${this.presentLayerIndex}" placeholder="e.g. verdana, Sans-serif etc.">
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
        if (this.totalLayers > 1) {
            this.inBetweenLayersDel();
            this.layers.splice(this.presentLayerIndex, 1);
            this.totalLayers -= 1;
            this.presentLayerDiv = document.getElementById(`styleBox${this.presentLayerIndex}`);
            this.presentLayerDiv.remove();
            document.getElementById(`layer${this.presentLayerIndex}`).remove();
            this.moveBackward();

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
        var jsObject = editor.layers;
        var jsonData = JSON.stringify(jsObject);
        console.log(jsonData);
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

                 }
            }
        }
        saveData();
    }


}
let editor = new Editor();




    
