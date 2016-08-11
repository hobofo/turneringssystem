<?php


require_once("../functions.php");

$telefon = $_POST["telefon"];
$navn = $_POST["navn"];

$query = mysql_query("SELECT * FROM hbf_brugere where telefon = '$telefon'") or die(mysql_error());
if(mysql_num_rows($query) == 1){
    $row = mysql_fetch_array($query);
    if($row["deaktiv"] == 1) {
    	mysql_query("UPDATE hbf_brugere SET deaktiv = 0 WHERE telefon = '$telefon'") or die(mysql_error());
    	echo "$navn var deaktiveret, men er nu aktiv igen.";
    }
} else {
    $query = mysql_query("INSERT INTO hbf_brugere (telefon,navn) values ('$telefon','$navn')") or die(mysql_error());
    echo "$navn er oprettet som bruger. Velkommen!";
}

?>


