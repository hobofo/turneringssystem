<?php
require_once("../functions.php");

$turneringsid = $_GET["id"];
$bord = $_GET["bord"];
$explode = explode("-",$bord);
unset($explode[0]);
$borde = arraytodbarray($explode);

$opdater = mysql_query("UPDATE hbf_turnering SET borde = '$borde' WHERE turnering_id = '$turneringsid'") or die(mysql_error());

?>
