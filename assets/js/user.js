
let cookieExist = (document.cookie.match(/^(?:.*;)?\s*UID\s*=\s*([^;]+)(?:.*)?$/)||[,null])[1];
let userID;
if (cookieExist != null) {
    userID = str_obj(document.cookie).UID;
}else{
    userID = '';
}

function cancelUpdatePopup() {
    $('.profileUpdateShade').css('display', 'none');
     enableScroll();

}
function enableUpdatePopup(){
    $('.profileUpdateShade').css('display', 'flex');
    disbaleScroll();
    removeOptions();
}

function updateDOBndGender(){
    var DOB = document.querySelector('#updateDOB').value;
    var Gender = document.querySelector('#updateGender').value;
    var errorMessage = document.querySelector('#errorMessage');
    var dob = false;
    var gen = false;
    if(Gender.length < 1){
        errorMessage.style.display = 'inline-block';
        errorMessage.innerHTML = 'Please select gender';
    }else if (Gender == 'Male' || Gender == 'Female' || Gender == 'Others') {
        if (DOB != '') {
            if (isFiveYearsOld(DOB)) {
                errorMessage.style.display = 'none';
                document.querySelector('#updateDOBndGender').innerHTML = `Updating...`;
                updateDOBndGender();
                async function updateDOBndGender(){
                    const logUrl = `/.htactivity/API/updateDetails.php?gender=${Gender}&DOB=${DOB}`;
                    const response = await fetch(logUrl);
                    var data = await response.json();
                    if (data.Result) {
                        document.querySelector('#updateDOBndGender').innerHTML = `Updated`;
                        location.reload();
                    }else{
                        errorMessage.style.display = 'inline-block';
                        errorMessage.innerHTML = 'Not Updated';
                    }
                }

            }else{
                errorMessage.style.display = 'inline-block';
                errorMessage.innerHTML = 'Must Greater then 5 Yrs';
            }
        }else{
            errorMessage.style.display = 'inline-block';
            errorMessage.innerHTML = 'Please Enter DOB';
        }
    }else{
        errorMessage.style.display = 'inline-block';
        errorMessage.innerHTML = 'Incorrect Gender given';
    }


    
}


function disbaleScroll() {
    // Get the current Y scroll position
    var scrollY = window.pageYOffset || document.documentElement.scrollTop;
    // Set the body to hide overflow and record the previous scroll position
    document.body.style.overflow = 'hidden';
    document.body.dataset.scrollY = scrollY;
  }
  
  // Enable scrolling on the webpage
  function enableScroll() {
    // Get the previous Y scroll position
    var scrollY = parseInt(document.body.dataset.scrollY || '0');
    // Remove the overflow style from the body
    document.body.style.overflow = '';
    // Scroll back to the previous position
    window.scrollTo(0, scrollY);
  }


function isFiveYearsOld(dateString) {
    var inputDate = new Date(dateString);
    var today = new Date();
    var tenYearsAgo = new Date().setFullYear(today.getFullYear() - 5);
    return inputDate <= tenYearsAgo;
  }


isUserlogged(userID);
async function isUserlogged(userID){
    const logUrl = `/.htactivity/API/checkUser.php`;
    const response = await fetch(logUrl);
    var userLog = await response.json();
    if (userLog.Result) {
        let PID = userLog.message.PID;
        let NAME = userLog.message.NAME;
        getNotifications(PID);
        async function getNotifications(PID){
            const logUrl = '/.htactivity/API/getNotifications.php';
            var encyDat = {
                'ePID' : `${PID}`
            };
            const response = await fetch(logUrl, {
                method: 'post',
                headers: {
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify(encyDat)
              });
            var notificationData = await response.json();

            
            var notifiCount = notificationData.length;
            if (notifiCount < 4) {
                $('#noti-nav').css('bottom', 'auto');
            }else{
                $('#noti-nav').css('bottom', '5px');
            }

            var nonRead = [];
            for (let i= 0; i < notifiCount; i++) {
                if (notificationData[i].isRead == '0') {
                    nonRead[i] = notificationData[i];
                }
            }

            // hide and show notification badge based on notifications
            var nonReadCount = nonRead.length;
            if (nonReadCount > 0) {
                if (nonReadCount > 100) {
                    $('#notiCount').css('width', 'auto');
                }
                $('#notiCount').css('display', 'flex');
                $('#notiCount').html(nonReadCount);
            }else{
                $('#notiCount').css('display', 'none')
            }
            // ********* //

            let notificationHTML = '';
            if (notificationData.length > 5) {
                notificationData.length = 5;
            }
            for (let g = 0; g < notificationData.length; g++) {
                let str = notificationData[g].title;
                if (str.includes("${NAME}")) {
                    str = str.replace("${NAME}", NAME);
                } else if (str.includes("${USERNAME}")) {
                    str = str.replace("${USERNAME}", USERNAME);
                }
                var isRead = Boolean(notificationData[g].isRead);
                if(isRead){
                    var showDot = `<i id="markRead" class="fa fa-circle-dot"></i>`;
                }else{
                    var showDot = `<i></i>`;
                }

                let url = notificationData[g].url;
                var aTag;
                if(url == 'update'){
                    aTag = `onclick="enableUpdatePopup()"`;
                }else{
                    aTag = `href="${url}"`;
                }
                let srcImage;
                if (notificationData[g].image != null) {
                    srcImage = notificationData[g].image;
                }else{
                    srcImage = "/assets/img/favicon2.jpg";
                }
                notificationHTML += `
                <div class="notification" id="notification">
                    <a ${aTag}>
                        <img class="image" src="${srcImage}">
                        <div class="body">
                            <p class="noti-parts title"> ${str}</p>
                            <span class="noti-parts time">${timeAgo(notificationData[g].time)}</span>
                        </div>
                        ${showDot}
                    </a>
                    
                </div>`;
            }
            document.getElementById('notifications').innerHTML = notificationHTML;
            

           
        }
        getNotifications(userID).then(() => 
        {
            // Update styles
            styleUpdate();
        });
    }
}

function timeAgo(timestamp) {
    const seconds = Math.floor((new Date() - timestamp * 1000) / 1000);

    let interval = Math.floor(seconds / 31536000);
    if (interval > 1) {
      return `${interval} years ago`;
    }
    if (interval === 1) {
      return "1 year ago";
    }
    interval = Math.floor(seconds / 2592000);
    if (interval > 1) {
      return `${interval} months ago`;
    }
    if (interval === 1) {
      return "1 month ago";
    }
    interval = Math.floor(seconds / 86400);
    if (interval > 1) {
      return `${interval} ${interval === 1 ? "day" : "days"} ago`;
    }
    if (interval === 1) {
      return "1 day ago";
    }
    interval = Math.floor(seconds / 3600);
    if (interval > 1) {
      return `${interval} ${interval === 1 ? "hour" : "hours"} ago`;
    }
    if (interval === 1) {
      return "1 hour ago";
    }
    interval = Math.floor(seconds / 60);
    if (interval > 1) {
      return `${interval} ${interval === 1 ? "minute" : "minutes"} ago`;
    }
    if (interval === 1) {
      return "1 minute ago";
    }
    return `${Math.floor(seconds)} seconds ago`;
  }
  
  
  


