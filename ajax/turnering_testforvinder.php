<?php
require_once("../functions.php");

$i = 0;
$turnerings_id = $_GET["id"];

$test = mysql_query("SELECT * FROM hbf_kampe where turnerings_id = '$turnerings_id' AND type in ('f','jf') AND vinder = ''") or die(mysql_error());
$antal = mysql_num_rows($test);

if($antal < 1){
    echo "1";
} else {
    echo "0";
}
