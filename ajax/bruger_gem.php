<?php
require_once("../functions.php");


$bruger_id = $_GET["id"];
$telefon = $_POST["telefon"];
$navn = $_POST["navn"];
if($_POST["sms"] == 'false') {
	$sms = 0;
} else {
	$sms = 1;
}
if ($_POST["medlemskab"] == 'false') {
	$medlem = 0;
} else {
	$medlem = 1;
}
if($telefon != ""){
    $query = mysql_query("UPDATE hbf_brugere SET telefon = '".$telefon."', navn = '".$navn."', opdateret_medlemskab = '".$medlem."', modtage_sms = '".$sms."' WHERE bruger_id = '".$bruger_id."'") or die(mysql_error());
    if($medlem == '1' && $query)
    	$query = mysql_query("INSERT INTO hbf_medlemskaber (bruger_id) VALUES ('".$bruger_id."')");
    echo "Brugeren er opdateret";
}

