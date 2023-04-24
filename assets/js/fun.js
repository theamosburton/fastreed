
window.onload = function(){

    // inject css to iframe
    // var iframe = document.querySelector('.S9gUrf-YoZ4jf iframe');
    // var innerHtmlTag = iframe.contentWindow.document.documentElement;
    // innerHtmlTag.style.backgroundcolor = 'rgb(53, 55, 64);';

    

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
    }
}

function enableDarkMode() {
    document.cookie = "colorMode=dark; max-age=31104000; path=/"; 
    $('body').css('background-color', 'rgb(32,33,35)');
    $('header .nav, header h1 a').css('color', 'rgb(218,218,218)');
    $('.cat a, .date, .f-card .fa-ellipsis-v').css('color', 'rgb(194, 194, 194)');
    $('.f-card_small .title a').css('color', 'white');
    $('.dropdowns').css({
        'background-color': '#353740',
        'border-color': 'rgb(218, 218, 218)'
    });

    $('header').css('border-bottom', '1px solid rgb(218,218,218)');
    $('.dropdowns .menus a').css('color', 'rgb(231, 231, 231)');
    $('dropdowns .menu-head span, .dropdowns .menus, .dropdowns .menus i').css('color', 'rgb(231, 231, 231)');
    $('.dropdowns .menu-head').css('border-color', 'rgb(231, 231, 231)');
    $('.dropdowns .menu-head').css('color', 'rgb(231, 231, 231)');
    var toggleMode = document.querySelector('#toggleMode');
    toggleMode.classList.remove('fa-toggle-off');
    toggleMode.classList.add('fa-toggle-on'); 
    $('#toggleMode').css('color','#8f8fed');
      
}

function enableLightMode(){
    document.cookie = "colorMode=light; max-age=31104000; path=/";
    $('body').css('background-color', 'rgb(255, 255, 255)');
    $('header .nav, header h1 a').css('color', 'rgba(32,33,35)');

    $('.cat a, .date, .f-card .fa-ellipsis-v').css('color', 'rgba(32,33,35)');

    $('.f-card_small .title a').css('color', 'rgba(32,33,35)');

    $('.dropdowns').css({
    'border-color': '#353740',
    'background-color': 'rgb(255, 255, 255)'
    });

    $('header').css('border-bottom', '1px solid #353740');

    $('#accounts').css('background-color','white');
    $('.dropdowns .menus a').css('color', 'rgb(32, 33, 35)');
    $('dropdowns .menu-head span, .dropdowns .menus, .dropdowns .menus i').css('color', 'rgb(32,33,35)');
    $('.dropdowns .menu-head').css('border-color', 'rgb(32, 33, 35)');
    $('.dropdowns .menu-head').css('color', 'rgb(32, 33, 35)');
    var toggleMode = document.querySelector('#toggleMode');
    toggleMode.classList.remove('fa-toggle-on');
    toggleMode.classList.add('fa-toggle-off');
    $('#toggleMode').css('color','rgb(32,33,35)');
   
}



let settingState = document.getElementById('settings');
let notification = document.getElementById('notifications');
let accountsState = document.getElementById('accounts');
let overlay = document.getElementById('opt-overlay');
let accountIcon = document.getElementById('accountIcon');
let mSpinner =  document.getElementById('MenuSpinner');
let profileImage = document.getElementById('profileImage');


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
    }else if(notification.style.display == 'block'){
        notification.style.display = 'none';
    }else if(accountsState.style.display == 'block'){
        accountsState.style.display = 'none';
        mSpinner.style.display = 'none';
        accountIcon.style.display = "block";
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

function toggleNotifications(){
    // if(optionsState.style.display == 'none'){
    //     optionsState.style.display = 'block';
    //     overlay.style.display = 'block';
    // }else{
    //     optionsState.style.display = 'none';
    // }
}
function toggleAccounts(){
    if(accountsState.style.display == 'none'){
        accountsState.style.display = 'block';
        overlay.style.display = 'block';
        accountIcon.style.display = 'none';
        mSpinner.style.display = 'block';
    }else{
        accountsState.style.display = 'none';
        mSpinner.style.display = 'none';
    }
}



function toggleProfile() {
    if(accountsState.style.display == 'none'){
      accountsState.style.display = 'block';
      overlay.style.display = 'block';
    }else{
        accountsState.style.display = 'none'; 
    }
    
}
