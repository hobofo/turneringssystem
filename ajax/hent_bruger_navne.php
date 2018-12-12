<?php
require_once("../functions.php");
	$result = mysqli_query($link,"SELECT navn, telefon FROM hbf_brugere WHERE deaktiv != 1") or die(mysqli_error($link));
	$returnString = "";
	while ($row = mysql_fetch_array($result)){
    	$returnString = $returnString . $row['navn'] . ' - ' . $row['telefon'] . ',';
	}
	echo $returnString;
?>