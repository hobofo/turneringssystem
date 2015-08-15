<?php
require_once("../functions.php");

$kamp_id = $_GET["kamp"];
$bord_id = $_GET["bord"];
//$query = mysql_query("SELECT * FROM hbf_kampe where kamp_id  = '$kamp_id'") or die(mysql_error());
//$row = mysql_fetch_array($query);
//$turnering = hentturnering();
$i = 0;
$turnering = hentturnering();
$turnering_id = $turnering["turnering_id"];
//echo $turneringsid;

$query = mysql_query("UPDATE hbf_kampe SET bord = '', startet = '' WHERE turnerings_id = '$turnering_id' AND bord = '$bord_id' AND vinder = ''")or die(mysql_query());
$query = mysql_query("UPDATE hbf_kampe SET bord = '$bord_id', startet = now() WHERE turnerings_id = '$turnering_id' AND kamp_id = '$kamp_id'")or die(mysql_query());
?>
Kamp startet på <?=settingNumberToName('borde',$bord_id)?>