<?php
require_once("../functions.php");

$kunloese = false;
$spillerid = 0;

if(isset($_GET["kunloese"])){
    $kunloese = true;
    $spillerid = $_GET["kunloese"];
   
}
$hold = $lose_overskrift = $lose = "";

$turneringsid = $_GET["id"];
$i = 0;
$query = mysqli_query($link,"SELECT * FROM hbf_spillere where turnering_id = '$turneringsid' AND primaer = '1' AND medspiller <> ''") or die(mysql_error());
$antalhold = mysql_num_rows($query);
while($row = mysql_fetch_array($query)){
    $i++;
    $spiller = hentbruger($row["spiller"]);
    $medspiller = hentbruger($row["medspiller"]);
    $hold .= "
    <h4>Hold $i</h4>
    <p>
        <a class='btn i_house icon yellow sletspillerlink' href='#data_sletspiller' id='".$row["spiller_id"]."-0' >".$spiller["navn"]."</a>
        <a class='btn i_house icon yellow sletspillerlink' href='#data_sletspiller' id='".$row["spiller_id"]."-1'>".$medspiller["navn"]."</a>
    </p>
    ";

 } 
$countloese = 0;
if($kunloese){
    $query = mysqli_query($link,"SELECT * FROM hbf_spillere where turnering_id = '$turneringsid' AND primaer = '1' AND medspiller = '' AND spiller_id <> '".$spillerid."'") or die(mysql_error());
    
    
} else {
    $query = mysqli_query($link,"SELECT * FROM hbf_spillere where turnering_id = '$turneringsid' AND primaer = '1' AND medspiller = ''") or die(mysql_error());

    $countloese = mysql_num_rows($query);
}

if(mysql_num_rows($query) > 0 ){
    $lose_overskrift .= "<h4 style='margin-top:10px;'>Løse</h4>";
}
$i = 0;
while($row = mysql_fetch_array($query)){

    $spiller = hentbruger($row["spiller"]);

    if($kunloese){
       if($i == 4){
           $i = 0;
           $lose .= "<br />";
       }
       $i++;
       $lose .= "<a href='#data' onclick='javascript:valgpartner(\"".$spillerid."\",\"".$row["spiller_id"]."\");return false;' class='btn i_house icon yellow'>".$spiller["navn"]."</a>";


    } else {
       $lose .= "<a href='#data' onclick='javascript:valgloesepiller(\"".$row["spiller_id"]."\");return false;' class='btn i_house icon yellow'>".$spiller["navn"]."</a>";
    }

}

if($kunloese){
     if($lose == ""){ $lose = "Ingen partnere at vælge"; }  
     echo "<h3 style='margin-bottom:5px;'>Vælg partner</h3>".$lose;
} else {
    echo $hold.$lose_overskrift.$lose;
}
echo "<input type='hidden' value='$countloese' id='antalloese' name='antalloese'>";
echo '<input type="hidden" class="antalhold" name="antalhold" value="'.$antalhold.'">';