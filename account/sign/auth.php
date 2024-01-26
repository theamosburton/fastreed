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
if (!isset($_SESSION['otp']) || empty($_SESSION['otp']) || !isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: /account/sign/");
}elseif ($adminLogged || $userLogged) {
    header("Location: /");
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title></title>
  </head>
  <body>
    <div class="auth wrapper">
         <div class="title-text">
           <div class="title signup">Verify Email</div>
         </div>
         <div class="form-container">
           <div class="form-inner">

             <!-- Log in -->
             <div class="form login">
               <div  class="otpMessage">We have sent a 6 digit OTP to your email address please enter to contnue</div>
               <div id="otpError" class="errorMessage"></div>
               <div class="field">
                 <input id="otpInput" type="text" placeholder="Enter six digit OTP" required>
               </div>
               <div class="pass-link"><a onclick="resendOTP()">Resend OTP</a></div>
               <div class="field btn">
                 <div class="btn-layer"></div>
                 <button id="confirmButton" class="submit" onclick="verifyEmail()">Verify</button>
               </div>
             </div>

           </div>
         </div>
       </div>
  </body>
  <script src="function.js?v= <?php echo $version;?>"charset="utf-8"></script>
</html>
