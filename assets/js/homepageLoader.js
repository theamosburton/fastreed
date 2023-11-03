class LoadStories {
  constructor() {
    this.showLoading();
    this.loadLatestStories();
  }

  showLoading(){
    var storiesDiv = document.getElementById('storiesDiv');
    var mediumCard =`<div class="f-card f-card_medium fading-div" style="border-radius:20px; min-height: 318px;"></div>`;
    var smallCard =`<div class="f-card f-card_small fading-div" style="border-radius:20px; min-height: 198px;"></div>`;
    var largeCard = `<div class="f-card f-card_large fading-div" style="border-radius:20px; min-height: 418px;"></div>`;
    storiesDiv.innerHTML = mediumCard +smallCard + mediumCard + largeCard + mediumCard +mediumCard + largeCard + smallCard + smallCard;
  }

  async loadLatestStories(){
      const url = '/.ht/API/showWebstories.php';
      var encyDat = {
        'type':'lastest'
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
          console.log(parsedJSON);
        }else{
          alert(data.message);
        }
      }else{
          alert('Problem 2');
      }
  }
}
new LoadStories();
