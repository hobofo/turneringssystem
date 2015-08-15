<?php


require_once("../functions.php");

$bruger_id = $_GET["id"];
$query = mysql_query("UPDATE hbf_brugere SET deaktiv = '1' where bruger_id = '".$bruger_id."'") or die(mysql_error());
echo "done";
?>


