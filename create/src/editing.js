class Edits{
    constructor(){
        this.editor = editor;
        this.editor.layers[this.editor.presentLayerIndex].title.text = '';
        this.editor.layers[this.editor.presentLayerIndex].title.bgColor = '';
        this.editor.layers[this.editor.presentLayerIndex].title.bgOpacity = '';
        this.editor.layers[this.editor.presentLayerIndex].title.fontSize = '';
        this.editor.layers[this.editor.presentLayerIndex].title.fontWeight = '';
        this.editor.layers[this.editor.presentLayerIndex].title.textColor ='';

        this.editor.layers[this.editor.presentLayerIndex].description.text = '';
        this.editor.layers[this.editor.presentLayerIndex].description.bgColor = '';
        this.editor.layers[this.editor.presentLayerIndex].description.bgOpacity = '';
        this.editor.layers[this.editor.presentLayerIndex].description.fontSize = '';
        this.editor.layers[this.editor.presentLayerIndex].description.fontWeight = '';
        this.editor.layers[this.editor.presentLayerIndex].description.textColor ='';
        
        this.editor.layers[this.editor.presentLayerIndex].media.style = {};
        this.editor.layers[this.editor.presentLayerIndex].media.style.mediaFit = "";
        this.editor.layers[this.editor.presentLayerIndex].media.style.overlayColor = "";
        this.editor.layers[this.editor.presentLayerIndex].media.style.overlayOpacity = "";
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
        console.log(this.editor.layers);
    }

    overlayOpacity(){
        var mediaOverlayOpacity = document.getElementById('mediaOverlayOpacity');
        this.editor.layers[this.editor.presentLayerIndex].media.style.overlayOpacity = mediaOverlayOpacity.value;
        console.log(this.editor.layers);
    }
    overlayColor(){
        var overlayColor = document.getElementById('mediaOverlayColor');
        this.editor.layers[this.editor.presentLayerIndex].media.style.overlayColor = overlayColor.value;
    }
    mediaFit(){
        var mediaFit = document.getElementById('mediaFit');
        this.editor.layers[this.editor.presentLayerIndex].media.style.mediaFit = mediaFit.value;
    }
    // Media Editing

}

let edits = new Edits();
