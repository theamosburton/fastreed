<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>FastReed - Refresh</title>
  </head>
  <body>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
     echo "Git Hub Refresh";
     function execPrint($command) {
      $result = array();
      exec($command, $result);
      print("<pre>");
      foreach ($result as $line) {
          print($line . "\n");
      }
      print("</pre>");
    }
     // Print the exec output inside of a pre element
     execPrint("git pull https://github.com/mdshafiqmalik/Fastreed-v1.0.0 main");
     execPrint("git status");
     ?>
  </body>
</html>