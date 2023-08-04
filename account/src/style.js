class styleThisPage{
    constructor(){
        this.hashValue = window.location.hash.substr(1);
        this.optValue = this.hashValue;
        this.dashboardMenu = document.querySelector('#dashboardMenu');
        this.dashboardDiv = document.querySelector('#dashboardDiv');
        this.othersMenus = document.getElementsByClassName('menus');
        this.othersDivs = document.getElementsByClassName('contentView');
        for (var i = 0; i < this.othersMenus.length; i++) {
            // othersDivs[i].style.display = 'none';
            if (this.othersMenus[i].classList.contains('active')) {
              this.othersMenus[i].classList.remove('active');
            }
        }
        for (var i = 0; i < this.othersDivs.length; i++) {
            this.othersDivs[i].style.display = 'none';
        }

        // check the hash and display what to show
        if (this.optValue == 'opt' || this.optValue == '' || this.optValue === null || this.optValue === 'undefined') {
            // Stay on dashboard
            this.dashboardMenu.classList.add('active');
            this.dashboardDiv.style.display = 'block';
            this.dashboardMenu.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }else{
            var showDiv = document.getElementById(`${this.optValue}Div`);
            var showMenu = document.getElementById(`${this.optValue}Menu`);
            showMenu.classList.add('active');
            showDiv.style.display = 'block';
            showMenu.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    updateMenus(x){
      for (var i = 0; i < this.othersMenus.length; i++) {
          // othersDivs[i].style.display = 'none';
          if (this.othersMenus[i].classList.contains('active')) {
            this.othersMenus[i].classList.remove('active');
          }
      }
      for (var i = 0; i < this.othersDivs.length; i++) {
          this.othersDivs[i].style.display = 'none';
      }


      // check the hash and display what to show
      if (x == 'opt' || x == '' || x === null || x === 'undefined') {
        this.dashboardMenu.classList.add('active');
        this.dashboardDiv.style.display = 'block';
        this.dashboardMenu.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }else{
        var showDiv = document.getElementById(`${x}Div`);
        var showMenu = document.getElementById(`${x}Menu`);
        showMenu.classList.add('active');
        showDiv.style.display = 'block';
        showMenu.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }

}
var stylePage = new styleThisPage();

function exapndAndShrink(id){
    let div = document.getElementById(`${id}`);
    let isDisplay = div.style.display;
    if (isDisplay == 'none') {

        div.style.display= 'block';
        div.style.height= 'auto';
    }else{
        div.style.height= '0';
        div.style.display= 'none';

    }
}

function showImage(path, visibility, ID, ext, imgID, time, size, status){
  if (size >= 1024) {
    size = size/1024;
    size = size.toFixed(2);
    size = size+'MB';
  }else{
    size = size+'KB';
  }
    var showContainer = document.querySelector('#imageShowDiv .imageContainer');
    var showImageDiv = document.getElementById('imageShowDiv');
    if (showImageDiv.style.display == 'none') {
      showImageDiv.style.display = 'flex';
      if (visibility == 'self') {
        var self = 'fa-square-check';
        var everyoneU = 'fa-square';
        var everyoneA = 'fa-square';
        var following = 'fa-square';
      }else if(visibility == 'followers'){
        var following = 'fa-square-check';
        var everyoneU = 'fa-square';
        var everyoneA = 'fa-square';
        var self = 'fa-square';
      }else if(visibility == 'anon'){
        var everyoneA = 'fa-square-check';
        var following = 'fa-square';
        var everyoneU = 'fa-square';
        var self = 'fa-square';
      }else if(visibility == 'users'){
        var everyoneU = 'fa-square-check';
        var following = 'fa-square';
        var everyoneA = 'fa-square';
        var self = 'fa-square';
      }
      var t = new Intl.DateTimeFormat('en', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
      }).format(new Date(time * 1000));
      var restrictMedia = '';
      var statusOpt = '';
      var statusInfo = '';

      if (status ==  'VFD') {
        statusInfo = `<div class="details uploadStatus">
          <span class="property">Status:</span>
          <span class="value" style="color: green;"> Verified By Admin <i class="checkbox fa-regular fa-circle-check"></i></span>
        </div>`;
      }else if (status ==  'UFD') {
        statusInfo = `<div class="details uploadStatus">
          <span class="property">Status:</span>
          <span class="value" style="color: orange;"> Not Verified</span>
        </div>`;
      }else if (status ==  'VLD') {
        statusInfo = `<div class="details uploadStatus">
          <span class="property">Status:</span>
          <span class="value" style="color: red;"> Image is against community guidelines</span>
        </div>`;
      }else{
        statusInfo = `<div class="details uploadStatus">
          <span class="property">Status:</span>
          <span class="value" style="color: orange;"> Not Verified</span>
        </div>`;
      }

      if (adminLogged) {
          restrictMedia = `<div class="options" id="restrictMedia" onclick="restrictMedia('${ID}', 'VLD', '${status}', 'restrictMedia')"><span>Violated</span> <i class="checkbox fa-regular fa-square"></i></div>`;
        if (status == 'UFD') {
          statusOpt = `<div class="options" id="verifyImage" onclick="restrictMedia('${ID}', 'VFD', '${status}', 'verifyImage')"><span>Verify Image</span> <i class=" checkbox fa-regular fa-square"></i></div>`;
        }else if (status == 'VFD') {
          statusOpt = `<div class="options" id="verifyImage" onclick="restrictMedia('${ID}', 'UFD', '${status}','verifyImage')"><span>Verify Image</span> <i class=" checkbox fa-regular fa-square-check"></i></div>`;
        }else if (status == 'VLD') {
          restrictMedia = `<div class="options" id="restrictMedia" onclick="restrictMedia('${ID}', 'VLD', '${status}','restrictMedia')"><span>Violated</span> <i class="checkbox fa-regular fa-square-check"></i></div>`;
          statusOpt = `<div class="options" id="verifyImage" onclick="restrictMedia('${ID}', 'UFD', '${status}','verifyImage')"><span>Verify Image</span> <i class=" checkbox fa-regular fa-square-check"></i></div>`;
        }

      }

      showContainer.innerHTML = `
          <div class="imgOptions">
            <i class="fa fa-times fa-xl optIcons" onclick="removeImage()"></i>
            <i class="fa fa-trash optIcons" id="deleteImageIcon" onclick="deleteImage('${ID}', '${ext}', 'photos', '${imgID}')"></i>
            <i class="fa fa-earth optIcons" id="earthIcon" onclick="showPicOptions('')"></i>
            <i class="fa fa-info optIcons" id="infoIcon" onclick="showInfo('')"></i>
            <div class="optionDropdown" id="optionDropdown" style="display:none;">
              <span class="title">Who can view?</span>

              <div class="options" id="selfOption" onclick="changeImageVisibility('${ID}', 'self', '${imgID}', '${visibility}')"> <span>Only Me</span>   <i class="checkbox fa fa-regular ${self}"></i> </div>

              <div class="options" id="followersOption" onclick="changeImageVisibility('${ID}', 'followers', '${imgID}', '${visibility}')"><span>Following</span> <i class=" checkbox fa-regular ${following}"></i></div>

              <div class="options" id="everyoneOptionU" onclick="changeImageVisibility('${ID}', 'users', '${imgID}', '${visibility}')"><span>All Users</span> <i class=" checkbox fa-regular ${everyoneU}"></i></div>

              <div class="options" id="everyoneOptionA" onclick="changeImageVisibility('${ID}', 'anon', '${imgID}', '${visibility}')"><span>Anonymous</span> <i class=" checkbox fa-regular ${everyoneA}"></i></div>
              ${statusOpt}
              ${restrictMedia}
            </div>


            <div class="optionDropdown" id="optionDropdownDetails" style="display:none;">
              <div class="details size">
                <span class="property">File Size: </span>
                <span class="value">${size}</span>
              </div>
              <div class="details">
                <span class="property">Link:</span>
                <textarea id="linkToCopy" style="position: absolute; top: -9999px;"></textarea>
                <span class="value copylink" onclick="copyLink('${path}')">Copy <i class="fa fa-solid fa-copy"></i></span>
              </div>
              <div class="details uploadDT">
                <span class="property">Upload Time:</span>
                <span class="value">${t} (IST)</span>
              </div>
              ${statusInfo}
            </div>
          </div>
            <img src="${path}" onclick="showPicOptions('none')" alt=""></img>`;
      disbaleScroll();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });

    }

    if (visibility == 'none') {
      document.getElementById('earthIcon').remove();
      document.getElementById('deleteImageIcon').remove();
      document.getElementById('optionDropdown').remove();
    }
  }

function showVideo(path, visibility, ID, ext, vidID, time, size, status){
  var showImageDiv = document.getElementById('imageShowDiv');
  if (showImageDiv.style.display == 'none') {
    showImageDiv.style.display = 'flex';
    var showContainer = document.querySelector('#imageShowDiv .imageContainer');
    if (visibility == 'self') {
      var self = 'fa-square-check';
      var everyoneU = 'fa-square';
      var everyoneA = 'fa-square';
      var following = 'fa-square';
    }else if(visibility == 'followers'){
      var following = 'fa-square-check';
      var everyoneU = 'fa-square';
      var everyoneA = 'fa-square';
      var self = 'fa-square';
    }else if(visibility == 'anon'){
      var everyoneA = 'fa-square-check';
      var following = 'fa-square';
      var everyoneU = 'fa-square';
      var self = 'fa-square';
    }else if(visibility == 'users'){
      var everyoneU = 'fa-square-check';
      var following = 'fa-square';
      var everyoneA = 'fa-square';
      var self = 'fa-square';
    }
    var t = new Intl.DateTimeFormat('en', {
      day: '2-digit',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      hour12: true,
    }).format(new Date(time * 1000));
    showContainer.innerHTML = `
            <div class="imgOptions">
            <i class="fa fa-times fa-xl optIcons" onclick="removeImage()"></i>
            <i class="fa fa-trash optIcons" id="deleteImageIcon" onclick="deleteImage('${ID}', '${ext}', 'videos', '${vidID}')"></i>
            <i class="fa fa-earth optIcons" onclick="showPicOptions('')"></i>
            <i class="fa fa-info optIcons" onclick="showInfo('${ID}')"></i>
            <div class="optionDropdown" style="display:none;">
            <span class="title">Who can view?</span>

              <div class="options" id="selfOption" onclick="changeImageVisibility('${ID}', 'self', '${vidID}', '${visibility}')"> <span>Only Me</span>   <i class="checkbox fa fa-regular ${self}"></i> </div>

              <div class="options" id="followersOption" onclick="changeImageVisibility('${ID}', 'followers', '${vidID}', '${visibility}')"><span>Following</span> <i class=" checkbox fa-regular ${following}"></i></div>

              <div class="options" id="everyoneOptionU" onclick="changeImageVisibility('${ID}', 'users', '${vidID}', '${visibility}')"><span>All Users</span> <i class=" checkbox fa-regular ${everyoneU}"></i></div>

              <div class="options" id="everyoneOptionA" onclick="changeImageVisibility('${ID}', 'anon', '${vidID}', '${visibility}')"><span>Anonymous</span> <i class=" checkbox fa-regular ${everyoneA}"></i></div>

            </div>
          </div>
          <video controls controlsList="nodownload" onclick="showPicOptions('none')"> <source src="${path}" type="video/mp4"></video>`;
    disbaleScroll();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
    if (visibility == 'none') {
      document.getElementById('earthIcon').remove();
      document.getElementById('deleteImageIcon').remove();
      document.getElementById('optionDropdown').remove();
    }
  }
}


function removeImage(){
  var showImageDiv = document.getElementById('imageShowDiv');
  if (showImageDiv.style.display != 'none') {
    var showContainer = document.querySelector('#imageShowDiv .imageContainer');
    showContainer.innerHTML = ``;
    showImageDiv.style.display = 'none';
    enableScroll();
  }
}

function follow(){
  var followButton = document.getElementById('followButton');
  followButton.innerHTML = '...';

  const followUser = async () =>{
      const url = '/.ht/API/follow.php/?follow';
      var encyDat = {
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
        if (data.Result) {
          followButton.innerHTML = 'Followed';
          followButton.setAttribute( "onClick", "javascript: unfollow();");
        }else{
        followButton.innerHTML = `${data.message}`;
        }
      }else{
          followButton.innerHTML = `${data.message}`;
      }
    }
    followUser();
}

function unfollow(){
  var followButton = document.getElementById('followButton');
  followButton.innerHTML = '...';

  const unfollowUser = async () =>{
      const url = '/.ht/API/follow.php/?unfollow';
      var encyDat = {
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
        if (data.Result) {
          followButton.innerHTML = 'Follow';
          followButton.setAttribute( "onClick", "javascript: follow();");
        }else{
          followButton.innerHTML = "Can't  unfollow";
        }
      }else{
          followButton.innerHTML = "Can't  unfollow";
      }
    }
    unfollowUser();
}
