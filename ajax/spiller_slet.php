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

mysqli_query($GLOBALS['link'],$sql) or die(mysqli_error($GLOBALS['link']));


echo "Holdet er fjernet";
?>
