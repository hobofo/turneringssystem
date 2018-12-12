<?php


require_once("../functions.php");

$query = mysqli_query($link,"UPDATE hbf_brugere SET opdateret_medlemskab = '0' WHERE deaktiv != '1'") or die(mysqli_error($link));
echo "done";
?>
