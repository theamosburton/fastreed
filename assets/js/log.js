function onGoogleSignIn(response) {
  const responsePayload = decodeJWT(response.credential);
      const loginURL =`/.htactivity/G_USER_LOGIN.php?email=${responsePayload.email}&name=${responsePayload.name}&profilePic=${responsePayload.picture}`;
      serverLogin(loginURL);
      async function serverLogin(url){
        const response = await fetch(url);
        var data = await response.json();
        islogged = data.Result;
        message = data.message;
        if (islogged) {
          let nav = document.getElementById('nav')
          document.getElementById('g_id_onload').style.display = 'none';
          document.getElementById('g_id_signin').style.display = 'none';
          document.getElementById('accountIcon').style.display = 'none';
          document.getElementById('contEmail').style.display = 'none';
          var exContent = nav.innerHTML;
          nav.innerHTML = `<img src="${responsePayload.picture}" id="profileImage" onclick="toggleProfile()">` + exContent;

          document.getElementById('accounts').innerHTML = `<div class="menu-head">
          <span class="name">My Account</span>
        </div>`;
         
          removeOptions();
        }else {
          alert("There is some Problem at our end");
        }
    }

   //  console.log("ID: " + responsePayload.sub);
   //  console.log('Full Name: ' + responsePayload.name);
   //  console.log('Given Name: ' + responsePayload.given_name);
   //  console.log('Family Name: ' + responsePayload.family_name);
   //  console.log("Image URL: " + responsePayload.picture);
   //  console.log("Email: " + responsePayload.email);
   //  console.log("Is Verified: "+ responsePayload.email_verified);
 }


 function decodeJWT(jwtToken) {
    const payload = JSON.parse(window.atob(jwtToken.split('.')[1]));
    return payload;
  }



 