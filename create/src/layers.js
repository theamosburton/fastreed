class Layers{
    constructor(){
        this.editorId = document.getElementById('editTab');
        this.presentLayerIndex = 0;
        this.presentLayer  = this.presentLayerIndex + 1 ;
        this.totalLayers = 1;
        this.layers = [];
        this.layers[0] = {
            'media': {},
            'title':{},
            'description': {},
            'caption' :{}
        };
        var newLayer = document.createElement('div');
        newLayer.id = `layer${this.presentLayerIndex}`;
        newLayer.className = 'layers';
        newLayer.innerHTML = `<div class="placeholder">
            <p> Add</p>
            <p> Photos/Videos</p>
            <small> Recomended ratios are </small>
            <small> 9:16, 3:4 and 2:3 </small>
        </div>`;
        this.editorId.appendChild(newLayer);
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
    }

    

    createNewLayer(){  
        this.inBetweenLayersAdd();
        this.totalLayers += 1;
        this.presentLayerIndex += 1;
        this.presentLayer  = this.presentLayerIndex + 1 ;
        this.layers[this.presentLayerIndex] = {
            'media': {},
            'title':{},
            'description': {},
            'caption' :{}
        };
        var newLayer = document.createElement('div');
        newLayer.id = `layer${this.presentLayerIndex}`;
        newLayer.className = 'layers';
        
        this.editorId.appendChild(newLayer);
        this.presentLayerDiv = document.getElementById(`layer${this.presentLayerIndex}`);
        for (var i = 0; i < this.totalLayers; i++) {
            document.getElementById(`layer${i}`).style.display = 'none';
        }
        newLayer.innerHTML = `<div class="placeholder">
            <p> Add</p>
            <p> Photos/Videos</p>
            <small> Recomended ratios are </small>
            <small> 9:16, 3:4 and 2:3 </small>
        </div>`;
        this.presentLayerDiv.style.display = 'flex';
        this.playPauseLastMedia('add');
        this.layersAhead = this.totalLayers - this.presentLayer;
        this.layersBack = this.totalLayers-this.layersAhead-1;
        document.getElementById('forwardIcon').innerHTML = `${this.layersAhead}&nbsp;`;
        document.getElementById('backwardIcon').innerHTML = `&nbsp;${this.layersBack}`;
    }

    inBetweenLayersAdd(){
        if (this.presentLayerIndex+1 != this.totalLayers) {
            var presentLayer  = this.presentLayerIndex + 1 ;
            var layersAhead = this.totalLayers - presentLayer;
            for (let i = layersAhead; i >= 1; i--) {
                var layersIndex = presentLayer + i - 1;
                // console.log(`Current layersIndex: ${layersIndex}`);
                document.getElementById(`layer${layersIndex}`).id = `layer${layersIndex + 1}`;
                console.log(`Updated id: layer${layersIndex + 1}`);
            }

        }
    }

    deleteLayer(){
        if (this.totalLayers > 1 && this.presentLayerIndex != 0) {
            this.inBetweenLayersDel();
            this.layers.splice(this.presentLayerIndex, 1);
            this.totalLayers -= 1;
            this.presentLayerDiv = document.getElementById(`layer${this.presentLayerIndex}`);
            this.presentLayerDiv.remove();
            this.moveBackward();

        }
    }

    inBetweenLayersDel(){
        if (this.presentLayerIndex+1 != this.totalLayers) {
            var presentLayer  = this.presentLayerIndex + 1 ;
            var layersAhead = this.totalLayers - presentLayer;
            for (let i = layersAhead; i >= 1; i--) {
                var layersIndex = presentLayer + i - 1;
                console.log(`Current layersIndex: ${layersIndex}`);
                document.getElementById(`layer${layersIndex}`).id = `layer${layersIndex - 1}`;
                console.log(`Updated id: layer${layersIndex - 1}`);
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
            }   
            this.presentLayerDiv.style.display = 'flex';
            this.playPauseLastMedia('forward');
        }
        this.presentLayer  = this.presentLayerIndex + 1 ;
        this.layersAhead = this.totalLayers - this.presentLayer;
        this.layersBack = this.totalLayers-this.layersAhead-1;
        document.getElementById('forwardIcon').innerHTML = `${this.layersAhead}&nbsp;`;
        document.getElementById('backwardIcon').innerHTML = `&nbsp;${this.layersBack}`;
    }

    moveBackward(){    
        if (this.presentLayerIndex > 0) {
            this.presentLayerIndex -= 1;
            this.presentLayer  = this.presentLayerIndex + 1 ;
            this.presentLayerDiv = document.getElementById(`layer${this.presentLayerIndex}`);
            for (var i = 0; i < this.totalLayers; i++) {
                document.getElementById(`layer${i}`).style.display = 'none';
            }
            this.presentLayerDiv.style.display = 'flex';
            this.playPauseLastMedia('backward');
        }
        this.layersAhead = this.totalLayers - this.presentLayer;
        this.layersBack = this.totalLayers-this.layersAhead-1;
        document.getElementById('forwardIcon').innerHTML = `${this.layersAhead}&nbsp;`;
        document.getElementById('backwardIcon').innerHTML = `&nbsp;${this.layersBack}`;
       
    }
    modifyMedia(type, blobUrl, url){
        var deleteMediaButton = document.getElementById('deleteMedia');
        if (type == 'image') {
            deleteMediaButton.setAttribute("onclick", "layers.deleteMedia('image')");
        }else if(type == 'video'){
            deleteMediaButton.setAttribute("onclick", "layers.deleteMedia('video')");
        }else{
            deleteMediaButton.removeAttribute("onclick");
        }

        this.presentLayerDiv.innerHTML = '';
        this.layers[this.presentLayerIndex].media = {
            'type': type,
            'blobUrl' : blobUrl,
            'url' : url,
        };
    }

    deleteMedia(type){
        if(type == 'image'){
            var media = document.querySelector(`#${this.presentLayerDiv.id} img`);
        }else if(type == 'video'){
            var media = document.querySelector(`#${this.presentLayerDiv.id} video`);
        }
        this.presentLayerDiv.innerHTML =`
            <div class="placeholder">
                <p> Add</p>
                <p> Photos/Videos</p>
                <small> Recomended ratios are </small>
            <small> 9:16, 3:4 and 2:3 </small>
            </div>`;
        media.remove();
        this.layers[this.presentLayerIndex].media = {};
    }

    modifyTitle(text){
        this.layers[this.presentLayerIndex].title = {
            'text': text
        };
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
}
let layers = new Layers();


    
