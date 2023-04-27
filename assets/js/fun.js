
window.onload = function(){
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
 
}



function toggleMode(){
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
    $('.notification').css('box-shadow',' 0 0 1px 0 rgb(218,218,218)');
    $('header').css('border-bottom', '1px solid rgb(218,218,218)');
    $('.dropdowns .menus a, .noti-parts').css('color', 'rgb(231, 231, 231)');
    $('.dropdowns .menu-head span, .dropdowns .menus').css('color', 'rgb(231, 231, 231)');
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
    $('.notification').css('box-shadow',' 0 0 1.5px 0 rgb(52 52 52)');
    $('#accounts').css('background-color','white');
    $('.dropdowns .menus a, .noti-parts').css('color', 'rgb(32, 33, 35)');
    $('.dropdowns .menu-head span, .dropdowns .menus').css('color', 'rgb(32,33,35)');
    $('.dropdowns .menu-head').css('border-color', 'rgb(32, 33, 35)');
    $('.dropdowns .menu-head').css('color', 'rgb(32, 33, 35)');
    var toggleMode = document.querySelector('#toggleMode');
    toggleMode.classList.remove('fa-toggle-on');
    toggleMode.classList.add('fa-toggle-off');
    $('#toggleMode').css('color','rgb(32,33,35)');
   
}



let settingState = document.getElementById('settings');
let notification = document.getElementById('noti-nav');
let accountsState = document.getElementById('accounts');
let overlay = document.getElementById('opt-overlay');
let accountIcon = document.getElementById('accountIcon');
let mSpinner =  document.getElementById('MenuSpinner');
let profileImage = document.getElementById('profileImage');
let advOptions = document.getElementById('advOptions');


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
    }else if(profileImage.style.display == 'block'){
        profileImage.style.display == 'block
    }else if(advOptions.style.display == 'block'){
        advOptions.style.display = 'none';
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
    if(notification.style.display == 'none'){
        notification.style.display = 'block';
        overlay.style.display = 'block';
    }else{
        notification.style.display = 'none';
    }
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

function toggleAdmin() {
    if(advOptions.style.display == 'none'){
        advOptions.style.display = 'block';
        overlay.style.display = 'block';
        updateNeeded();
    }else{
        advOptions.style.display = 'none';
    }
}

function updateNeeded() {
    if (gitIsUpdated) {
        $('#RefreshIcon, #refHard, #refStyle, #HPicon').css('color', 'grey');
        $('#refHard span').html('Repo Pulled');
        $('#refStyle span').html('Style Updated');
        $('#refHard, #refStyle').hover(function(){
            $(this).css('cursor', 'default');
        });
        
    }else {
        $('#RefreshIcon, #refHard, #refStyle, #HPicon').css('color', 'lime');
        
    }
    
}

function toggleProfile() {
    if(profileImage.style.display == 'none'){
        profileImage.style.display = 'block';
      overlay.style.display = 'block';
    }else{
        profileImage.style.display = 'none'; 
    }
    
}
