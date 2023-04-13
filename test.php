<!--  -->

<?php
//    66bfc5265202308b00cb20ef163cdb3b
include '../secrets/AUTH.php';

$d = new AUTH();

$dat = 'lhwP6SeefDaj70%2B%2Bqru3eB3WyuwjmKg%2BSKbnbYvAdGsdjQ%3D%3D';
$data = $d->decrypt($dat);
echo $data;


There is one main div which is fitted in the center of body
in the main div there are 30 smalls divs with same width 252px and varies or same in height from 300px to 700px
the smalls divs are adjusted in a way that if screen size changes they will adjust approperaitaly
?>