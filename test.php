<!--  -->

<?php
//    66bfc5265202308b00cb20ef163cdb3b
include '../secrets/AUTH.php';

$d = new AUTH();
$dat = 'VkdVc2JZb2s5cHh0QnVXeC9BclE0bk9LOjqgZBUxyCinbHHrlnucox6g';
$data = $d->decrypt($dat);
echo $data;
?>