<?php


require_once("../functions.php");

$telefon = $_POST["telefon"];
$navn = $_POST["navn"];

$query = mysql_query("SELECT * FROM hbf_brugere where telefon = '$telefon'") or die(mysql_error());
if(mysql_num_rows($query) > 0){
    echo "En bruger med dette telefonnummer findes allerede";
} else {
    $query = mysql_query("INSERT INTO hbf_brugere (telefon,navn) values ('$telefon','$navn')") or die(mysql_error());
    echo "$navn er oprettet som bruger. Velkommen!";
}

?>


