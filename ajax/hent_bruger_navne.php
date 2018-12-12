<?php
require_once("../functions.php");
	$result = mysqli_query($GLOBALS['link'],"SELECT navn, telefon FROM hbf_brugere WHERE deaktiv != 1") or die(mysqli_error($GLOBALS['link']));
	$returnString = "";
	while ($row = mysqli_fetch_array($result)){
    	$returnString = $returnString . $row['navn'] . ' - ' . $row['telefon'] . ',';
	}
	echo $returnString;
?>