<?php
require_once("../functions.php");

$turneringsid = $_GET["id"];
$bord = $_GET["bord"];
$explode = explode("-",$bord);
unset($explode[0]);
$borde = arraytodbarray($explode);

$opdater = mysqli_query($GLOBALS['link'],"UPDATE hbf_turnering SET borde = '$borde' WHERE turnering_id = '$turneringsid'") or die(mysqli_error($GLOBALS['link']));

?>
