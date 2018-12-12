<?php
require_once("../functions.php");

$i = 0;
$turnerings_id = $_GET["id"];

$test = mysqli_query($link,"SELECT * FROM hbf_kampe where turnerings_id = '$turnerings_id' AND type in ('f','jf') AND vinder = ''") or die(mysqli_error($link));
$antal = mysqli_num_rows($test);

if($antal < 1){
    echo "1";
} else {
    echo "0";
}
