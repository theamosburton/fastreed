function hideSection(id){
  var sectionID = document.getElementById(`${id}`);
  sectionID.style.display = 'none';
  var hsLeft = document.getElementById('hsLeft');
  var hsRight = document.getElementById('hsRight');
  hsLeft.style.display = 'flex';
  hsRight.style.display = 'flex';
}
function showSection(section, id2){
  var Section = document.getElementById(`${section}`);
  var hsLeft = document.getElementById('hsLeft');
  var hsRight = document.getElementById('hsRight');
  var lefthideMe = document.getElementById(`${id2}`);
  lefthideMe.style.display = 'flex';
  hsLeft.style.display = 'none';
  hsRight.style.display = 'none';
  Section.style.display = 'flex';
}
function openOptions(x){
  let layerName = document.getElementById('layerNumber');
  let metaDataName = document.getElementById('metaDataName');
  let metaData = document.getElementById('metaData');
  let objectOptions = document.getElementById('objectOptions');

  if (x == 'layers') {
    metaData.style.display = 'none';
    if (metaDataName.classList.contains("active")) {
      metaDataName.classList.remove('active');
    }

    layerName.classList.add('active');
    objectOptions.style.display = 'block';

  }else if(x == 'metadata'){
    objectOptions.style.display = 'none';
    if (layerName.classList.contains("active")) {
      layerName.classList.remove('active');
    }

    metaDataName.classList.add('active');
    metaData.style.display = 'block';
  }

}

function showPreview(){
  var urlObj = new URL(window.location.href);
  var id = urlObj.searchParams.get("ID");
  var newTab = window.open(`/create/preview?webstory=${id}`, '_blank');
}
