<?php
require_once("../functions.php");


$bruger_id = $_GET["id"];
$telefon = $_POST["telefon"];
$navn = $_POST["navn"];
if($telefon != ""){
    $query = mysql_query("UPDATE hbf_brugere SET telefon = '".$telefon."', navn = '".$navn."' where bruger_id = '".$bruger_id."'") or die(mysql_error());

    echo "Brugeren er opdateret";
}

