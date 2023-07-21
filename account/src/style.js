class styleThisPage{
    constructor(){
      var params = new URLSearchParams(window.location.search);
        this.optValue = params.get('opt');
        this.dashboardMenu = document.querySelector('#dashboardMenu');
        this.dashboardDiv = document.querySelector('#dashboardDiv');
       
        // check the hash and display what to show
        if (this.optValue == '' || this.optValue === null || this.optValue === 'undefined') {
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


function showImage(path, visibility, ID, ext, imgID){
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
      
      showContainer.innerHTML = `
          <div class="imgOptions">
            <i class="fa fa-times fa-xl optIcons" onclick="removeImage()"></i>
            <i class="fa fa-trash optIcons" id="deleteImageIcon" onclick="deleteImage('${ID}', '${ext}', 'photos', '${imgID}')"></i>
            <i class="fa fa-earth optIcons" id="earthIcon" onclick="showPicOptions('')"></i>
            <div class="optionDropdown" id="optionDropdown" style="display:none;">
              <span class="title">Who can view?</span>

              <div class="options" id="selfOption" onclick="changeImageVisibility('${ID}', 'self', '${imgID}', '${visibility}')"> <span>Only Me</span>   <i class="checkbox fa fa-regular ${self}"></i> </div>

              <div class="options" id="followersOption" onclick="changeImageVisibility('${ID}', 'followers', '${imgID}', '${visibility}')"><span>Following</span> <i class=" checkbox fa-regular ${following}"></i></div>

              <div class="options" id="everyoneOptionU" onclick="changeImageVisibility('${ID}', 'users', '${imgID}', '${visibility}')"><span>All Users</span> <i class=" checkbox fa-regular ${everyoneU}"></i></div>

              <div class="options" id="everyoneOptionA" onclick="changeImageVisibility('${ID}', 'anon', '${imgID}', '${visibility}')"><span>Anonymous</span> <i class=" checkbox fa-regular ${everyoneA}"></i></div>
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
  
  function showVideo(path, visibility, ID, ext, vidID){
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
      showContainer.innerHTML = `
              <div class="imgOptions">
              <i class="fa fa-times fa-xl optIcons" onclick="removeImage()"></i>
              <i class="fa fa-trash optIcons" id="deleteImageIcon" onclick="deleteImage('${ID}', '${ext}', 'videos', '${vidID}')"></i>
              <i class="fa fa-earth optIcons" onclick="showPicOptions('')"></i>
              <div class="optionDropdown" style="display:none;">
              <span class="title">Who can view?</span>

                <div class="options" id="selfOption" onclick="changeImageVisibility('${ID}', 'self', '${vidID}', '${visibility}')"> <span>Only Me</span>   <i class="checkbox fa fa-regular ${self}"></i> </div>

                <div class="options" id="followersOption" onclick="changeImageVisibility('${ID}', 'followers', '${vidID}', '${visibility}')"><span>Following</span> <i class=" checkbox fa-regular ${following}"></i></div>

                <div class="options" id="everyoneOptionU" onclick="changeImageVisibility('${ID}', 'users', '${vidID}', '${visibility}')"><span>All Users</span> <i class=" checkbox fa-regular ${everyoneU}"></i></div>

              <div class="options" id="everyoneOptionA" onclick="changeImageVisibility('${ID}', 'anon', '${vidID}', '${visibility}')"><span>Anonymous</span> <i class=" checkbox fa-regular ${everyoneA}"></i></div>

              </div>
            </div>
            <video controls controlsList="nodownload" onclick="showPicOptions('none')"> <source src="${path}" type="video/mp4"></vide>`;
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