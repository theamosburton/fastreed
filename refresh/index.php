<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>FastReed - Refresh</title>
  </head>
  <body>
    <?php
    //make sure to make the shell file executeable first before running the shell_exec function
    $output = shell_exec('./shell-script.sh');
    chdir($old_path);
    
    echo $output;
     ?>
  </body>
</html>