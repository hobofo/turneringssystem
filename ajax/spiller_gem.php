<?php

require_once("../functions.php");

$spiller1 = $tlf1 =  $_POST["nummer_spiller1"];
$spiller2 = $tlf2 = $_POST["nummer_spiller2"];

if($_POST["betalt_spiller1"] == "true") {$_POST["betalt_spiller1"] = 1;} else {$_POST["betalt_spiller1"] = 0;};
if($_POST["betalt_spiller2"] == "true") {$_POST["betalt_spiller2"] = 1;} else {$_POST["betalt_spiller2"] = 0;};

$betaltspiller1 =  $_POST["betalt_spiller1"];
$betaltspiller2 = $_POST["betalt_spiller2"];

// tester at spillerne ikke er ens
if($spiller1 ==$spiller2){
 echo "0##En spiller kan ikke spille på hold med sig selv.";
 return;
}

$result = mysql_query("SELECT * FROM hbf_brugere where telefon = '".$spiller1."' and deaktiv != '1'");
$rowsp1 = mysql_fetch_array($result);


if(mysql_num_rows($result) < 1){
 echo "0##Spiller 1 med telefonnummeret '$spiller1' findes ikke! Opret spilleren og prøv igen.";
 return;
} else {
  $spiller1 = $rowsp1["bruger_id"];
  $navn1 = hentbruger($spiller1);
  $navn1 = $navn1["navn"];
}

if($spiller2 != ""){
    $result = mysql_query("SELECT * FROM hbf_brugere where telefon = '".$spiller2."' and deaktiv != '1'");
    $rowsp2 = mysql_fetch_array($result);

    if(mysql_num_rows($result) < 1){
     echo "0##Spiller 2 med telefonnummeret '$spiller2' findes ikke! Opret spilleren og prøv igen.";
     return;
    } else {
      $spiller2 = $rowsp2["bruger_id"];
      $navn2 = hentbruger($spiller2);
      $navn2 = $navn2["navn"];
    }
}

$turneringsid = $_POST["turneringsid"];

// Tester om spiller 1 allerede er en den af turneringen
$results = mysql_query("SELECT * FROM hbf_spillere where spiller = '$spiller1' and turnering_id = '$turneringsid'");
if(mysql_num_rows($results) > 0){
    echo "0##$navn1 ($tlf1) er allerede tilmeldt turneringen.";
    return;
}

// Tester om spiller 1 allerede er en den af turneringen
if($spiller2 != ""){
    $results = mysql_query("SELECT * FROM hbf_spillere where spiller in ('$spiller2') and turnering_id = '$turneringsid'");
    if(mysql_num_rows($results) > 0){
        echo "0##$navn2 ($tlf2) er allerede tilmeldt turneringen.";
        return;
    }
}

if($turneringsid != ""){

        if($spiller2 != ""){
            $bruger1 = hentbruger($spiller1);
            $bruger2 = hentbruger($spiller2);
            $rang = $bruger1["rangliste"] + $bruger2["rangliste"];
            $query = mysql_query("INSERT INTO hbf_spillere (turnering_id,spiller,medspiller,primaer,rang,betalt) values ('$turneringsid','$spiller1','$spiller2','1',$rang,$betaltspiller1)");
            $query = mysql_query("INSERT INTO hbf_spillere (turnering_id,spiller,medspiller,primaer,rang,betalt) values ('$turneringsid','$spiller2','$spiller1','0',$rang,$betaltspiller2)");

            echo "1##$navn1 og $navn2 er tilmeldt som et hold ";

        } else {
            $query = mysql_query("INSERT INTO hbf_spillere (turnering_id,spiller,medspiller,primaer,betalt) values ('$turneringsid','$spiller1','','1',$betaltspiller1)");

            echo "1##$navn1 er tilmeldt som løs \n";
        }



} else {
   echo "0##Der kan ikke oprettes spillere da turneringen ikke er startet";
}

