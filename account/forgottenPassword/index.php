<?php
if(!isset($_SESSION)){session_start();}
$_SERVROOT = '../../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include "../../.ht/controller/VISIT.php";
$VisitorActivity = new VisitorActivity();
$version = $VisitorActivity->VERSION;
$version = implode('.', str_split($version, 1));
$userData = new getLoggedData();
$adminLogged = $userData->adminLogged;
$userLogged = $userData->userLogged;
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
    <meta data-rh="true" property="og:title" content="Recover Forgotten Password">
    <meta data-rh="true" property="twitter:title" content="Recover Forgotten Password">
    <meta data-rh="true" property="og:description" content="Fastreed is a Webstories creating and publishing website. Fastreed allow everyone to Read, Write and Share their ideas and knowledge with the world via webstories">
    <meta data-rh="true" property="twitter:description" content="Fastreed is a Webstories creating and publishing website. Fastreed allow everyone to Read, Write and Share their ideas and knowledge with the world via webstories">
    <meta data-rh="true" property="og:image" content="https://www.fastreed.com/favicon.ico">
    <meta data-rh="true" property="twitter:image" content="https://www.fastreed.com/favicon.ico">
    <meta data-rh="true" property="og:url" content="https://www.fastreed.com/account/forgottenPassword/index.php">
    <meta data-rh="true" property="og:type" content="website">
    <!-- <meta name="author" content=""> -->
    <link data-rh="true" rel="canonical" href="https://www.fastreed.com/account/forgottenPassword/index.php">
    <title>Recover Forgotten Password</title>
    <link rel="stylesheet" href="../sign/style.css">
  </head>
  <body>
    <div class="auth wrapper">
         <div class="title-text">
           <div class="title signup">Recover Forgotten Password</div>
         </div>
         <div class="form-container">
           <div class="form-inner">

             <!-- Log in -->
             <div class="form login">
               <div  class="otpMessage">Enter email address or Username to recover password</div>
               <div id="otpError" class="errorMessage"></div>
               <div class="field">
                 <input id="emailUsername" type="text" placeholder="Email or Username" required>
               </div>
               <div class="field btn">
                 <div class="btn-layer"></div>
                 <button id="confirmButton" class="submit" onclick="sendOTP()">Recover Password</button>
               </div>
             </div>

           </div>
         </div>
       </div>
  </body>
  <script src="function.js?v= <?php echo $version;?>"charset="utf-8"></script>
</html>
