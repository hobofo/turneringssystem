<?php
require_once("../functions.php");

$turneringsid = $_GET["id"];
$antalhold = $_GET["antalhold"];
$max = floor($antalhold);
$antalmulige = floor($antalhold/2);
$i = 0;

//$query = mysql_query("SELECT * FROM hbf_spillere where turnering_id = '$turneringsid' AND primaer = '1' AND medspiller <> ''") or die(mysql_error());

//$antal = mysql_num_rows($query);
//$antalmulige = floor($antal/3);
//$overskud = $antal-floor($antal/3)*3;
?>

<div class="slider" data-connect="slider_connect" data-value="0" data-range="min" data-max="<?=$antalmulige;?>" data-min="1"></div>
<input type="number" class="integer" id="slider_connect">