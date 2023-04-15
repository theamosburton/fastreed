let settingState = document.getElementById('settings');
let optionsState = document.getElementById('options');
// let userState = document.getElementById('user');
let overlay = document.getElementById('opt-overlay');

function toggleUser(){

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
        overlay.style.display = 'block';
        settingState.style.display = 'block';
    }else{
        settingState.style.display = 'none';
    }
}
function toggleOptions(){

}