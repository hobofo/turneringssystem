<?php
require_once("../functions.php");

$i = 0;

$resultathold1 = $_POST["resultathold1"];
$resultathold2 = $_POST["resultathold2"];
$kamp_id = $_POST["kamp_id"];
$turneringsid = $_POST["turneringsid"];

$query = mysqli_query($link,"SELECT * FROM hbf_kampe where kamp_id  = '$kamp_id'") or die(mysql_error());
$row = mysql_fetch_array($query);

if($resultathold1 == $resultathold2){
    $vinder = 0;
} else if($resultathold1 > $resultathold2) {
    $vinder = $row["hold1"];
} else {
    $vinder = $row["hold2"];
}


if(isset($_GET["finaler"])){
  
    if($vinder==0){
        echo "Kampen kan ikke ende uafgjort";
        exit;
    } else {

        $result = mysqli_query($link,"UPDATE hbf_kampe SET resultat1 = '$resultathold1', resultat2 = '$resultathold2', vinder = '$vinder'  where kamp_id = '$kamp_id'");
        
        // Kvartfinaler
        if($row["kampnr"] == 1 && $row["type"] == "k"){ $result = mysqli_query($link,"UPDATE hbf_kampe SET hold1 = '$vinder' WHERE type = 's' and kampnr = '5' AND turnerings_id = '$turneringsid'") or die(mysql_error()); }
        if($row["kampnr"] == 2 && $row["type"] == "k"){ $result = mysqli_query($link,"UPDATE hbf_kampe SET hold2 = '$vinder' WHERE type = 's' and kampnr = '5' AND turnerings_id = '$turneringsid'") or die(mysql_error()); }
        if($row["kampnr"] == 3 && $row["type"] == "k"){ $result = mysqli_query($link,"UPDATE hbf_kampe SET hold1 = '$vinder' WHERE type = 's' and kampnr = '6' AND turnerings_id = '$turneringsid'") or die(mysql_error()); }
        if($row["kampnr"] == 4 && $row["type"] == "k"){ $result = mysqli_query($link,"UPDATE hbf_kampe SET hold2 = '$vinder' WHERE type = 's' and kampnr = '6' AND turnerings_id = '$turneringsid'") or die(mysql_error()); }

        if($row["kampnr"] == 1 && $row["type"] == "jk"){ $result = mysqli_query($link,"UPDATE hbf_kampe SET hold1 = '$vinder' WHERE type = 'js' and kampnr = '5' AND turnerings_id = '$turneringsid'") or die(mysql_error()); }
        if($row["kampnr"] == 2 && $row["type"] == "jk"){ $result = mysqli_query($link,"UPDATE hbf_kampe SET hold2 = '$vinder' WHERE type = 'js' and kampnr = '5' AND turnerings_id = '$turneringsid'") or die(mysql_error()); }
        if($row["kampnr"] == 3 && $row["type"] == "jk"){ $result = mysqli_query($link,"UPDATE hbf_kampe SET hold1 = '$vinder' WHERE type = 'js' and kampnr = '6' AND turnerings_id = '$turneringsid'") or die(mysql_error()); }
        if($row["kampnr"] == 4 && $row["type"] == "jk"){ $result = mysqli_query($link,"UPDATE hbf_kampe SET hold2 = '$vinder' WHERE type = 'js' and kampnr = '6' AND turnerings_id = '$turneringsid'") or die(mysql_error()); }

        // Semifinaler
        if($row["kampnr"] == 5 && $row["type"] == "s"){ $result = mysqli_query($link,"UPDATE hbf_kampe SET hold1 = '$vinder' WHERE type = 'f' AND turnerings_id = '$turneringsid'") or die(mysql_error()); }
        if($row["kampnr"] == 6 && $row["type"] == "s"){ $result = mysqli_query($link,"UPDATE hbf_kampe SET hold2 = '$vinder' WHERE type = 'f' AND turnerings_id = '$turneringsid'") or die(mysql_error()); }
        if($row["kampnr"] == 5 && $row["type"] == "js"){ $result = mysqli_query($link,"UPDATE hbf_kampe SET hold1 = '$vinder' WHERE type = 'jf' AND turnerings_id = '$turneringsid'") or die(mysql_error()); }
        if($row["kampnr"] == 6 && $row["type"] == "js"){ $result = mysqli_query($link,"UPDATE hbf_kampe SET hold2 = '$vinder' WHERE type = 'jf' AND turnerings_id = '$turneringsid'") or die(mysql_error()); }


    }
   

} else {
    
    $result = mysqli_query($link,"UPDATE hbf_kampe SET resultat1 = '$resultathold1', resultat2 = '$resultathold2', vinder = '$vinder'  where kamp_id = '$kamp_id'");
}
?>




<? if($vinder != "0"){ ?>
    Kampen er afsluttet - <? echo hentnavne($vinder," og "); ?> vandt!
<? } else { ?>
     Kampen er afsluttet - den blev uafgjort.
<? } ?>