
window.onload = function(){
    // checking cookie mode
    let darkMode;
    let cookieExist = (document.cookie.match(/^(?:.*;)?\s*DARKMODE\s*=\s*([^;]+)(?:.*)?$/)||[,null])[1];
    if (cookieExist != null) {
        darkMode = str_obj(document.cookie).DARKMODE;
    }else{
        document.cookie = "DARKMODE=true";
        darkMode = 'true';
    }

    // setting mode with respect to cookie
    if (darkMode == 'false') {
        enableLightMode();
    }else if(darkMode == 'false'){
        enableDarkMode();
    }
}



function enableDarkMode() {
    var hs = document.getElementsByTagName('style');
    for (var i=0, max = hs.length; i < max; i++) {
        hs[i].parentNode.removeChild(hs[i]);
    }
}

function enableLightMode(){
    document.head.innerHTML += `	
<style>
    body{
        background-color : rgb(218, 218, 218);
    }
    header .nav, header h1 a{
        color :rgba(32,33,35);
    }
    .cat a, .date, .f-card .fa-ellipsis-v{
        color :rgba(32,33,35);
    }

    .f-card_small .title a{
        color :rgba(32,33,35);
    }

    .options, .settings{
        border-color:#353740;
        background-color: rgb(218, 218, 218);
        color: rgba(32,33,35);
    }
    .options .menu-head span, .settings .menu-head span, .options .menus, .settings .menus, .settings .menus i, .options .menus i{
        color: rgba(32,33,35);
    } 

    .options .menu-head::after, .settings .menu-head::after {
        height: 1px;
        background: rgba(32,33,35);
    }
</style>`;
}

function toggleMode(){
    let styleTagExists;
    var head = document.querySelector('head');
    var toggleMode = document.querySelector('#toggleMode');
    // Check if it contains any <style> elements
    if (head.querySelector('style') !== null) {
        // style Tag Exists light mode enabled 
        enableDarkMode();
        toggleMode.classList.remove('fa-toggle-off');
        toggleMode.classList.add('fa-toggle-on');
        document.cookie = "DARKMODE=true";
    } else {
        // style not Tag Exists dark mode enabled 
        toggleMode.classList.remove('fa-toggle-on');
        toggleMode.classList.add('fa-toggle-off');
        enableLightMode();
        document.cookie = "DARKMODE=false";
    }
}

let settingState = document.getElementById('settings');
let optionsState = document.getElementById('s-options');
// let userState = document.getElementById('user');
let overlay = document.getElementById('opt-overlay');

function toggleUser(){

}

function str_obj(str) {
    str = str.split('; ');
    var result = {};
    for (var i = 0; i < str.length; i++) {
        var cur = str[i].split('=');
        result[cur[0]] = cur[1];
    }
    return result;
}

function removeOptions(){
    if(settingState.style.display == 'block'){
        settingState.style.display == 'none';
    }else{
        optionsState.style.display == 'none';
    }
}
function toggleSetting(){
    if(settingState.style.display == 'none'){
        if(optionsState.style.display = 'none'){
            settingState.style.display = 'block';
        }else{
            optionsState.style.display = 'none';
            settingState.style.display = 'block';
        }
        
    }else{
        settingState.style.display = 'none';
    }
}

function toggleOptions(){
    let settingState = document.getElementById('settings');
    if(optionsState.style.display == 'none'){
        if(settingState.style.display == 'none'){
            optionsState.style.display = 'block';
        }else{
            settingState.style.display = 'none';
            optionsState.style.display = 'block';
        }
    }else{
        optionsState.style.display = 'none';
    }
}