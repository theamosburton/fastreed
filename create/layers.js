class Layers{
    constructor(){
        this.editorId = document.getElementById('editTab');
        this.presentLayer = 0;
        this.totalLayers = 1;
        this.layersCount = `${this.presentLayer+1} - ${this.totalLayers}`;
        this.layers = [];
        this.layers[0] = {
            'media': {},
            'title':{},
            'description': {},
            'caption' :{}
        };
        var newLayer = document.createElement('div');
        newLayer.id = `layer${this.presentLayer}`;
        newLayer.className = 'layers';
        newLayer.innerHTML = `<div class="placeholder">
            <p> Add</p>
            <p> Photos/Videos</p>
            <small> Recomended ratios are </small>
            <small> 9:16, 3:4 and 2:3 </small>
        </div>`;
        this.editorId.appendChild(newLayer);
        this.presentLayerDiv = document.getElementById(`layer${this.presentLayer}`);
        var otherLayers = document.querySelector("#editTab .layers");
        for (var i = 0; i < otherLayers.length; i++) {
            otherLayers[i].style.display = "none";
        }
        document.getElementById('layerCount').innerHTML = this.layersCount;

        this.presentLayerDiv.style.display = 'flex';
    }

    

    createNewLayer(){  
        this.inBetweenLayersAdd();
        this.totalLayers += 1;
        this.presentLayer += 1;
        this.layers[this.presentLayer] = {
            'media': {},
            'title':{},
            'description': {},
            'caption' :{}
        };
        var newLayer = document.createElement('div');
        newLayer.id = `layer${this.presentLayer}`;
        newLayer.className = 'layers';
        
        this.editorId.appendChild(newLayer);
        this.presentLayerDiv = document.getElementById(`layer${this.presentLayer}`);
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
        document.getElementById('layerCount').innerHTML = `${this.presentLayer+1} - ${this.totalLayers}`;
    }

    inBetweenLayersAdd(){
        if (this.presentLayer+1 != this.totalLayers) {
            var PL  = this.presentLayer + 1 ;
            var layersAhead = this.totalLayers - PL;
            for (let i = layersAhead; i >= 1; i--) {
                var layersIndex = PL + i - 1;
                console.log(`Current layersIndex: ${layersIndex}`);
                document.getElementById(`layer${layersIndex}`).id = `layer${layersIndex + 1}`;
                console.log(`Updated id: layer${layersIndex + 1}`);
            }

        }
    }

    deleteLayer(){
        if (this.totalLayers > 1 && this.presentLayer != 0) {
            this.inBetweenLayersDel();
            this.layers.splice(this.presentLayer, 1);
            this.totalLayers -= 1;
            this.presentLayerDiv = document.getElementById(`layer${this.presentLayer}`);
            this.presentLayerDiv.remove();
            this.moveBackward();

        }
    }

    inBetweenLayersDel(){
        if (this.presentLayer+1 != this.totalLayers) {
            var PL  = this.presentLayer + 1 ;
            var layersAhead = this.totalLayers - PL;
            for (let i = layersAhead; i >= 1; i--) {
                var layersIndex = PL + i - 1;
                console.log(`Current layersIndex: ${layersIndex}`);
                document.getElementById(`layer${layersIndex}`).id = `layer${layersIndex - 1}`;
                console.log(`Updated id: layer${layersIndex - 1}`);
            }
        }
    }
    moveForward(){
        var endOfLayers  = this.totalLayers <= this.presentLayer +1;
        if (!endOfLayers) {
            this.presentLayer  += 1;
            this.presentLayerDiv = document.getElementById(`layer${this.presentLayer}`);
            for (var i = 0; i < this.totalLayers; i++) {
                document.getElementById(`layer${i}`).style.display = 'none';
            }
            this.presentLayerDiv.style.display = 'flex';
            this.playPauseLastMedia('forward');
        }
        document.getElementById('layerCount').innerHTML =  `${this.presentLayer+1} - ${this.totalLayers}`;
    }

    moveBackward(){    
        if (this.presentLayer > 0) {
            this.presentLayer -= 1;
            this.presentLayerDiv = document.getElementById(`layer${this.presentLayer}`);
            for (var i = 0; i < this.totalLayers; i++) {
                document.getElementById(`layer${i}`).style.display = 'none';
            }
            this.presentLayerDiv.style.display = 'flex';
            this.playPauseLastMedia('backward');
        }
        document.getElementById('layerCount').innerHTML = `${this.presentLayer+1} - ${this.totalLayers}`;
       
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
        this.layers[this.presentLayer].media = {
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
        this.layers[this.presentLayer].media = {};
    }

    modifyTitle(text){
        this.layers[this.presentLayer].title = {
            'text': text
        };
    }

    updatePlusButton(){
        var plusIcon = document.getElementById('plusIcon');
        if (this.presentLayer+1 == this.totalLayers) {
            plusIcon.style.color= 'darkgreen';
            plusIcon.setAttribute('onclick', 'layers.createNewLayer()');
        }else{
            plusIcon.style.color = 'coral';
            plusIcon.removeAttribute('onclick');
        }
    }

    playPauseMedia(){
        var playPauseMedia = document.querySelector(`#layer${this.presentLayer} #playPauseMedia`);
        var video = document.querySelector(`#layer${this.presentLayer} video`);
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
        var muteUnmute = document.querySelector(`#layer${this.presentLayer} #muteUnmute`);
        var video = document.querySelector(`#layer${this.presentLayer} video`);
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

    updateMinusButton(){
        var minusIcon = document.getElementById('minusIcon');
        if (this.presentLayer+1 == this.totalLayers) {
            minusIcon.style.color= 'darkgreen';
            minusIcon.setAttribute('onclick', 'layers.deleteLayer()');
        }else{
            minusIcon.style.color = 'coral';
            minusIcon.removeAttribute('onclick');
        }
    }


    playPauseLastMedia(direction){
        let presentLayer = this.presentLayer-1;
        if (direction == 'add') {
            let presentLayerMedia = document.querySelector(`#layer${presentLayer} video`);
            if (presentLayerMedia) {
                presentLayerMedia.pause();
            }
        }else if(direction == 'forward'){
            let presentLayerMedia = document.querySelector(`#layer${presentLayer} video`);
            if (presentLayerMedia) {
                presentLayerMedia.pause();
            }
        }else{
            let presentLayerMedia = document.querySelector(`#layer${presentLayer+2} video`);
            if (presentLayerMedia) {
                presentLayerMedia.pause();
            }
        }
        
    }
}
let layers = new Layers();

    
