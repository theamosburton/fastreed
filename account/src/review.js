function storyAction(x, y){
  let rejectButton =document.getElementById(`rejectStory${y}`);
  let acceptButton =document.getElementById(`acceptStory${y}`);
  if (x == 'accept') {
    acceptButton.innerHTML = '<div class="spinner" style="margin-right:0px"></div>';
    rejectButton.removeAttribute('onclick');
  }else if (x == 'reject'){
    rejectButton.innerHTML = '<div class="spinner" style="margin-right:0px"></div>';
    acceptButton.removeAttribute('onclick');
  }
}
let nonVerified = [];
let verified = [];
let rejected = [];
function fetchStoriesReview(sev){
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
              nonVerified[i] = parsedJSON[i];
            }else if (status.status == 'true') {
              verified[i] = parsedJSON[i];
            }else if (status.status == 'false') {
              rejected[i] = parsedJSON[i];
            }
          }
          console.log(nonVerified);
          if (what == 'none') {
            showStories(nonVerified, what);
          }else if (what == 'true') {
            showStories(verified, what)
          }else if (what == 'false') {
            showStories(rejected, what);
          }
          console.log(what);
        }else{
          console.log(data.message);
        }
      }else{
          console.log('Problem 2');
      }
  }
  fetchWebstoryData();
}

function showStories(x, what){
  // var x = [];
  // for (let i = 0; i < originalArray.length; i++) {
  //   x[i] = originalArray[i];
  // }
  console.log(x);
  document.getElementById('reviewStoriesDiv').innerHTML = '';
  if (x.length) {
    for (var i = 0; i < x.length; i++) {
    var layers = JSON.parse(x[i][7]);
    var image = layers.layers.L0.media.url;
    var title = layers.metaData.title;
    var url = layers.metaData.url;
    var storyID = x[i][1];
    var username = x[i][0];
    document.getElementById('reviewStoriesDiv').innerHTML += `
      <div class="contentTopics">
        <div class="storyBox" id="storybox${i}">
          <div class="visibleArea">
            <div class="imageDiv">
              <img src="${image}" alt="">
              <div class="storyStatus" id="storyStatus${i}">
                <i class="fa-solid fa-earth" style="color:orange;"></i>
              </div>

            </div>
            <div class="otherInfoDiv">
              <div class="title">
                <span>${title}</span>
              </div>
              <div class="about">
                <div class="buttons" onclick="viewStoryAdmin('${url}')"> <i class="fa fa-eye"></i></div>
                <div class="buttons" onclick="editStoryRe('${storyID}','${username}')"><i class="fa fa-pen-to-square"></i></div>
                <div class="buttons" id="rejectStory${i}" onclick="storyAction('reject', '${i}', '${storyID}')"> <i class="fa-solid fa-xmark"></i></div>
                <div class="buttons" id="acceptStory${i}" onclick="storyAction('accept', '${i}', '${storyID}')"> <i class="fa-solid fa-check"></i></div>
            </div>
            </div>
          </div>
        </div>
      </div>
      `;
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

function storyAction(action, id, storyID){
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
            document.getElementById(`rejectStory${id}`).innerHTML = '<i class="fa-solid fa-xmark"></i>';
            rejected.unshift(nonVerified[id]);
            nonVerified.splice(id, 1);
          }else if (action == 'accept') {
            document.getElementById(`acceptStory${id}`).innerHTML = '<i class="fa-solid fa-check"></i>';
            accepted.unshift(nonVerified[id]);
            nonVerified.splice(id, 1);
          }
        }else{
          console.log(data);
        }
      }else{

      }
    }
    storyActionInside();
}
fetchStoriesReview('none');
function shuffleStoryType(){
  var type = document.getElementById('shuffleStoryType');
  fetchStoriesReview(`${type.value}`);
}
function populateStoryLoadingAdmin() {
    var webstories = document.getElementById('reviewStoriesDiv');
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
