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

function fetchStoriesReview(){
  populateStoryLoadingAdmin();
  
}

fetchStoriesReview();

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
