<?php
$_SERVROOT = '../../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include "../../.ht/controller/VISIT.php";
$VisitorActivity = new VisitorActivity();
$version = $VisitorActivity->VERSION;
$version = implode('.', str_split($version, 1));
$userData = new getLoggedData();
$adminLogged = $userData->adminLogged;
$userLogged = $userData->userLogged;
if ($adminLogged || $userLogged) {
  header("Location: /");
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="public, max-age=3600">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta data-rh="true" name="keywords" content="Fastreed is a Webstories creating and publishing website. Fastreed allow everyone to Read, Write and Share their ideas and knowledge with the world via webstories">
    <meta data-rh="true" name="description" content="Fastreed is a Webstories creating and publishing website. Fastreed allow everyone to Read, Write and Share their ideas and knowledge with the world via webstories">
    <meta data-rh="true" property="og:title" content="Create or Login to fastreed account">
    <meta data-rh="true" property="twitter:title" content="Create or Login to fastreed account">
    <meta data-rh="true" property="og:description" content="Fastreed is a Webstories creating and publishing website. Fastreed allow everyone to Read, Write and Share their ideas and knowledge with the world via webstories">
    <meta data-rh="true" property="twitter:description" content="Fastreed is a Webstories creating and publishing website. Fastreed allow everyone to Read, Write and Share their ideas and knowledge with the world via webstories">
    <meta data-rh="true" property="og:image" content="https://www.fastreed.com/favicon.ico">
    <meta data-rh="true" property="twitter:image" content="https://www.fastreed.com/favicon.ico">
    <meta data-rh="true" property="og:url" content="https://www.fastreed.com/account/sign/index.php">
    <meta data-rh="true" property="og:type" content="website">
    <!-- <meta name="author" content=""> -->
    <link data-rh="true" rel="canonical" href="https://www.fastreed.com/account/sign/index.php">
    <title>Create or Login to fastreed account</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://apis.google.com/js/api.js"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XZ4YQSPFM1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-XZ4YQSPFM1');
    </script>

  </head>
  <body>
    <div class="wrapper">
         <div class="form-container">
           <div class="slide-controls">
             <input type="radio" name="slide" id="login" checked>
             <input type="radio" name="slide" id="signup">
             <label for="login" class="slide login">Login</label>
             <label for="signup" class="slide signup">Signup</label>
             <div class="slider-tab"></div>
           </div>
           <div class="form-inner">

             <!-- Log in -->
             <div class="form login">
               <div id="loginError" class="errorMessage"></div>
               <div class="field">
                 <input id="userEmailName" type="text" placeholder="Email or Username" required>
               </div>
               <div class="field">
                 <input id="loginPassword" type="password" placeholder="Password" required>
               </div>
               <div class="pass-link"><a href="../forgottenPassword/
                 ">Forgot password?</a></div>
               <div class="field btn">
                 <div class="btn-layer"></div>
                 <button id="loginButton" class="submit"  onclick="login()">Login</button>
               </div>
               <div class="signup-link">Not a member? <a href="">Signup now</a></div>


               <!-- Google Sigin in -->

 								<div class="signInWith">
                  <span>OR</span>
                  <div id="g_id_onload"
                       data-client_id="878548651441-q7db04pmge7g6vlcieepnr21j9mkj2iu.apps.googleusercontent.com"
                       data-context="use"
                       data-ux_mode="popup"
                       data-callback="onGoogleSignIn"
                       data-auto_prompt="false">
                  </div>

                  <div class="g_id_signin"
                       data-type="standard"
                       data-shape="pill"
                       data-theme="outline"
                       data-text="continue_with"
                       data-size="large"
                       data-logo_alignment="left">
                  </div>
                </div>
             </div>

             <!-- Sign Up -->
             <div  class="form signup">
                <div id="signupError" class="errorMessage"></div>
               <div class="field">
                 <input id="fullName" onkeyup="checkName()" type="text" placeholder="Full Name*" required>
               </div>
               <div class="field">
                 <input id="emailAddress" onkeyup="checkEmail()" type="text" placeholder="Email Address*" required>
               </div>
               <div class="field">
                 <input onkeyup="checkNewPassword()" id="password" type="password" placeholder="Password*" required>
               </div>
               <div class="field">
                 <input onkeyup="checkVerifyPassword()" id="passwordVerify" type="password" placeholder="Confirm password*" required>
               </div>

                <div class="signup-link">By clicking on signup you agree to fastreed <a href="">terms and conditions</a></div>

               <div class="field btn">
                 <div class="btn-layer"></div>
                 <button id="signupButton" class="submit"  onclick="sendOTP()">Signup</button>
               </div>

               <div class="signInWith">
                 <span>OR</span>
                 <div id="g_id_onload"
                       data-client_id="878548651441-q7db04pmge7g6vlcieepnr21j9mkj2iu.apps.googleusercontent.com"
                       data-context="use"
                       data-ux_mode="popup"
                       data-callback="onGoogleSignIn"
                       data-auto_prompt="false">
                  </div>

                  <div class="g_id_signin"
                       data-type="standard"
                       data-shape="pill"
                       data-theme="outline"
                       data-text="continue_with"
                       data-size="large"
                       data-logo_alignment="left">
                  </div>
               </div>
             </div>
           </div>
         </div>
       </div>
  </body>
  <script src="style.js?v= <?php echo $version;?>" charset="utf-8"></script>
  <script src="function.js?v= <?php echo $version;?>"charset="utf-8"></script>
</html>
