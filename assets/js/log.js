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
          location.reload();
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



 