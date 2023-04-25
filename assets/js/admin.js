window.onload = function(){
    const refreshURL =`/.htactivity/REFRESH.php?intent=gitIsUpdated`;
    refreshStatus(refreshURL);
    async function refreshStatus(url){
        const response = await fetch(url);
        var data = await response.json();
        isUpdated = data.Result;
        if (isUpdated) {
            // No need to update git
            $('#RefreshIcon').css('color', 'grey');
            $('#HPicon').css('color', 'grey');
            
        }else {
            $('#RefreshIcon').css('color', 'lightblue');
            $('#HPicon').css('color', 'lightblue');
        }
      }
}