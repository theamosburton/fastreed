

isUserlogged();
async function isUserlogged(){
    const logUrl = `/.htactivity/API/checkUser.php`;
    const response = await fetch(logUrl);
    var userLog = await response.json();
    if (userLog.Result) {
        let PID = userLog.message.PID;
        let NAME = userLog.message.NAME;
        getNotifications()
        async function getNotifications(){
            const logUrl = `/.htactivity/API/getNotifications.php?ePID=${PID}`;
            const response = await fetch(logUrl);
            var notificationData = await response.json();
            var notifiCount = notificationData.length;
            if (notifiCount < 2) {
                $('#noti-nav').css('bottom', 'auto');
            }else{
                $('#noti-nav').css('bottom', '5px');
            }

            var nonRead = [];
            var broadCast = [];
            var completeProfile = {};
            var selfNotify = [];
            
            for (let i= 0; i < notifiCount; i++) {
                if (notificationData[i].isRead == '0') {
                    nonRead[i] = notificationData[i];
                }

                if (notificationData[i].reciever == 'public') {
                    broadCast[i] = notificationData[i];
                }

                if (notificationData[i].Purpose == 'profileCompletion') {
                    completeProfile = {
                        result: true,
                        time: notificationData[i].time
                    };
                }else{
                    completeProfile = {
                        result: false
                    };
                }

                if (notificationData[i].Purpose != 'profileCompletion' && notificationData[i].reciever != 'public') {
                    selfNotify[i] = notificationData[i];
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

            let broadcast = ' ';
            for (let j = 0; j < broadCast.length; j++) {

                broadcast[j] = `
                <div class="notification" id="notification">
                    <a href="/profile/">
                        <img class="image" src="/assets/img/favicon2.jpg">
                        <div class="body">
                            <p class="noti-parts title"> ${broadCast[j].other}
                            <span class="noti-parts time">${timeAgo(broadCast[j].time)}</span>
                        </div>
                    </a>
                </div>
            `;
            }
            let profileNotifications = ' ';
            if (completeProfile.result) {
                profileNotifications = `
                <div class="notification" id="notification">
                    <a href="/profile/">
                        <img class="image" src="/assets/img/favicon2.jpg">
                        <div class="body">
                            <p class="noti-parts title"> Hello, <b>${NAME}!</b> Please complete your profile to enable more options.</p>
                            <span class="noti-parts time">${timeAgo(completeProfile.time)}</span>
                        </div>
                    </a>
                </div>
                `;
            }
            let selfNotifications = ' ';
            for (let k = 0; k < selfNotify.length; k++) {
                selfNotifications[k] =  `
                <div class="notification" id="notification">
                    <a href="/profile/">
                        <img class="image" src="/assets/img/favicon2.jpg">
                        <div class="body">
                            <p class="noti-parts title"> ${broadCast[k].other}
                            <span class="noti-parts time">${timeAgo(broadCast[k].time)}</span>
                        </div>
                    </a>
                </div>
            `;
            }


            
        document.getElementById('notifications').innerHTML = broadCast + profileNotifications + selfNotifications;
        }
        getNotifications().then(() => 
        {
            // Update styles
            styleUpdate();
        });
    }
}

function timeAgo(timestamp) {
    const seconds = Math.floor((new Date() - timestamp * 1000) / 1000);
    if (seconds < 60) {
      return "just now";
    }
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
      return "1 min. ago";
    }
    return `${Math.floor(seconds)} seconds ago`;
  }
  
  
  


