<?php
require_once("../functions.php");

$turneringsid = $_GET["id"];
$or_vinder = $_GET["p1"];
$or_finale = $_GET["p2"];
$or_semi = $_GET["p3"];
$or_kvart = $_GET["p4"];
$jay_vinder = $_GET["p5"];
$jay_finale = $_GET["p6"];
$jay_semi = $_GET["p7"];
$jay_kvart = $_GET["p8"];

$point = "{".$or_vinder."},{".$or_finale."},{".$or_semi."},{".$or_kvart."},{".$jay_vinder."},{".$jay_finale."},{".$jay_semi."},{".$jay_kvart."}";



//$type1 $antaltype1 $type2 $antaltype2
$opdater = mysqli_query($link,"UPDATE hbf_turnering SET point = '$point' WHERE turnering_id = '$turneringsid'") or die(mysql_error());

?>
