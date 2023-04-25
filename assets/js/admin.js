window.onload = function(){
    // 
    if (gitIsUpdated) {
        // No need to update git
        $('#RefreshIcon, #refHard, #refStyle, #HPicon').css('color', 'grey');
        
    }else {
        $('#RefreshIcon, #refHard, #refStyle, #HPicon').css('color', 'aquamarine');
    }
}