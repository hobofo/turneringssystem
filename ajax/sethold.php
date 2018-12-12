<?php
require_once("../functions.php");

$spiller1 = $_GET["sp1"];
$spiller2 = $_GET["sp2"];


$result = mysqli_query($GLOBALS['link'],"SELECT * FROM hbf_spillere WHERE spiller_id = '$spiller1'");
$rowspiller1 = mysqli_fetch_array($result);

$result = mysqli_query($GLOBALS['link'],"SELECT * FROM hbf_spillere WHERE spiller_id = '$spiller2'");
$rowspiller2 = mysqli_fetch_array($result);

$turneringsid = $rowspiller1["turnering_id"];
$spiller1 = $rowspiller1["spiller"];
$spiller2 = $rowspiller2["spiller"];

$spillerid1 = $rowspiller1["spiller_id"];
$spillerid2 = $rowspiller2["spiller_id"];

$query = mysqli_query($GLOBALS['link'],"DELETE FROM hbf_spillere WHERE spiller_id = '$spillerid1' OR spiller_id = '$spillerid2'");

$bruger1 = hentbruger($spiller1);
$bruger2 = hentbruger($spiller2);
$rang = $bruger1["rangliste"] + $bruger2["rangliste"];
$query = mysqli_query($GLOBALS['link'],"INSERT INTO hbf_spillere (turnering_id,spiller,medspiller,primaer,rang) values ('$turneringsid','$spiller1','$spiller2','1',$rang)");
$query = mysqli_query($GLOBALS['link'],"INSERT INTO hbf_spillere (turnering_id,spiller,medspiller,primaer,rang) values ('$turneringsid','$spiller2','$spiller1','0',$rang)");
