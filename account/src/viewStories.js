function fetchStories(){
  populateStoryLoading();
  var currentURL = window.location.href;
  var urlSegments = currentURL.split('/');
  var indexOfU = urlSegments.indexOf('u');
  var username = '';
// Check if 'u' is in the URL and if there's a segment following it
if (indexOfU !== -1 && indexOfU < urlSegments.length - 1) {
  username = urlSegments[indexOfU + 1];
  if (adminLogged) {
    var whoIs = 'Admin';
  }else if(userLogged){
    var whoIs = 'User';
  }else{
    var whoIs = 'Anon';
  }
}else{
  var whoIs = 'Self';
}
  const fetchWebstoryData = async () =>{
      const url = '/.ht/API/webstories.php';
      var encyDat = {
      'purpose' : 'fetchAll',
      'whois': `${whoIs}`,
      'username': `${username}`
      };
      const response = await fetch(url, {
          method: 'post',
          headers: {
          'Content-Type': 'application/json'
          },
          body: JSON.stringify(encyDat)
      });
      var data = await response.json();
      var webstoryDiv = document.getElementById('webstories');
      if (data) {
        if (data.Result) {
          var webstories = data.message;
          webstories = JSON.parse(webstories);
          var publishedStories = [];

          for (var i = 0; i < webstories.length; i++) {
            var webstoryData = JSON.parse(webstories[i][7]);
            var storyStatus = JSON.parse(webstories[i][5]);
            console.log(webstoryData);
            var verifyStatus = JSON.parse(webstories[i][10]);
            if ( Object.keys(webstoryData).length == 0) {
              var title = webstories[i][8];
            }else if (webstoryData.metaData.title == '' || webstoryData.metaData.title == undefined) {
              var title = webstories[i][8];
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
              var href = webstoryData.metaData.url;
              href = '/webstories/'+ href;
              var func = `viewStory('${href}')`;
              var icon = '<i class="fa-solid fa-earth-asia" style="color:#13c013;"></i>';
              publishStory[i] = `
              <div class="webstory" id="webstory${i}" onclick="viewStory('${href}')">
                  <div class="background" style="background-image: url('${setUrl}');">
                      <div class="storyOverlay"></div>
                      <div class="title">${title}</div>
                  </div>
              </div>
              `;
            }else if(storyStatus.status == 'published'){
                var href = webstoryData.metaData.url;
                href = '/webstories/'+ href;
                var icon = '<i class="fa-solid fa-earth-asia" style="color:darkorange;"></i>';
                publishedStories[i] = `
                <div class="webstory" id="webstory${i}" onclick="viewStory('${href}')">
                    <div class="background" style="background-image: url('${setUrl}');">
                        <div class="storyOverlay"></div>
                        <div class="title">${title}</div>
                    </div>
                </div>
                `;
            }
          }

          if (!publishedStories.length) {
            webstoryDiv.style.display = 'none';
            var webstoriesDiv = document.getElementById('webstoriesDiv');
            var noUpload = document.createElement('div');
            noUpload.classList.add('noUpload');
            noUpload.innerHTML = `<div><i class="fa fa-circle-exclamation fa-xl"></i></div>
                   <div> <p>Nothing to display</p></div>`;
            webstoriesDiv.appendChild(noUpload);
          }else{
            webstoryDiv.innerHTML = '';
            for (var i = 0; i < publishedStories.length; i++) {
              webstoryDiv.innerHTML += publishedStories[i];
            }
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
fetchStories();

function populateStoryLoading() {
    var webstories = document.getElementById('webstories');
    webstories.innerHTML = `
    <div class="webstory">
         <div class="fading-div" >
         </div>
         <div class="options" style="border:0px" >
           <div class="fading-div" style="margin:0; padding: 15px;" >
           </div>
          </div>
     </div>

     <div class="webstory">
          <div class="fading-div" >
          </div>
          <div class="options" style="border:0px" >
            <div class="fading-div" style="margin:0; padding: 15px;" >
            </div>
           </div>
      </div>

      <div class="webstory">
           <div class="fading-div" >
           </div>
           <div class="options" style="border:0px" >
             <div class="fading-div" style="margin:0; padding: 15px;" >
             </div>
            </div>
       </div>

       <div class="webstory">
            <div class="fading-div" >
            </div>
            <div class="options" style="border:0px" >
              <div class="fading-div" style="margin:0; padding: 15px;" >
              </div>
             </div>
        </div>
    `;
}
