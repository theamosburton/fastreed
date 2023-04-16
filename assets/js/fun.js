
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
    if (darkMode == 'true') {
        enableDarkMode();
    }else if(darkMode == 'false'){
        enableLightMode();
    }
}



function enableDarkMode() {
    $('body').css('background-color', 'rgb(32,33,35)');
    $('header .nav, header h1 a').css('color', 'rgb(218,218,218)');
    $('.cat a, .date, .f-card .fa-ellipsis-v').css('color', 'rgb(194, 194, 194)');
    $('.f-card_small .title a').css('color', 'white');
    $('.options, .settings').css({
        'background-color': '#353740',
        'border-color': 'rgb(218, 218, 218)'
      });

      $('.options .menu-head span, .settings .menu-head span, .options .menus, .settings .menus, .settings .menus i, .options .menus i').css('color', 'rgb(231, 231, 231)');
      $('.options .menu-head, .settings .menu-head').css('border-color', 'rgb(231, 231, 231)');
      var toggleMode = document.querySelector('#toggleMode');
      toggleMode.classList.remove('fa-toggle-off');
      toggleMode.classList.add('fa-toggle-on'); 
      document.cookie = "DARKMODE=true"; 
}

function enableLightMode(){
    $('body').css('background-color', 'rgb(218, 218, 218)');
    $('header .nav, header h1 a').css('color', 'rgba(32,33,35)');

    $('.cat a, .date, .f-card .fa-ellipsis-v').css('color', 'rgba(32,33,35)');

    $('.f-card_small .title a').css('color', 'rgba(32,33,35)');

    $('.options, .settings').css({
    'border-color': '#353740',
    'background-color': 'rgb(218, 218, 218)'
    });
    $('.options .menu-head span, .settings .menu-head span, .options .menus, .settings .menus, .settings .menus i, .options .menus i').css('color', 'rgb(32,33,35)');
    $('.options .menu-head, .settings .menu-head').css('border-color', 'rgb(32, 33, 35)');
    var toggleMode = document.querySelector('#toggleMode');
    toggleMode.classList.remove('fa-toggle-on');
    toggleMode.classList.add('fa-toggle-off');
    document.cookie = "DARKMODE=false";
}

function toggleMode(){
    let styleTagExists;
    var head = document.querySelector('head');
    var toggleMode = document.querySelector('#toggleMode');
    // Check if it contains any <style> elements
    if (toggleMode.classList.contains('fa-toggle-off')) {
        // Enable Dark Mode
        enableDarkMode();
        toggleMode.classList.remove('fa-toggle-off');
        toggleMode.classList.add('fa-toggle-on');
    } else {
        // Enable light mode 
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
        settingState.style.display = 'none';
    }else if(optionsState.style.display == 'block'){
        optionsState.style.display = 'none';
    }
    overlay.style.display = 'none';
}
function toggleSetting(){
    if(settingState.style.display == 'none'){
        settingState.style.display = 'block';
        overlay.style.display = 'block';
    }else{
        settingState.style.display = 'none';
    }
}

function toggleOptions(){
    let settingState = document.getElementById('settings');
    if(optionsState.style.display == 'none'){
        optionsState.style.display = 'block';
        overlay.style.display = 'block';
    }else{
        optionsState.style.display = 'none';
    }
}