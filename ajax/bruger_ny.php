<?php


require_once("../functions.php");

$telefon = $_POST["telefon"];
$navn = $_POST["navn"];

$query = mysqli_query($GLOBALS['link'],"SELECT * FROM hbf_brugere where telefon = '$telefon'") or die(mysqli_error($GLOBALS['link']));
if(mysqli_num_rows($query) == 1){
    $row = mysqli_fetch_array($query);
    if($row["deaktiv"] == 1) {
    	mysqli_query($GLOBALS['link'],"UPDATE hbf_brugere SET deaktiv = 0 WHERE telefon = '$telefon'") or die(mysqli_error($GLOBALS['link']));
    	echo "$navn var deaktiveret, men er nu aktiv igen.";
    }
} else {
    $query = mysqli_query($GLOBALS['link'],"INSERT INTO hbf_brugere (telefon,navn) values ('$telefon','$navn')") or die(mysqli_error($GLOBALS['link']));
    echo "$navn er oprettet som bruger. Velkommen!";
}

?>


