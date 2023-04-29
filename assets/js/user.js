

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
            if (notifiCount < 3) {
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

                let srcImage;
                if (notificationData[g].image != null) {
                    srcImage = notificationData[g].image;
                }else{
                    srcImage = "/assets/img/favicon2.jpg";
                }
                notificationHTML += `
                <div class="notification" id="notification">
                    <a href="/profile/">
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
  
  
  


