<?php

setlocale(LC_ALL, 'da_DK');
date_default_timezone_set('Europe/Copenhagen');
session_start();
require_once("conn.php");

if(isset($_SESSION['username']))
{
  // Logged in
  $user_set = true;
}
else
{
  if(!isset($Global_ignorecheck)){
  header("location:login.php");
  }
}

 // Henter bruger
function hentbruger($bruger_id){
    $result = mysqli_query($link,"SELECT * from hbf_brugere where bruger_id = '$bruger_id'") or die(mysqli_error($link));
    $row = mysql_fetch_array($result);
    return $row;
}

function opdaterrangliste(){

    $date = date("Y-m-d H:i:s");
    $newdate = strtotime ( '-8 WEEKS' , strtotime ( $date ) ) ;
    $newdate = date ( 'Y-m-d H:i:s' , $newdate );

    $query = mysqli_query($link,"SELECT * from hbf_brugere") or die(mysqli_error($link));
    while($row = mysql_fetch_array($query)){
        $bruger_id = $row["bruger_id"];
        $rangliste = mysqli_query($link,"SELECT sum(point) as sum FROM hbf_rangliste WHERE date > '$newdate' and bruger_id = '$bruger_id'") or die(mysqli_error($link));
        $rowrang = mysql_fetch_array($rangliste);
        $sum = $rowrang["sum"];

        $opdater = mysqli_query($link,"UPDATE hbf_brugere SET rangliste = '$sum' where bruger_id = '$bruger_id'") or die(mysqli_error($link));
    }

    $opdater = mysqli_query($link,"UPDATE hbf_brugere SET rangliste = '0' where telefon = '88888888'") or die(mysqli_error($link));

}

// Send sms
function sendSMS($kamp) {
    if($kamp["sms_sent"] == 1) return;

    $hold1 = spillerinfo($kamp["hold1"]);
    $spillere = [];
    $spillere[] = hentbruger($hold1["spiller"]);
    $spillere[] = hentbruger($hold1["medspiller"]);
    $hold2 = spillerinfo($kamp["hold2"]);
    $spillere[] = hentbruger($hold2["spiller"]);
    $spillere[] = hentbruger($hold2["medspiller"]);

    foreach($spillere as $spiller) {
        if($spiller["modtage_sms"] == 1) {
            $text = "Bord ".($kamp["bord"]+1).".%0A".$spillere[0]["navn"]." og ".$spillere[1]["navn"]." - ".$spillere[2]["navn"]." og ".$spillere[3]["navn"].". Kaldt op klokken ".date('H:i').".%0AHBF";
            $text = str_replace(' ', '%20', $text);
            $phoneNumber = $spiller["telefon"];
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, "http://87-104-227-27-static.trp-solutions.dk/sms.php?key=yAaQ7fuGf&number=".$phoneNumber."&text=".$text); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_exec($ch); 
            curl_close($ch);
        }
    }

    $query = mysqli_query($link,"UPDATE hbf_kampe SET sms_sent = 1 WHERE kamp_id = '".$kamp["kamp_id"]."' ") or die(mysqli_error($link));
}

 // Henter navn
function hentnavne($spiller_id,$opdel = "<br />"){
    if($spiller_id > 0){
    $result = mysqli_query($link,"SELECT * from hbf_spillere where spiller_id = '$spiller_id'") or die(mysqli_error($link));
    $row = mysql_fetch_array($result);
    $spiller = hentnavn($row["spiller"]);
    $medspiller = hentnavn($row["medspiller"]);

        return $spiller.$opdel.$medspiller;
    } else {
        return "-";
    }
}
function hentrang($spiller_id){
    $result = mysqli_query($link,"SELECT * from hbf_spillere where spiller_id = '$spiller_id'") or die(mysqli_error($link));
    $row = mysql_fetch_array($result);
    $spiller = hentbruger($row["spiller"]);
    $medspiller = hentbruger($row["medspiller"]);
    $total = $spiller["rangliste"]+$medspiller["rangliste"];
    return $total;
}

 // Henter navn
function hentnavn($bruger_id){
    $result = mysqli_query($link,"SELECT * from hbf_brugere where bruger_id = '$bruger_id'") or die(mysqli_error($link));
    $row = mysql_fetch_array($result);
    return $row["navn"];
}
 // Henter turnering
function hentturnering(){
    $result = mysqli_query($link,"SELECT * from hbf_turnering where  slut_date = '0000-00-00 00:00:00' ORDER BY date DESC") or die(mysqli_error($link));
    $row = mysql_fetch_array($result);
    return $row;
}

 // Henter bruger
function spillerinfo($spiller_id){
    $result = mysqli_query($link,"SELECT * from hbf_spillere where spiller_id = '$spiller_id'") or die(mysqli_error($link));
    $row = mysql_fetch_array($result);
    return $row;
}

function stringtodbarray($strings){
    $strings = explode(",", $strings);
    $dbarray = "";
    foreach($strings as $string){
        $dbarray .= "{".$string."},";
    }
    $dbarray = substr($dbarray,0,-1);
    return $dbarray;
}

function dbarraytostring($dbarray){
    $dbarray = str_replace("}", "", $dbarray);
    $dbarray = str_replace("{", "", $dbarray);
    return $dbarray;
}

function dbarraytoarray($dbarray){
    $dbarray = str_replace("}", "", $dbarray);
    $dbarray = str_replace("{", "", $dbarray);
    $dbarray = explode(",",$dbarray);
    return $dbarray;
}
function splitholdtilbruger($spiller_id,$turnerings_id){
    $hent = mysqli_query($link,"SELECT * FROM hbf_spillere WHERE turnering_id = '$turnerings_id' AND spiller_id = '$spiller_id'") or die(mysqli_error($link));
    $brugere = mysql_fetch_array($hent);
    $bruger[] = $brugere["spiller"];
    $bruger[] = $brugere["medspiller"];
    return $bruger;
}
function afslutkamprangliste ($hold,$rangliste,$type,$turnerings_id){
    if($hold > 0){
        $brugere = splitholdtilbruger($hold,$turnerings_id);
        foreach($brugere as $bruger_id){

            $bruger = hentbruger($bruger_id);
            $query = mysqli_query($link,"INSERT INTO hbf_rangliste (bruger_id,text,date,point,turnerings_id) values ('".$bruger_id."','$type',now(),'".$rangliste."','$turnerings_id')") or die(mysqli_error($link));
            $nyrangliste = $rangliste + $bruger["rangliste"];
            $query = mysqli_query($link,"UPDATE hbf_brugere set rangliste = '$nyrangliste' WHERE bruger_id = '".$bruger["bruger_id"]."' ") or die(mysqli_error($link));
        }
    }
}
function sumdbarray($dbarray){
   $antaltotal = 0;
   foreach(dbarraytoarray($dbarray) as $antal){
        $antaltotal = $antaltotal + $antal;
   }
   return $antaltotal;
}
function arraytodbarray($array){
    $dbarray = "";
    foreach($array as $string){
        $dbarray .= "{".$string."},";
    }
    $dbarray = substr($dbarray,0,-1);
    return $dbarray;
}

function getsetting($setting){

    $query = mysqli_query($link,"SELECT * FROM hbf_indstillinger where short = '$setting'");
    $setting = mysql_fetch_array($query);

    return $setting["setting"];
}

function settingNumberToName($setting,$number){

    $query = mysqli_query($link,"SELECT * FROM hbf_indstillinger where short = '$setting'");
    $setting = mysql_fetch_array($query);
    $settingarr = dbarraytoarray($setting["setting"]);
    $setting = $settingarr[$number];
    return $setting;
}

function getpuljespiller($spiller_id,$turnering_id){

    $query = mysqli_query($link,"SELECT * FROM hbf_puljer where spiller_id = '$spiller_id' AND turnerings_id = '$turnering_id' and type = 'p'");
    $spiller = mysql_fetch_array($query);

    return $spiller;
}
function puljeresultatens($spiller1,$spiller2){
    if(
            $spiller1["point"] == $spiller2["point"]
         && ($spiller1["maal_scoret"]-$spiller1["maal_gaaetind"]) == ($spiller2["maal_scoret"]-$spiller2["maal_gaaetind"])
         && ($spiller1["maal_scoret"] == $spiller2["maal_scoret"])
      )
        {
            return true;
        } else {
            return false;
        }
}
function genberegnPuljer($turnering,$whitelist = ""){

 $onlywhitelist = $whitelist;
 $puljer = mysqli_query($link,"SELECT * FROM hbf_puljer WHERE turnerings_id = '".$turnering."' order by pulje_nr, pulje_id DESC") or die(mysqli_error($link));
 while($row = mysql_fetch_array($puljer)){

     $pulje_id = $row["pulje_id"];
     $hold = $row["spiller_id"];


     $kampe = mysqli_query($link,"SELECT count(*) as antal FROM hbf_kampe WHERE turnerings_id = '".$turnering."' and  type = 'p' and vinder = '$hold' $onlywhitelist") or die(mysqli_error($link));
     $antal_vundne = mysql_fetch_array($kampe);

     $kampe = mysqli_query($link,"SELECT count(*) as antal FROM hbf_kampe WHERE turnerings_id = '".$turnering."' and  type = 'p' and vinder = '0' and (hold1 = '$hold' OR hold2 = '$hold') $onlywhitelist") or die(mysqli_error($link));
     $antal_uafgjort = mysql_fetch_array($kampe);

     $kampe = mysqli_query($link,"SELECT count(*) as antal FROM hbf_kampe WHERE turnerings_id = '".$turnering."' and  type = 'p' and (vinder <> '' and vinder <> '0' and vinder <> '$hold') and (hold1 = '$hold' OR hold2 = '$hold') $onlywhitelist") or die(mysqli_error($link));
     $antal_tabte = mysql_fetch_array($kampe);

     // Mål scoret
     $maal = mysqli_query($link,"SELECT sum(resultat1) as maal_scoret FROM hbf_kampe WHERE turnerings_id = '".$turnering."' and  type = 'p' and (hold1 = '$hold') $onlywhitelist") or die(mysqli_error($link));
     $maalrow1 = mysql_fetch_array($maal);

     $maal = mysqli_query($link,"SELECT sum(resultat2) as maal_scoret FROM hbf_kampe WHERE turnerings_id = '".$turnering."' and  type = 'p' and (hold2 = '$hold') $onlywhitelist") or die(mysqli_error($link));
     $maalrow2 = mysql_fetch_array($maal);

     $maal_scoret = $maalrow1["maal_scoret"]+$maalrow2["maal_scoret"];

     // Mål gået ind
     $maal = mysqli_query($link,"SELECT sum(resultat2) as maal_scoret FROM hbf_kampe WHERE turnerings_id = '".$turnering."' and  type = 'p' and (hold1 = '$hold') $onlywhitelist") or die(mysqli_error($link));
     $maalrow1 = mysql_fetch_array($maal);

     $maal = mysqli_query($link,"SELECT sum(resultat1) as maal_scoret FROM hbf_kampe WHERE turnerings_id = '".$turnering."' and  type = 'p' and (hold2 = '$hold') $onlywhitelist") or die(mysqli_error($link));
     $maalrow2 = mysql_fetch_array($maal);

     $maal_gaaetind = $maalrow1["maal_scoret"]+$maalrow2["maal_scoret"];

     $point = $antal_vundne["antal"]*2 + $antal_uafgjort["antal"]*1;

     $antalkampe = $antal_vundne["antal"] + $antal_uafgjort["antal"] + $antal_tabte["antal"];
     //if($antalkampe != 0){ die($antal_vundne["antal"]."-".$antal_uafgjort["antal"]."-".$antal_tabte["antal"]);}
     $opdater = mysqli_query($link,"UPDATE hbf_puljer SET point = '$point',kampe = '$antalkampe',maal_scoret = '$maal_scoret',maal_gaaetind = '$maal_gaaetind',type='p' WHERE turnerings_id = '".$turnering."' AND spiller_id = '$hold'");
 }
}


 function updatekampnr(){
     
    $turnering = hentturnering();
    $turneringsid = $turnering["turnering_id"];

    $puljer = mysqli_query($link,"SELECT DISTINCT pulje from hbf_kampe WHERE  turnerings_id = '$turneringsid'") or die(mysqli_error($link));
    while($row = mysql_fetch_array($puljer)){
        $puljerar[] = $row["pulje"];
    }
    
    // LOOP PULJER
    foreach($puljerar as $puljenummer){
        $puljespillere = array();
        $puljer = mysqli_query($link,"SELECT spiller_id,(SELECT rang FROM hbf_spillere WHERE turnering_id = '$turneringsid' AND primaer = '1' and hbf_spillere.spiller_id = hbf_puljer.spiller_id) as rang from hbf_puljer WHERE  turnerings_id = '$turneringsid' AND pulje_nr = '$puljenummer' ORDER BY rang DESC,spiller_id") or die(mysqli_error($link));
        while($row = mysql_fetch_array($puljer)){
            $puljespillere[] = $row["spiller_id"];
        }
        $q = 0;

        // Opdaterer rækkefølgen i puljen så indgangsbilledet er okay.
        foreach($puljespillere as $spiller){
            $q++;

            $update = mysqli_query($link,"UPDATE hbf_puljer SET initial_placering = '$q' WHERE turnerings_id = '$turneringsid' AND spiller_id = '$spiller'") or die (mysqli_error($link));
            //echo hentnavne($spiller)." $puljenummer $q<br><br>";

        }
        
        //$puljespillere = array('1','2','3','4','5','6','7','8','9');
        $antal = count($puljespillere);
        if(checkNum($antal)){
            $puljespillere[] = "d";
        }
        $antal = count($puljespillere);

        $stop = 0;
        $q = 0;
        while($stop != $antal-1){

            for ( $i = 0; $i <= ($antal/2)-1; $i += 1) {

               // Opdater kamp, med mindre det er en dummy kampe
                if($puljespillere[$i] != "d" && $puljespillere[$antal-$i-1] != "d"){
                    $sp1 = $puljespillere[$i];
                    $sp2 = $puljespillere[$antal-$i-1];
                    $q++;
                    $update = mysqli_query($link,"UPDATE hbf_kampe SET kampnr = '$q' WHERE turnerings_id = '$turneringsid' AND (hold1 = '$sp1' OR hold2 = '$sp1') AND (hold1 = '$sp2' OR hold2 = '$sp2')") or die(mysqli_error($link));
                    //echo $puljespillere[$i]." mod ".$puljespillere[$antal-$i-1]."<br />";
                }
            }
            //echo "<br />";
            $puljespillereny = array();
            foreach($puljespillere as $index=>$spiller){
               $puljespillereny[$index+1] = $puljespillere[$index];
            }
            $nynummeret = $puljespillereny[$antal];

            $puljespillereny[0] = $puljespillereny[1];
            $puljespillereny[1] = $nynummeret;
            unset($puljespillereny[$antal]);
            $puljespillere = $puljespillereny;

            $stop++;
        }
    }

    // Opdaterer alle puljer
    $q = 0;
    $opdater = mysqli_query($link,"SELECT * FROM  hbf_kampe WHERE turnerings_id = '$turneringsid' ORDER BY kampnr,pulje") or die(mysqli_error($link));
    while($row = mysql_fetch_array($opdater)){
            $q++;
            $opdaternr = mysqli_query($link,"UPDATE hbf_kampe SET kampnr  = '$q' WHERE kamp_id = '".$row["kamp_id"]."'") or die(mysqli_error($link));
        }

 }

function checkNum($num){
  return ($num%2) ? TRUE : FALSE;
}

    function setkvartfinalekamp($turnerings_id,$kampprogram,$kampnummer,$forstespiller,$andenspiller,$finaletype){
       
        // Dernæst køres normalt
        $stop = false;
        $q = $forstespiller; // Første spiller
        $i = $andenspiller;  // Optimal anden spiller
        
 
        while($stop != true and $i>$q){
            
            if($kampprogram[$q] > 0 && $kampprogram[$i] > 0){
                $spiller_id = $kampprogram[$q];
                $modstander = $kampprogram[$i];
                $insert = mysqli_query($link,"INSERT INTO hbf_kampe (turnerings_id,hold1,hold2,type,kampnr,pulje,parameter) values ('$turnerings_id','$spiller_id','$modstander','$finaletype','$kampnummer','0','0')")or die(mysqli_error($link));
                $stop = true;
                $kampprogram[$i] = $kampprogram[$q] = 0;
                $kampnummer++;
            }

        $i = $i - 1;
        }
        
        $result["kamprogram"] = $kampprogram;
        $result["kampnummer"] = $kampnummer;
        return $result;
    }

     function setkvart2puljer($placering,$pulje,$turnerings_id,$kamprangering){
            $output = 0;
           
            if(!empty($kamprangering)){
                $list = "'".implode("', '", $kamprangering)."'";
                $total = "AND rangering_total not in (".$list.")";
            } else {
                $total = "";
            }
           
            $hent = mysqli_query($link,"SELECT * FROM hbf_puljer WHERE turnerings_id = '$turnerings_id'  $total and rangering_total < 9   AND kvartfinale = 0 AND pulje_nr = $pulje ORDER BY rangering_total  limit 0,1")or die(mysqli_error($link));
            while($row = mysql_fetch_array($hent)){
                
                $output = $row["rangering_total"];
                
                
            }
            
            return $output;

        } 
        
        
        
          function setkvart2puljerjay($placering,$pulje,$turnerings_id,$kamprangering){
            $output = 0;
            
            if(!empty($kamprangering)){
                $list = "'".implode("', '", $kamprangering)."'";
                $total = "AND rangering_total not in (".$list.")";
            } else {
                $total = "";
            }

            $hent = mysqli_query($link,"SELECT * FROM hbf_puljer WHERE turnerings_id = '$turnerings_id'  $total and rangering_total > 8 and kvartfinale = 0 and rangering_total <= 16  AND pulje_nr = $pulje ORDER BY rangering_total  limit 0,$placering")or die(mysqli_error($link));
            while($row = mysql_fetch_array($hent)){

                $output = $row["rangering_total"];
                
            }
            
            return $output;

        } 

    
        
        function puljer2hent($turnerings_id,$rangering_total,$pulje_nr){
      $hent = mysqli_query($link,"SELECT * FROM hbf_puljer WHERE turnerings_id = '$turnerings_id' and rangering_total in ($rangering_total) AND kvartfinale = 0 AND pulje_nr = $pulje_nr ORDER BY rangering_total limit 0,1")or die(mysqli_error($link));
      $row = mysql_fetch_array($hent);
      if(mysqli_num_rows($hent)> 0){
        $type = $row["spiller_id"];
       
      } else {
       $type = 0;
      }
       return $type;
      }
      
      function set2puljer($puljer2,$kampnummer,$spec,$modarr,$turnerings_id,$finaletype){
        if($puljer2[$spec] > 0){
                $spiller_id = $puljer2[$spec];
                $modstander = $puljer2[$spec] = 0;
                foreach($modarr as $mod){
                if($puljer2[$mod] > 0){
                        $modstander = $puljer2[$mod];
                        $puljer2[$mod] = 0;
                        break;
                }    
                }
                if($modstander > 0){
                    $kampnummer++;
                    $insert = mysqli_query($link,"INSERT INTO hbf_kampe (turnerings_id,hold1,hold2,type,kampnr,pulje,parameter) values ('$turnerings_id','$spiller_id','$modstander','$finaletype','$kampnummer','0','0')")or die(mysqli_error($link));
                
                }
        }
        return $kampnummer;
        
      }