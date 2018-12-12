<?php
require_once("../functions.php");

$spiller1 = $tlf1 =  $_POST["nummer_spiller1"];
$spiller2 = $tlf2 = $_POST["nummer_spiller2"];


if($_POST["betalt_spiller1"] == "true") {$_POST["betalt_spiller1"] = 1;} else {$_POST["betalt_spiller1"] = 0;};
if($_POST["betalt_spiller2"] == "true") {$_POST["betalt_spiller2"] = 1;} else {$_POST["betalt_spiller2"] = 0;};

$betaltspiller1 =  $_POST["betalt_spiller1"];
$betaltspiller2 = $_POST["betalt_spiller2"];

$turnering = hentturnering();
$turneringsid = $turnering["turnering_id"];
$update = mysqli_query($link,"UPDATE hbf_kampe SET kampnr = '0' WHERE turnerings_id = '$turneringsid' AND vinder = '' AND type = 'p'")or die(mysql_error());
           
// tester at spillerne ikke er ens
if($spiller1 ==$spiller2){
 echo "En spiller kan ikke spille på hold med sig selv.";
 return;
}

$result = mysqli_query($link,"SELECT * FROM hbf_brugere where telefon = '".$spiller1."' and deaktiv != '1'");
$rowsp1 = mysql_fetch_array($result);


if(mysql_num_rows($result) < 1){
 echo "Spiller 1 med telefonnummeret '$spiller1' findes ikke! Opret spilleren og prøv igen.";
 return;
} else {
  $spiller1 = $rowsp1["bruger_id"];
  $navn1 = hentbruger($spiller1);
  $navn1 = $navn1["navn"];
}

if($spiller2 != ""){
    $result = mysqli_query($link,"SELECT * FROM hbf_brugere where telefon = '".$spiller2."' and deaktiv != '1'");
    $rowsp2 = mysql_fetch_array($result);

    if(mysql_num_rows($result) < 1){
     echo "Spiller 2 med telefonnummeret '$spiller2' findes ikke! Opret spilleren og prøv igen.";
     return;
    } else {
      $spiller2 = $rowsp2["bruger_id"];
      $navn2 = hentbruger($spiller2);
      $navn2 = $navn2["navn"];
    }
}

// Tester om spiller 1 allerede er en den af turneringen
$results = mysqli_query($link,"SELECT * FROM hbf_spillere where spiller = '$spiller1' and turnering_id = '$turneringsid'");
if(mysql_num_rows($results) > 0){
    echo "$navn1 ($tlf1) er allerede tilmeldt turneringen.";
    return;
}

// Tester om spiller 1 allerede er en den af turneringen
if($spiller2 != ""){
    $results = mysqli_query($link,"SELECT * FROM hbf_spillere where spiller in ('$spiller2') and turnering_id = '$turneringsid'");
    if(mysql_num_rows($results) > 0){
        echo "$navn2 ($tlf2) er allerede tilmeldt turneringen.";
        return;
    }
}

if($turneringsid != ""){
   
        if($spiller2 != ""){

            
           
            
            
            $query = mysqli_query($link,"SELECT  `pulje_nr` , COUNT( * ) AS antal
                FROM  `hbf_puljer` WHERE turnerings_id = '$turneringsid'
                GROUP BY pulje_nr
                ORDER BY antal");
            $row = mysql_fetch_array($query);
            $pulje_nr  = $row["pulje_nr"];
         

            // Indsætter spiller
            $bruger1 = hentbruger($spiller1);
            $bruger2 = hentbruger($spiller2);
            $rang = $bruger1["rangliste"] + $bruger2["rangliste"];
            $query = mysqli_query($link,"INSERT INTO hbf_spillere (turnering_id,spiller,medspiller,primaer,rang,betalt) values ('$turneringsid','$spiller1','$spiller2','1',$rang,$betaltspiller1)");
            $spiller_id = mysql_insert_id();
            $query = mysqli_query($link,"INSERT INTO hbf_spillere (turnering_id,spiller,medspiller,primaer,rang,betalt) values ('$turneringsid','$spiller2','$spiller1','0',$rang,$betaltspiller2)");

            
            // Indsætter pulje
            $results = mysqli_query($link,"INSERT INTO hbf_puljer (turnerings_id,pulje_nr,spiller_id,point,kampe,type) values ('$turneringsid','$pulje_nr','$spiller_id','0','0','p')") or die(mysql_error());

            // Finder mindste pulje
            $puljertilny = dbarraytoarray($turnering["puljer"]);
            sort($puljertilny);

            $puljertilny[0]  = $puljertilny[0]+1;
            $puljerny = arraytodbarray($puljertilny);
            $opdater = mysqli_query($link,"UPDATE hbf_turnering SET puljer = '$puljerny' where turnering_id = '$turneringsid'") or die(mysql_error());

            // Indsætter kampe
            $result_partner = mysqli_query($link,"SELECT * FROM `hbf_puljer` where turnerings_id = '$turneringsid' and pulje_nr = '$pulje_nr' and spiller_id <> '$spiller_id' order by pulje_id DESC") or die(mysql_error());
            while($rowp = mysql_fetch_array($result_partner)){
                $modstander =  $rowp["spiller_id"];
                // Ser om kombinationen findes
                $komp = mysqli_query($link,"SELECT *  FROM `hbf_kampe` where turnerings_id = '$turneringsid'  and ((hold1 = '$spiller_id' AND hold2 = '$modstander') OR (hold1 = '$modstander' AND hold2 = '$spiller_id') )") or die(mysql_error());
                if(mysql_num_rows($komp)< 1){
                        $rang1 = hentrang($spiller_id);
                        $rang2 = hentrang($modstander);
                        $insert = mysqli_query($link,"INSERT INTO hbf_kampe (turnerings_id,hold1,hold2,rang1,rang2,type,kampnr,pulje,parameter) values ('$turneringsid','$spiller_id','$modstander','$rang1','$rang2','p','0','$pulje_nr','0')")or die(mysql_error());
                }
            }

            $update = mysqli_query($link,"UPDATE hbf_kampe SET kampnr = '0' WHERE turnerings_id = '$turneringsid' AND vinder = '' AND type = 'p'")or die(mysql_error());
            updatekampnr();
            
            echo "$navn1 og $navn2 er tilmeldt som et hold ";

        } 
} else {
   echo "Der kan ikke oprettes spillere da turneringen ikke er startet";
}

