<?php


require_once("../functions.php");

$telefon = $_POST["telefon"];
$navn = $_POST["navn"];

$query = mysqli_query($link,"SELECT * FROM hbf_brugere where telefon = '$telefon'") or die(mysqli_error($link));
if(mysql_num_rows($query) == 1){
    $row = mysql_fetch_array($query);
    if($row["deaktiv"] == 1) {
    	mysqli_query($link,"UPDATE hbf_brugere SET deaktiv = 0 WHERE telefon = '$telefon'") or die(mysqli_error($link));
    	echo "$navn var deaktiveret, men er nu aktiv igen.";
    }
} else {
    $query = mysqli_query($link,"INSERT INTO hbf_brugere (telefon,navn) values ('$telefon','$navn')") or die(mysqli_error($link));
    echo "$navn er oprettet som bruger. Velkommen!";
}

?>


