<?php


require_once("../functions.php");

$bruger_id = $_GET["id"];
$query = mysqli_query($link,"UPDATE hbf_brugere SET deaktiv = '1' where bruger_id = '".$bruger_id."'") or die(mysqli_error($link));
echo "done";
?>


