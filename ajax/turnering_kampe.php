<?php
require_once("../functions.php");

$turnering = hentturnering();
$turneringsid = $turnering["turnering_id"];
$i = 0;

?>

    <?php
    // Checker at der er lige så mange nuværendekampe som borde
    $kampe = mysql_query("SELECT * FROM hbf_kampe WHERE turnerings_id = '".$turneringsid."' and bord <> '' and  vinder = '' and type = 'p' order by kampnr DESC") or die(mysql_error());
    $livekampe = mysql_num_rows($kampe);
    $borde = dbarraytoarray($turnering["borde"]);
    $antalborde = count($borde);
    
    if($livekampe != $borde){
        $antal = $antalborde - $livekampe;
        
        // Hvem spiller nu og på hvilket bord?
        $nuborde = array();
        $holdarray = array();
        $kampe = mysql_query("SELECT * FROM hbf_kampe WHERE turnerings_id = '".$turneringsid."' and bord <> '' and  vinder = '' and type = 'p' order by kampnr DESC") or die(mysql_error());
        while($kamp = mysql_fetch_array($kampe)){
            $nuborde[] = $kamp["bord"];
        }
        $ledigeborde = array_diff($borde,$nuborde);
        foreach($ledigeborde as $ledigtbord){
            $normledige[] = $ledigtbord;
        }
       
        for($i = 0;$i < $antal;$i++){
            $kampe = mysql_query("SELECT * FROM hbf_kampe WHERE turnerings_id = '".$turneringsid."' and bord <> '' and  vinder = '' and type = 'p' order by kampnr DESC") or die(mysql_error());
            while($kamp = mysql_fetch_array($kampe)){
                $holdarray[]= $kamp["hold1"];
                $holdarray[]= $kamp["hold2"];
            }
            
            // Åbner kamp hvis holdene ikke er blandt de spillende.            
            $kampe = mysql_query("UPDATE `hbf_kampe` SET bord = '".$normledige[$i]."',startet = NOW() WHERE bord = '' and  type = 'p' and vinder = '' and  turnerings_id = '".$turneringsid."' AND hold1 NOT IN ('".implode("','",$holdarray)."') AND hold2 NOT IN ('".implode("','",$holdarray)."') order by `kampnr` limit 1") or die(mysql_error());

        }
    }

    // Sidste kamp
    $kampe = mysql_query("SELECT * FROM hbf_kampe WHERE turnerings_id = '".$turneringsid."' and bord <> '' and  vinder = '' and type = 'p' order by startet DESC, bord desc") or die(mysql_error());
    $sidstekamp = mysql_fetch_array($kampe);
    
    $i = 0;
    $kampe = mysql_query("SELECT * FROM hbf_kampe WHERE turnerings_id = '".$turneringsid."' and bord <> '' and  vinder = '' and type = 'p' order by bord") or die(mysql_error());
    while($kamp = mysql_fetch_array($kampe)){
        $i++;
        if($i == 1){
            echo "<div class='g12' style='margin:0;padding:0;'>";
        }


        if($sidstekamp["kamp_id"] == $kamp["kamp_id"]){
          $baggrund = "background-color:#a2e8a2;font-weight:bold;";
        } else {
          $baggrund = "background-image:url(css/light/images/paper_02.png);font-weight:bold;";
        }

        sendSMS($kamp);
        // $text = hentnavne($kamp["hold1"]," og ")."spiller%20imod%20".hentnavne($kamp["hold2"]," og ")."%20på%20".settingNumberToName('borde',$kamp["bord"]);
        // $ch = curl_init(); 
        // curl_setopt($ch, CURLOPT_URL, "http://87-104-227-27-static.trp-solutions.dk/sms.php?key=yAaQ7fuGf&number=61333789&text=".$text); 
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        // curl_exec($ch); 
        // curl_close($ch);

        echo "<div class='g6' style='margin-top:0;padding-top:0;'>
               <table ><tr style='$baggrund'><td style='$baggrund'>".settingNumberToName('borde',$kamp["bord"])."</td></tr>
        <tr style='background-color:#fff;'><td>".hentnavne($kamp["hold1"]," og ")."</td></tr>
        <tr style='background-color:#fff;'><td>".hentnavne($kamp["hold2"]," og ")."</td></tr>";
         //echo "<tr><td><a href='javascript:afslutkamp(".$kamp["hold1"].",".$kamp["hold2"].")' class='btn small'>Afslut kamp</a></td></tr>";
        echo "<tr style='background-color:#fff;'><td><a href='#afslutkampbox' rel='".$kamp["kamp_id"]."'  class='afslutkamp btn small'>Afslut kamp</a></td></tr>";
        echo "</table></div>";
        if($i == 2){
            echo "</div>";
            $i = 0;
        }
    }

    ?>

<? if(mysql_num_rows($kampe) < 1){ ?>
<div class='g12'>Ingen nuværende kampe </div>

<? } ?>