<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$cat1 = array(1,2,3);
$cat2 = array(4,5,6);
$cat3 = array(7,8,9,10,11,12,13);
$cat4 = array(14,15,16,17,18,19);
$cat5 = array(20,21,22);

$qcat1 = array_rand($cat1,2);
$qcat2 = array_rand($cat2,2);
$qcat3 = array_rand($cat3,2);
$qcat4 = array_rand($cat4,2);
$qcat5 = array_rand($cat5,2);

$questions = array($cat1[$qcat1[0]],$cat1[$qcat1[1]],$cat2[$qcat2[0]],$cat2[$qcat2[1]],$cat3[$qcat3[0]],$cat3[$qcat3[1]],$cat4[$qcat4[0]],$cat4[$qcat4[1]],$cat5[$qcat5[0]],$cat5[$qcat5[1]]);
shuffle($questions);

$res = json_encode($questions);
echo '{"res":'.$res.'}';

?>