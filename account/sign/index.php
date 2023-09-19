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
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title></title>
  </head>
  <body>
    <div class="wrapper">
         <div class="title-text">
           <div class="title login">Login Form</div>
           <div class="title signup">Signup Form</div>
         </div>
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
               <div class="pass-link"><a href="#">Forgot password?</a></div>
               <div class="field btn">
                 <div class="btn-layer"></div>
                 <button id="loginButton" class="submit"  onclick="login()">Login</button>
               </div>
               <div class="signup-link">Not a member? <a href="">Signup now</a></div>
             </div>

             <!-- Sign Up -->
             <div  class="form signup">
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
                 <button id="signupButton" class="submit"  onclick="signup()">Signup</button>
               </div>
             </div>
           </div>
         </div>
       </div>
  </body>
  <script src="style.js?v= <?php echo $version;?>" charset="utf-8"></script>
  <script src="function.js?v= <?php echo $version;?>"charset="utf-8"></script>
</html>
