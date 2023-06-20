<?php
$filepath = 'assets/img/port13.png';
header('Content-Type: image/png');
header('Content-Length: ' . filesize($filepath));
header('Content-Disposition: inline'); // Set to inline instead of attachment
readfile($filepath);
?>