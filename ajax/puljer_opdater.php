<?php
require_once("../functions.php");

$turneringsid = $_GET["id"];
$antalpuljer = $_GET["antal"];
$i = 0;

$query = mysql_query("SELECT * FROM hbf_spillere where turnering_id = '$turneringsid' AND primaer = '1' AND medspiller <> ''") or die(mysql_error());
$antal = mysql_num_rows($query);

$antalmulige = floor($antal/2);
$overskud = $antal-floor($antal/3)*3;
$type1 = floor($antal/$antalpuljer);
$type2 = floor($antal/$antalpuljer)+1;
$overskud = $antal-floor($antal/$antalpuljer)*$antalpuljer;
$antaltype1 = $antalpuljer-$overskud;
$antaltype2 = $overskud;

 // Puljer
    $loop = true;
    $i = 0;
    $puljer = "";

    for ($counter = 1; $counter <= $antaltype1; $counter += 1) {
        $puljer .= "{".$type1."},";
    }

    for ($counter = 1; $counter <= $antaltype2; $counter += 1) {
        $puljer .= "{".$type2."},";
    }

    $puljer = substr($puljer,0,-1);

//$type1 $antaltype1 $type2 $antaltype2
$opdater = mysql_query("UPDATE hbf_turnering SET puljer = '$puljer' WHERE turnering_id = '$turneringsid'") or die(mysql_error());

?>
