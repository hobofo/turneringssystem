<?php
require_once("../functions.php");


$bruger_id = $_GET["id"];
$telefon = $_POST["telefon"];
$navn = $_POST["navn"];
if ($_POST["medlemskab"] == 'false') {
	$medlem = 0;
} else {
	$medlem = 1;
}
if($telefon != ""){
    $query = mysql_query("UPDATE hbf_brugere SET telefon = '".$telefon."', navn = '".$navn."', opdateret_medlemskab = '".$medlem."' WHERE bruger_id = '".$bruger_id."'") or die(mysql_error());
    echo "Brugeren er opdateret";
}

