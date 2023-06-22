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
      var imgElement = document.createElement('img');
      imgElement.src = link;
      editorId.innerHTML = '';
      editorId.appendChild(imgElement);
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
      // var link = 'your_video_url_here';

      videoElement.src = `${link}`;
      videoElement.addEventListener('loadeddata', function() {
        videoElement.src = videoElement.currentSrc;
      });

      editorId.innerHTML = '';
      editorId.appendChild(videoElement);

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