let Ricon = document.getElementById('Ricon');
let HPicon = document.getElementById('HPicon');
window.onload = function(){
    const refreshURL =`/.htactivity/REFRESH.php?intent=gitIsUpdated`;
    refreshStatus(refreshURL);
    async function refreshStatus(url){
        const response = await fetch(url);
        var data = await response.json();
        isUpdated = data.Result;
        if (isUpdated) {
            $('#Ricon').css('color', 'aquamarine');
            $('#HPicon').css('color', 'aquamarine');
        }else {
            $('#Ricon').css('color', 'grey');
            $('#HPicon').css('color', 'grey');
        }
      }
}