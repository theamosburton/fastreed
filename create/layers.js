class Layers{
    constructor(){
        this.editorId = document.getElementById('editTab');
        this.presentLayer = 0;
        this.totalLayers = 1;
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
            <small> Layer ${this.presentLayer+1}</small>
            <small> Expected ratio 9:16 </small>
            
        </div>`;
        this.editorId.appendChild(newLayer);
        this.presentLayerDiv = document.getElementById(`layer${this.presentLayer}`);
        var otherLayers = document.querySelector("#editTab .layers");
        for (var i = 0; i < otherLayers.length; i++) {
            otherLayers[i].style.display = "none";
        }

        this.presentLayerDiv.style.display = 'flex';
        // this.updatePlusButton();
        // this.updateMinusButton();
    }

    

    createNewLayer(){  
        if (this.presentLayer+1 == this.totalLayers) {
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
                <small> Layer ${this.presentLayer+1}</small>
                <small> Expected ratio 9:16 </small>
            </div>`;
            this.presentLayerDiv.style.display = 'flex';
        } 

        this.updatePlusButton();
        this.updateMinusButton();
        
    }

    deleteLayer(){
        if (this.presentLayer+1 == this.totalLayers) {
            this.layers.splice(this.presentLayer, 1);
            this.totalLayers -= 1;
            this.presentLayerDiv = document.getElementById(`layer${this.presentLayer}`);
            this.presentLayerDiv.remove();
            this.moveBackward();
        }
        this.updatePlusButton();
        this.updateMinusButton();
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
        }
        this.updatePlusButton();
        this.updateMinusButton();
    }
    moveBackward(){    
        if (this.presentLayer > 0) {
            
            this.presentLayer -= 1;
            this.presentLayerDiv = document.getElementById(`layer${this.presentLayer}`);
            for (var i = 0; i < this.totalLayers; i++) {
                document.getElementById(`layer${i}`).style.display = 'none';
            }
    
            this.presentLayerDiv.style.display = 'flex';
        }

        this.updatePlusButton();
        this.updateMinusButton();
       
    }
    modifyMedia(type, blobUrl, url){
        this.presentLayerDiv.innerHTML = '';
        this.layers[this.presentLayer].media = {
            'type': type,
            'blobUrl' : blobUrl,
            'url' : url,
        };
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
}
let layers = new Layers();

    
