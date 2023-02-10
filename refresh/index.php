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



     $output = exec("git pull fastreed mai");
     echo "<pre>$output</pre>";
     ?>
  </body>
</html>