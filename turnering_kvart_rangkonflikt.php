<?php
require_once("functions.php");
$turnering = hentturnering();
$turnerings_id = $turnering["turnering_id"];

// Sørger for at opdatere konflikter
$ipuljer = array();
foreach($_POST as $index => $test){
   $nummer = explode("_",$index); 
   $nummer = $nummer[1];
   $ipuljer[$nummer][] = $HTTP_POST_VARS[$index];
}
foreach($ipuljer as $konflikt_nr => $konflikt){
    $hent_startnummer = mysqli_query($link,"SELECT * FROM hbf_puljer WHERE rangering_konflikt = '$konflikt_nr' and turnerings_id = '$turnerings_id'order by rangering") or die(mysqli_error($link));
    $row = mysql_fetch_array($hent_startnummer);
    $nummer = $row["rangering"];
    
    foreach($konflikt[0] as $spiller){
       $opdater = mysqli_query($link,"UPDATE hbf_puljer SET rangering = '$nummer'  WHERE turnerings_id = '$turnerings_id' AND spiller_id = '$spiller' ") or die(mysqli_error($link));
       $nummer++;
    }

}

genberegnPuljer($turnerings_id);

$puljer = $turnering["puljer"];
$puljerArray = dbarraytoarray($puljer);
$antalpuljer = count($puljerArray);

$pulje_max = max($puljerArray);
$pulje_min = min($puljerArray);



$pulje_storrelse = $pulje_min;
$pulje_nr = "";
$i = 0;
$q =0;

// Skal kun gøres når det er muligt at det giver problemer?
//die(array_sum($puljerArray));

// Sørger for ikke at sætte pulje_min når det bringer det samlede antal ned under 16
if($pulje_min*$antalpuljer < 16){
    //$pulje_min = $pulje_max;
}

$sql = "SELECT * FROM hbf_puljer where turnerings_id = '$turnerings_id' AND rangering <= $pulje_min";
$puljer = mysqli_query($link,$sql) or die(mysqli_error($link));
  while($row = mysql_fetch_array($puljer)){
     if($pulje_nr != $row["pulje_nr"]){$i = 0;}
       if($i < $pulje_storrelse){
            $whitelistbrutto[] = $row["spiller_id"];
       }
       $pulje_nr = $row["pulje_nr"];
       $i++;
       $q++;
 }
$whitelistids = join(',',$whitelistbrutto);

$onlywhitelist = "AND (hold1 in ($whitelistids) AND hold2 in ($whitelistids)) ";

if($pulje_min*$antalpuljer < 17){
//    $onlywhitelist = "";
}

genberegnPuljer($turnerings_id,$onlywhitelist);

// Henter hold i korrekt rækkefølge:
$i = 0;
$hold = mysqli_query($link,"SELECT * FROM hbf_puljer WHERE turnerings_id = '$turnerings_id' ORDER BY (0 + rangering), point DESC, (maal_scoret-maal_gaaetind) DESC,maal_scoret DESC,pulje_id") or die(mysqli_error($link));
while($row = mysql_fetch_array($hold)){
    $i++;
    $opdater = mysqli_query($link,"UPDATE hbf_puljer SET rangering_total = '$i' WHERE pulje_id = '".$row["pulje_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysqli_error($link));
}

// Tjekker om der er problem
$spiller = array();
$antalhold = sumdbarray($turnering["puljer"]);

    
    $henterhold = mysqli_query($link,"SELECT * FROM hbf_puljer WHERE turnerings_id = '$turnerings_id' and rangering_total  = 9 ORDER BY rangering_total") or die(mysqli_error($link));
    $hold8 = mysql_fetch_array($henterhold);
    if(mysql_num_rows($henterhold) >0 ){
        $ensspillere[] = $hold8["spiller_id"];
        $point = $hold8["point"];
        $diff = $hold8["maal_scoret"]-$hold8["maal_gaaetind"];
        $scoret = $hold8["maal_scoret"];
        $rangliste = $hold8["rangering_total"];
        $rangering = $hold8["rangering"];
        // Ser tilbage:

        $stop = true;
        $i = 0;
        $tilbage = false;
        while($stop == true){
            $i++;
            $nyrangliste = $rangliste - $i;
            $sql = "SELECT * FROM hbf_puljer WHERE rangering_total = '$nyrangliste'  AND rangering = '$rangering' AND  turnerings_id = '$turnerings_id' and point = '$point' and (maal_scoret-maal_gaaetind) = '$diff' and maal_scoret ='$scoret'  ORDER BY rangering_total";
            $henterenshold = mysqli_query($link,$sql) or die(mysqli_error($link));
            if(mysql_num_rows($henterenshold) > 0){
                $hold = mysql_fetch_array($henterenshold);
                $tilbage = true;
                $ensspillere[] = $hold["spiller_id"];
            } else {
                $stop = false;
            }

            if($rangliste < 2 || $rangliste > 16){
                $stop = false;
            }
        }

        // Ser frem (hvis der er blevet set tilbage):
        $stop = true;
        $i = 0;
        if($tilbage){
            while($stop == true){
                $i++;
                $nyrangliste = $rangliste + $i;
                $sql = "SELECT * FROM hbf_puljer WHERE rangering_total = '$nyrangliste'  AND rangering = '$rangering' AND turnerings_id = '$turnerings_id' and point = '$point' and (maal_scoret-maal_gaaetind) = '$diff' and maal_scoret ='$scoret'  ORDER BY rangering_total";
                $henterenshold = mysqli_query($link,$sql) or die(mysqli_error($link));
                if(mysql_num_rows($henterenshold) > 0){
                    $hold = mysql_fetch_array($henterenshold);
                    $ensspillere[] = $hold["spiller_id"];
                } else {
                    $stop = false;
                }

                if($rangliste < 2 || $rangliste > 16){
                    $stop = false;
                }
            }

        }

        if(count($ensspillere) < 2){
            header("location:turnering_kvart_placeringkonflikt.php");
        } else {
            // Konflikt
        }
    } else {
       header("location:turnering_kvart_placeringkonflikt.php");
    }


?>
<?php include_once("inc_header.php"); ?>
<body>
    <?php include_once("inc_navigation.php"); ?>

    <section id="content">

    <div class='g12'>
        <h2 style='margin-bottom:10px;'>Konflikt</h2>
            
        

        <form action="turnering_kvart_placeringkonflikt.php" method="post" data-ajax="false">
    <?php

        $q = 1;

        
            echo "<fieldset><label>Konflikt omkring hvilket hold der skal indtage 8. pladsen</label>";
            echo "<section><label>Konflikt <br /><span>Sæt venligst hold i den korrekte rækkefølge</span></label><div>";
            echo "<select name='pulje_rang' class='multiple' id='multiple' multiple>";

            $spillere = join(',',$ensspillere);
            
            $result = mysqli_query($link,"SELECT * FROM hbf_puljer WHERE turnerings_id =  '$turnerings_id' AND spiller_id in($spillere) ") or die(mysqli_error($link));
            while($spiller = mysql_fetch_array($result)){
                $spiller_id = $spiller["spiller_id"];
                echo "  <option value='$spiller_id'>".hentnavne($spiller_id,"-")."</option>";

            }

            echo "</select></div></section>";
            echo "</fieldset>";
            $q++;
        
   ?>

        
   <fieldset>
<section>
        <div style="text-align:right;"><button class="submit green" id="submut">Videre</button></div>
</section>
</fieldset>
</form>
</div>
</body>
<?php
genberegnPuljer($turnerings_id);
include_once("inc_footer.php");
?>