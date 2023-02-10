<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>FastReed - Refresh</title>
  </head>
  <body>
    <?php
     $output = shell_exec("git pull fastreed main");
     echo "<pre>$output</pre>";
     ?>
  </body>
</html>