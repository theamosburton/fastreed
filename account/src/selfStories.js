var visitedusername = '';
class FetchStories {
  constructor() {
    this.visitedusername = '';
    this.webstoriesData = [];
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('filter')) {
      const filter = urlParams.get('filter');
      if (filter != '' || filter == 'Published' || filter == 'Drafted' || filter == 'Verified') {
        this.filter = filter;
        var selectedStoryTypes = document.querySelectorAll('.storiesTypes .storyType');
        selectedStoryTypes.forEach((element) => {
          element.selected = false;
        });
        if (document.getElementById(`type${filter}`)) {
          document.getElementById(`type${filter}`).selected = true;
        }
      }else{
        this.filter = 'All';
      }
    }else{
      this.filter = 'All';
    }
    if (currentUsername) {
        this.visitedusername = currentUsername;
      if (adminLogged) {
        this.whoIs = 'Admin';
      }else if(userLogged){
        this.whoIs = 'User';
      }else{
        this.whoIs = 'Anon';
      }
    }else{
      this.whoIs = 'Self';
    }
    this.fetchStories();
  }

  fetchStories(){
    this.populateStoryLoading();
    const fetchWebstoryData = async () =>{
        const url = '/.ht/API/webstories.php';
        var encyDat = {
        'purpose' : 'fetchAll',
        'whois': 'Self',
        'username': `${this.visitedusername}`
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
            var webstories = data.message;
              this.webstoriesData = JSON.parse(webstories);
              this.renderWebstories();
          }else{
            webstoryDiv.style.display = 'none';
            var webstoriesDiv = document.getElementById('webstoriesDiv');
            var noUpload = document.createElement('div');
            noUpload.classList.add('noUpload');
            noUpload.innerHTML = `<div><i class="fa fa-circle-exclamation fa-xl" style="color:darkorange"></i></div>
                   <div> <p>Server Error</p></div>`;
            webstoriesDiv.appendChild(noUpload);
          }
        }else{
          webstoryDiv.style.display = 'none';
          var webstoriesDiv = document.getElementById('webstoriesDiv');
          var noUpload = document.createElement('div');
          noUpload.classList.add('noUpload');
          noUpload.innerHTML = `<div><i class="fa fa-circle-exclamation fa-xl" style="color:darkorange"></i></div>
                 <div> <p>Server Error</p></div>`;
          webstoriesDiv.appendChild(noUpload);
        }
    }
    fetchWebstoryData();
  }

  filterWebstory(input){
    var value = input.value;
    this.filter = value;
    this.webstoriesData = this.webstoriesData.reverse();
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('filter', `${value}`);
    const newQueryString = urlParams.toString();
    history.pushState(null, '', '?' + newQueryString);
    this.renderWebstories()
  }

  renderWebstories(){
    if(this.webstoriesData.length < 1){
      return;
    }
    var webstories = this.webstoriesData;
    webstories = webstories.reverse();
    if (this.filter == 'Published') {
      var published = [];
      for (var i = 0; i < webstories.length; i++) {
        var storyStatus = JSON.parse(webstories[i][5]);
        if (storyStatus.status == 'published') {
          published.push(webstories[i]);
        }
      }
      webstories = published;
    }else if (this.filter == 'Drafted') {
      var drafted = [];
      for (var i = 0; i < webstories.length; i++) {
        var storyStatus = JSON.parse(webstories[i][5]);
        if (storyStatus.status == 'drafted') {
          drafted.push(webstories[i]);
        }
      }
      webstories = drafted;
    }else if (this.filter == 'Verified') {
      var verified = [];
      for (var i = 0; i < webstories.length; i++) {
        var storyStatus = JSON.parse(webstories[i][8]);
        if (storyStatus.status == 'true') {
          verified.push(webstories[i]);
        }
      }
      webstories = verified;
    }else{
        webstories = this.webstoriesData;
    }
    var webstoryDiv = document.getElementById('webstories');
    if (!webstories.length) {
      webstoryDiv.style.display = 'none';
      var webstoriesDiv = document.getElementById('webstoriesDiv');
      if(webstoriesDiv.querySelectorAll('.noUpload')){
        var noUpload = webstoriesDiv.querySelectorAll('.noUpload');
        noUpload.forEach((element) => {
            element.style.display = 'none';
        });
      }
      var noUpload = document.createElement('div');
      noUpload.classList.add('noUpload');
        if (this.filter == 'All') {
          noUpload.innerHTML = `<div><i class="fa fa-circle-exclamation fa-xl"></i></div>
                 <div> <p>No Visual Story to display</p></div>`;
        }else{
          noUpload.innerHTML = `<div><i class="fa fa-circle-exclamation fa-xl"></i></div>
                 <div> <p>No ${this.filter} Visual Story to display</p></div>`;
        }

      webstoriesDiv.appendChild(noUpload);
    }else{
      var webstoriesDiv = document.getElementById('webstoriesDiv');
      webstoryDiv.style.display = 'flex';
      if(webstoriesDiv.querySelectorAll('.noUpload')){
        var noUpload = webstoriesDiv.querySelectorAll('.noUpload');
        noUpload.forEach((element) => {
            element.style.display = 'none';
        });
      }

      webstoryDiv.innerHTML = '';
      for (var i = 0; i < webstories.length; i++) {
        var webstoryData = JSON.parse(webstories[i][6]);
        var storyStatus = JSON.parse(webstories[i][5]);
        var verifyStatus = JSON.parse(webstories[i][8]);
        if ( Object.keys(webstoryData).length == 0) {
          var title = webstories[i][7];
        }else if (webstoryData.metaData.title == '' || webstoryData.metaData.title == undefined) {
          var title = webstories[i][7];
        }else{
          var title = webstoryData.metaData.title
        }

        if ( Object.keys(webstoryData).length == 0) {
          var setUrl =  '/assets/img/nomedia.png';
        }else if (webstoryData.layers.L0.media.url == undefined || webstoryData.layers.L0.media.url == '' ||webstoryData.layers.L0.media.url == 'default') {
          var setUrl =  '/assets/img/nomedia.png';
        }else{
          var setUrl = webstoryData.layers.L0.media.url;
        }
        if (verifyStatus != null && verifyStatus.status == 'true') {
          var storyURL = webstoryData.metaData.url;
          storyURL = '/webstories/'+storyURL;
          var func = `viewStory('${storyURL}')`;
          var icon = '<i class="fa-solid fa-earth-asia" style="color:#13c013;"></i>';
        }else if(storyStatus.status == 'published'){
            var storyURL = webstoryData.metaData.url;
            storyURL = '/webstories/'+storyURL;
            var func = `viewStory('${storyURL}')`;
            var icon = '<i class="fa-solid fa-earth-asia" style="color:darkorange;"></i>';
        }else{
          var func = "";
          var icon = '<i class="fa-solid fa-floppy-disk"></i>';
        }

        webstoryDiv.innerHTML += `
        <div class="webstory" id="webstory${i}">
            <div class="background" style="background-image: url('${setUrl}');">
                <div class="storyOverlay"></div>
                <div class="title">${title}</div>

            </div>
            <div class="options">
                <div class="edit" onclick="editStory('${webstories[i][1]}','')"> <i class="fa-solid fa-pen-to-square" style="color:blue;"></i></div>
                <div class="view" onclick="${func}"> <i class="fa fa-eye" style="color:blue;"></i></div>
                <div class="delete" onclick="deleteStory('${webstories[i][1]}', ${i})"><i class="fa fa-trash" style="color:#d95a3e;"></i> </div>
                <div class="storyOptions">${icon}</div>
            </div>
        </div>
        `;
      }
    }
  }

  populateStoryLoading() {
      if (document.getElementById('webstories') == null) {
        return;
      }
      var webstories = document.getElementById('webstories');
      webstories.innerHTML = `
      <div class="webstory loading">
           <div class="fading-div" >
           </div>
           <div class="options" style="border:0px" >
             <div class="fading-div" style="margin:0; padding: 15px;" >
             </div>
            </div>
       </div>

       <div class="webstory loading">
            <div class="fading-div" >
            </div>
            <div class="options" style="border:0px" >
              <div class="fading-div" style="margin:0; padding: 15px;" >
              </div>
             </div>
        </div>

        <div class="webstory loading">
             <div class="fading-div" >
             </div>
             <div class="options" style="border:0px" >
               <div class="fading-div" style="margin:0; padding: 15px;" >
               </div>
              </div>
         </div>

         <div class="webstory loading">
              <div class="fading-div" >
              </div>
              <div class="options" style="border:0px" >
                <div class="fading-div" style="margin:0; padding: 15px;" >
                </div>
               </div>
          </div>
      `;
  }
}

var fetchStoriesData = new FetchStories();
