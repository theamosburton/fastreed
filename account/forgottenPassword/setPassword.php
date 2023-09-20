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
function hideEmail($email) {
    list($username, $domain) = explode('@', $email);
    $usernameLength = strlen($username);
    $charactersToHide = max(0, $usernameLength - 3);
    $hiddenPart = str_repeat('*', $charactersToHide);
    $hiddenEmail = substr($username, 0, 3) . $hiddenPart . '@' . $domain;
    return $hiddenEmail;
}
$hiddenEmail = hideEmail($_SESSION['email']);
if (!isset($_SESSION['rID'])) {
  header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../sign/style.css">
    <title></title>
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
               <div  class="otpMessage">Enter 6 digit OTP sent to: <i> <?php echo $hiddenEmail; ?> </i></div>
               <div id="otpError" class="errorMessage"></div>
               <div class="field">
                 <input id="otpInput" type="text" onkeyup="checkOTP()" placeholder="Enter 6 digit OTP" required>
               </div>

               <div class="field">
                 <input id="password" onkeyup="checkNewPassword()" type="text" placeholder="Enter New Password" required>
               </div>

               <div class="field">
                 <input id="passwordVerify" onkeyup="checkVerifyPassword()" type="text" placeholder="Confirm Password" required>
               </div>

                <div class="pass-link"><a onclick="resendOTP()">Resend OTP</a></div>
               <div class="field btn">
                 <div class="btn-layer"></div>
                 <button id="confirmButton" class="submit" onclick="resetPassword()">Reset Password</button>
               </div>
             </div>

           </div>
         </div>
       </div>
  </body>
  <script src="function.js?v= <?php echo $version;?>"charset="utf-8"></script>
</html>
