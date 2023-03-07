<?php
session_start();
setcookie("RMM", "", time()-3600, "/");
  if (isset($_COOKIE['AID'])) {
    unset($_SESSION['LOGGED_ADMIN']);
  }elseif (isset($_COOKIE['UID'])) {
    unset($_SESSION['LOGGED_USER']);
  }
  setcookie("authStatus","Successfully logged Out", time()+10, '/');
  header("Location: /accounts/profile");
?>