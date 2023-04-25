window.onload = function(){
    if (gitIsUpdated) {
        // No need to update git
        $('#RefreshIcon').css('color', 'grey');
        $('#HPicon').css('color', 'grey');
        
    }else {
        $('#RefreshIcon').css('color', 'lightblue');
        $('#HPicon').css('color', 'lightblue');
    }
}