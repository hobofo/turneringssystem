<?php
require_once("../functions.php");
	$result = mysqli_query($link,"SELECT navn, telefon FROM hbf_brugere WHERE deaktiv != 1") or die(mysql_error());
	$returnString = "";
	while ($row = mysql_fetch_array($result)){
    	$returnString = $returnString . $row['navn'] . ' - ' . $row['telefon'] . ',';
	}
	echo $returnString;
?>