<!--  -->

<?php
//    66bfc5265202308b00cb20ef163cdb3b
include '../secrets/AUTH.php';

$d = new AUTH();

$dat = 'lhwP6SeefDaj70%2B%2Bqru3eB3WyuwjmKg%2BSKbnbYvAdGsdjQ%3D%3D';
$data = $d->decrypt($dat);
echo $data;
?>