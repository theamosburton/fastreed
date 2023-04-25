window.onload = function(){
    // 
    if (gitIsUpdated) {
        // No need to update git
        $('#RefreshIcon').css('color', 'grey');
        $('#HPicon').css('color', 'grey');
        
    }else {
        $('#RefreshIcon').css('color', 'aquamarine');
        $('#HPicon').css('color', 'aquamarine');
    }
}