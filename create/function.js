function dragStartHandler(event) {
    event.dataTransfer.setData("text/plain", event.target.id);
  }
  
  function dragOverHandler(event) {
    event.preventDefault();
  }
  
  function dropHandler(event) {
    event.preventDefault();
    const data = event.dataTransfer.getData("text/plain");
    const draggedElement = document.getElementById(data);
    const img = draggedElement.querySelector("img");
    event.target.innerHTML = "";
    event.target.appendChild(img);
  }
  

  function hideSection(id, icon){
    var sectionID = document.getElementById(`${id}`);
    sectionID.style.display = 'none';
    var hsLeft = document.getElementById('hsLeft');
    var hsRight = document.getElementById('hsRight');
    hsLeft.style.display = 'flex';
    hsRight.style.display = 'flex';
  }

  function showSection(section, id, id2){
    var Section = document.getElementById(`${section}`);
    var hsLeft = document.getElementById('hsLeft');
    var hsRight = document.getElementById('hsRight');
    var lefthideMe = document.getElementById(`${id2}`);
    lefthideMe.style.display = 'flex';
    hsLeft.style.display = 'none';
    hsRight.style.display = 'none';
    Section.style.display = 'flex';
  }

  function selectMedia(selfId, link, type){
    var editorId = document.getElementById(`editTab`);
    var hsLeft = document.getElementById('hsLeft');
    var hsRight = document.getElementById('hsRight');
    var leftSection = document.getElementById('leftSection');
    if (type == 'image') {


      var imageElement = document.createElement('img');

      fetch(link)
        .then(response => response.blob())
        .then(blob => {
          var imgURL = URL.createObjectURL(blob);
          imageElement.src = imgURL;
          editorId.innerHTML = '';
          editorId.appendChild(imageElement);
        });


      var screenWidth = window.innerWidth;
      if (screenWidth < 600) {
        if (leftSection.style.display = 'flex') {
          leftSection.style.display = 'none';
          hsLeft.style.display = 'flex';
          hsRight.style.display = 'flex';
        }
      }
    }else if(type == 'video'){
      var videoElement = document.createElement('video');
      var sourceElement = document.createElement('source');

      fetch(link)
        .then(response => response.blob())
        .then(blob => {
          var videoURL = URL.createObjectURL(blob);
          sourceElement.src = videoURL;
          sourceElement.type = 'video/mp4';
      
          // Append the source element to the video element
          videoElement.appendChild(sourceElement);
      
          // Append the video element to the editorId element
          editorId.innerHTML = '';
          editorId.appendChild(videoElement);
        });
      
      

      var screenWidth = window.innerWidth;
      if (screenWidth < 600) {
        if (leftSection.style.display = 'flex') {
          leftSection.style.display = 'none';
          hsLeft.style.display = 'flex';
          hsRight.style.display = 'flex';
        }
      }
    }

  }