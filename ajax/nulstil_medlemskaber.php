<?php


require_once("../functions.php");

$query = mysql_query("UPDATE hbf_brugere SET opdateret_medlemskab = '0' WHERE deaktiv != '1'") or die(mysql_error());
echo "done";
?>
