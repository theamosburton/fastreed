
const urlParams = new URLSearchParams(window.location.search);
if (adminLogged) {
  if (urlParams.has('nav')) {
      const param1Value = urlParams.get('nav')
      var select = document.getElementById('shuffleStoryType');
      if (param1Value == 'verify') {
        select.options[1].selected = true;
        fetchStoriesReview('true');
      }else if (param1Value == 'rejected') {
        select.options[2].selected = true;
        // document.getElementById('rejectOption');
        fetchStoriesReview('false');
      }else{
        select.options[0].selected = true;
        // document.getElementById('newOption');
        fetchStoriesReview('none');
      }
  }else{
    fetchStoriesReview('none');
  }
}

function fetchStoriesReview(sev){
  let nonVerified = [];
  let verified = [];
  let rejected = [];
  populateStoryLoadingAdmin();
  const fetchWebstoryData = async () =>{
    let what = sev;
      const url = '/.ht/API/webstories.php';
      var encyDat = {
      'purpose' : 'adminFetching'
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
          let dataJSON = data.message;
          let parsedJSON = JSON.parse(dataJSON);
          for (var i = 0; i < parsedJSON.length; i++) {
            var status = JSON.parse(parsedJSON[i][10]);

            if (status.status == 'none') {
              nonVerified.push(parsedJSON[i]);
            } else if (status.status == 'true') {
              verified.push(parsedJSON[i]);
            } else if (status.status == 'false') {
              rejected.push(parsedJSON[i]);
            }
          }
          if (what == 'none') {
            showStories(nonVerified, what);
          }else if (what == 'true') {
            showStories(verified, what)
          }else if (what == 'false') {
            showStories(rejected, what);
          }
        }else{
          alert(data.message);
        }
      }else{
          alert('Problem 2');
      }
  }
  fetchWebstoryData();
}

function showStories(x, what){
  if (document.getElementById('reviewStoriesDiv')) {
      document.getElementById('reviewStoriesDiv').innerHTML = '';
  }
  if (x.length) {
    for (var i = 0; i < x.length; i++) {
    var layers = JSON.parse(x[i][7]);
    var image = layers.layers.L0.media.url;
    var title = layers.metaData.title;
    var url = layers.metaData.url;
    var storyID = x[i][1];
    var username = x[i][0];
    var color;
    var actions;
    if (what == 'none') {
      color = 'orange';
      actions = `
      <div class="buttons" id="rejectStoryN${i}" onclick="storyAction('reject', 'rejectStoryN${i}', '${i}', '${storyID}')"> <i class="fa-solid fa-xmark"></i></div>
      <div class="buttons" id="acceptStoryN${i}" onclick="storyAction('accept', 'acceptStoryN${i}','${i}', '${storyID}')"> <i class="fa-solid fa-check"></i></div>
      `;
    }else if (what == 'true') {
      color = 'limegreen';
      actions = `
      <div class="buttons" id="rejectStoryT${i}" onclick="storyAction('reject', 'rejectStoryT${i}','${i}', '${storyID}')"> <i class="fa-solid fa-xmark"></i></div>
      `;
    }else if (what == 'false') {
      actions = `
      <div class="buttons" id="acceptStoryF${i}" onclick="storyAction('accept', 'acceptStoryF${i}','${i}', '${storyID}')"> <i class="fa-solid fa-check"></i></div>
      `;
      color = 'red';
    }
      if (document.getElementById('reviewStoriesDiv')) {
        document.getElementById('reviewStoriesDiv').innerHTML += `
          <div class="contentTopics">
            <div class="storyBox" id="storybox${i}">
              <div class="visibleArea">
                <div class="imageDiv">
                  <div style="width:100%; height:100%; overflow:hidden; border-radius:5px;">
                    <img src="${image}" alt="" style="object-fit:cover; width:100%; height:100%">
                  </div>
                  <div class="storyStatus" id="storyStatus${i}">
                    <i class="fa-solid fa-earth" style="color:${color};"></i>
                  </div>

                </div>
                <div class="otherInfoDiv">
                  <div class="title">
                    <span>${title}</span>
                  </div>
                  <div class="about">
                    <div class="buttons" onclick="viewStoryAdmin('${url}')"> <i class="fa fa-eye"></i></div>
                    <div class="buttons" onclick="editStoryRe('${storyID}','${username}')"><i class="fa fa-pen-to-square"></i></div>
                    ${actions}
                </div>
                </div>
              </div>
            </div>
          </div>
          `;
      }
    }
  }else{
    document.getElementById('reviewStoriesDiv').innerHTML = '';
    var text = '';
    if (what == 'none') {
      text = 'No Story to Verify';
    }else if (what == 'true') {
      text = 'No verified story';
    }else if (what == 'false') {
      text = 'No rejected story';
    }
    document.getElementById('reviewStoriesDiv').innerHTML = `
    <div class="noUpload" id="noUploads">
      <div><i class="fa fa-circle-exclamation fa-xl"></i></div>
      <div> <p>${text}</p></div>
    </div>
    `;
  }

}

function editStoryRe(id, un){
  console.log(un);
  if (un == currentUsername) {
    window.open(`/create/?type=webstory&ID=${id}`, '_blank');
  }else{
    window.open(`/create/?editor=Admin&type=webstory&username=${un}&ID=${id}`, '_blank');
  }

}
function viewStoryAdmin(link){
    window.open(`/webstories/${link}`, '_blank');
}

function storyAction(action, elementID, id, storyID){
  var element = document.getElementById(`${elementID}`);
  element.innerHTML = '<div class="spinner" style="margin-right:0px"></div>';
  const storyActionInside = async () =>{
      const url = '/.ht/API/webstories.php';
      var encyDat = {
      'purpose' : 'adminStoryAction',
      'storyID' : `${storyID}`,
      'action':  `${action}`
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
          if (action == 'reject') {
            element.innerHTML = '<i class="fa-solid fa-xmark"></i>';
          }else if (action == 'accept') {
            element.innerHTML.innerHTML = '<i class="fa-solid fa-check"></i>';
          }
          location.reload();
        }else{
          alert(data.message);
        }
      }else{
        alert('Server Problem');
      }
    }
    storyActionInside();
}


function shuffleStoryType(){
  var type = document.getElementById('shuffleStoryType');
  fetchStoriesReview(`${type.value}`);
  if (type.value == 'true') {
    const currentURL = new URL(window.location);
    currentURL.searchParams.set('nav', 'verify');
    window.history.replaceState({}, '', currentURL);
  }else if (type.value == 'false') {
    const currentURL = new URL(window.location);
    currentURL.searchParams.set('nav', 'rejected');
    window.history.replaceState({}, '', currentURL);
  }else{
    const currentURL = new URL(window.location);
    currentURL.searchParams.set('nav', 'none');
    window.history.replaceState({}, '', currentURL);
  }
}
function populateStoryLoadingAdmin() {
    var webstories = document.getElementById('reviewStoriesDiv');
    if (webstories) {
      webstories.innerHTML = `
      <div class="webstory loading" style="height:130px; margin-top: 10px;">
           <div class="fading-div" >
           </div>
           <div class="options" style="border:0px" >
             <div class="fading-div" style="margin:0;" >
             </div>
            </div>
       </div>

       <div class="webstory loading" style="height:130px; margin-top: 10px;">
            <div class="fading-div" >
            </div>
            <div class="options" style="border:0px" >
              <div class="fading-div" style="margin:0;" >
              </div>
             </div>
        </div>

        <div class="webstory loading" style="height:130px; margin-top: 10px;">
             <div class="fading-div" >
             </div>
             <div class="options" style="border:0px" >
               <div class="fading-div" style="margin:0;" >
               </div>
              </div>
         </div>
      `;
    }
}
