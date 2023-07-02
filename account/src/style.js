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


function showImage(path){
    var showImageDiv = document.getElementById('imageShowDiv');
    if (showImageDiv.style.display == 'none') {
      showImageDiv.style.display = 'flex';
      var showContainer = document.querySelector('#imageShowDiv .imageContainer');
      showContainer.innerHTML = `<i class="fa fa-times fa-xl" onclick="removeImage()"></i>
                                 <img src="${path}" alt=""></img>`;
      disbaleScroll();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    }
  }
  
  function showVideo(path){
    var showImageDiv = document.getElementById('imageShowDiv');
    if (showImageDiv.style.display == 'none') {
      showImageDiv.style.display = 'flex';
      var showContainer = document.querySelector('#imageShowDiv .imageContainer');
      showContainer.innerHTML = `<i class="fa fa-times fa-xl" onclick="removeImage()"></i>
                                 <video controls controlsList="nodownload"> <source src="${path}" type="video/mp4"></vide>`;
      disbaleScroll();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
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