<?php
  if (isset($_COOKIE['AID'])) {
    setcookie("AID", "", time()-3600, '/');
    unset($_SESSION['ASI']);
  }elseif (isset($_COOKIE['UID'])) {
    setcookie("UID", "", time()-3600, '/');
    unset($_SESSION['USI']);
  }elseif (isset($_SESSION['USI'])) {
    setcookie("UID", "", time()-3600, '/');
    unset($_SESSION['USI']);
  }elseif (isset($_SESSION['ASI'])) {
    setcookie("AID", "", time()-3600, '/');
    unset($_SESSION['ASI']);
  }

  header("Location: /accounts/");
?>