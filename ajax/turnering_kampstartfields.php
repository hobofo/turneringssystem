<?php
require_once("../functions.php");

$kamp_id = $_GET["kamp_id"];
$query = mysqli_query($GLOBALS['link'],"SELECT * FROM hbf_kampe where kamp_id  = '$kamp_id'") or die(mysqli_error($GLOBALS['link']));
$row = mysqli_fetch_array($query);
$turnering = hentturnering();
$i = 0;
$borde = dbarraytoarray($turnering["borde"]);
?>



<label>Start kamp i gang på:</label>
<div style="padding:20px;">
<?
foreach($borde as $bord){
echo "<a href='javascript:startkamp($kamp_id,$bord)' class='btn'>".settingNumberToName('borde',$bord)."</a>";
}
?>
</div>
