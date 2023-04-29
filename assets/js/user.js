

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
            var nonRead = [];
            
            for (let i= 0; i < notifiCount; i++) {
                if (notificationData[i].isRead == '0') {
                    nonRead[i] = notificationData[i];
                }

                if (notificationData[i].Purpose == 'profileCompletion') {
                    var completeProfile = {
                        result: true,
                        time: notificationData[i].time
                    };
                }
            }

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

            if (completeProfile.result) {
                document.getElementById('notifications').innerHTML = `
                <div class="notification" id="notification">
                    <a href="/profile/">
                        <img class="image" src="/assets/img/favicon2.jpg">
                        <div class="body">
                            <p class="noti-parts title"> Hi <b>${NAME}!</b> Please complete your profile to enable more options.</p>
                            <span class="noti-parts time">${timeAgo(completeProfile.time)}</span>
                        </div>
                    </a>
                </div>
                `;
            }else{

            }
        }
    }else{
        // Something Went Wrong with user Log

    }
}

function timeAgo(timestamp) {
    const seconds = Math.floor((new Date() - timestamp * 1000) / 1000);
    if (seconds < 60) {
      return "Just now";
    }
    let interval = Math.floor(seconds / 31536000);
    if (interval > 1) {
        return `${interval} ${interval === 1 ? "year" : "years"} ago`;
    }
    interval = Math.floor(seconds / 2592000);
    if (interval > 1) {
        return `${interval} ${interval === 1 ? "month" : "months"} ago`;
    }
    interval = Math.floor(seconds / 86400);
    if (interval > 1) {
        return `${interval} ${interval === 1 ? "day" : "days"} ago`;
    }
    interval = Math.floor(seconds / 3600);
    if (interval > 1) {
        return `${interval} ${interval === 1 ? "hr." : "hrs."} ago`;
    }
    interval = Math.floor(seconds / 60);
    if (interval > 1) {
        return `${interval} ${interval === 1 ? "min." : "mins."} ago`;
    }
    return `${Math.floor(seconds)}s ago`;
  }
  
  


