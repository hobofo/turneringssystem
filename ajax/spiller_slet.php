<?php
require_once("../functions.php");
$turneringsid = $_POST["turneringsid"];
$spillerid = $_POST["spillerid"];
$typeid = $_POST["typeid"];

// Henter spillerinfo
$spillerinfo = spillerinfo($spillerid);

$spiller = $spillerinfo["spiller"];
$medspiller =  $spillerinfo["medspiller"];

$sql = "DELETE FROM hbf_spillere WHERE spiller in ('$spiller','$medspiller') and medspiller in ('$spiller','$medspiller') ";

mysql_query($sql) or die(mysql_error());


echo "Holdet er fjernet";
?>
