styleUpdate();
function styleUpdate() {
    // checking cookie mode
    let colorMode;
    let cookieExist = (document.cookie.match(/^(?:.*;)?\s*colorMode\s*=\s*([^;]+)(?:.*)?$/)||[,null])[1];
    if (cookieExist != null) {
        colorMode = str_obj(document.cookie).colorMode;
    }else{
        document.cookie = "colorMode=dark; max-age=31104000; path=/";
        colorMode = 'true';
    }

    // setting mode with respect to cookie
    if (colorMode == 'dark') {
        enableDarkMode();
    }else if(colorMode == 'light'){
        enableLightMode();
    }
    updateNeeded();
    $('#notifications::-webkit-scrollbar-thumb').css('background-color', 'transparent');
}

function enableDarkMode() {
    $('body').css('background-color','rgb(32, 33, 35)');
}

function enableLightMode() {
    $('body').css('background-color','rgb(255, 255, 255)');
}



function toggleEditProfile(){
    var editFieldStatus = document.querySelector("#editFieldsStatus").innerHTML;
    if (editFieldStatus == '0') {
        document.querySelector("#editFieldsStatus").innerHTML = '1';
        document.querySelector("#edit_fields").style.display = 'block';
        document.querySelector("#editButton").innerHTML = 'Stop Editing Details';
    }else{
        document.querySelector("#editFieldsStatus").innerHTML = '0';
        document.querySelector("#edit_fields").style.display = 'none';
        document.querySelector("#editButton").innerHTML = 'Edit Personal Details';
    }

}