<?php
require_once("functions.php");
$turnering = hentturnering();
$turnerings_id = $turnering["turnering_id"];

$ipuljer = array();
foreach($_POST as $index => $test){
   $ipuljer = $HTTP_POST_VARS[$index];

}
if(count($ipuljer) > 0){
    $spillerids = join(',',$ipuljer);

    $hent_startnummer = mysqli_query($link,"SELECT * FROM hbf_puljer WHERE spiller_id in ($spillerids) and turnerings_id = '$turnerings_id' order by rangering_total") or die(mysqli_error($link));
    $row = mysql_fetch_array($hent_startnummer);
    $nummer = $row["rangering_total"];

    foreach($ipuljer as $spiller_id){
           $opdater = mysqli_query($link,"UPDATE hbf_puljer SET rangering_total = '$nummer'  WHERE turnerings_id = '$turnerings_id' AND spiller_id = '$spiller_id' ") or die(mysqli_error($link));
           $nummer++;
    }
}

// Tjekker om der er problem ved 16/17
$spiller = array();
$antalhold = sumdbarray($turnering["puljer"]);

if($antalhold >= 16){
    
    $henterhold = mysqli_query($link,"SELECT * FROM hbf_puljer WHERE turnerings_id = '$turnerings_id' and rangering_total  = 16 ORDER BY rangering_total") or die(mysqli_error($link));
    $hold8 = mysql_fetch_array($henterhold);
    $ensspillere[] = $hold8["spiller_id"];
    $point = $hold8["point"];
    $diff = $hold8["maal_scoret"]-$hold8["maal_gaaetind"];
    $scoret = $hold8["maal_scoret"];
    $rangliste = $hold8["rangering_total"];
    $rangering = $hold8["rangering"];

   // Ser frem
    $stop = true;
    $i = 0;
    $frem = false;
        while($stop == true){
            $i++;
            $nyrangliste = $rangliste + $i;
            $sql = "SELECT * FROM hbf_puljer WHERE rangering_total = '$nyrangliste' AND rangering = '$rangering' AND turnerings_id = '$turnerings_id' and point = '$point' and (maal_scoret-maal_gaaetind) = '$diff' and maal_scoret ='$scoret'  ORDER BY rangering_total";
            $henterenshold = mysqli_query($link,$sql) or die(mysqli_error($link));
            if(mysql_num_rows($henterenshold) > 0){
                $hold = mysql_fetch_array($henterenshold);
                $frem = true;
                $ensspillere[] = $hold["spiller_id"];
            } else {
                $stop = false;
            }

            if($rangliste < 2 || $rangliste > 16){
                $stop = false;
            }
        }



    // Ser tilbage: (hvis der er blevet set frem):
    
    $stop = true;
    $i = 0;
    if($frem){
        while($stop == true){
            $i++;
            $nyrangliste = $rangliste - $i;
            $sql = "SELECT * FROM hbf_puljer WHERE rangering_total = '$nyrangliste' AND rangering = '$rangering' AND turnerings_id = '$turnerings_id' and point = '$point' and (maal_scoret-maal_gaaetind) = '$diff' and maal_scoret ='$scoret'  ORDER BY rangering_total";
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
        header("location:turnering_kvart.php?start=1");
    } else {
        // Konflikt
    }
    
} else {
    header("location:turnering_kvart.php?start=1");
}
?>
<?php include_once("inc_header.php"); ?>
<body>
    <?php include_once("inc_navigation.php"); ?>

    <section id="content">

    <div class='g12'>
        <h2 style='margin-bottom:10px;'>Konflikt</h2>
            
        

        <form action="turnering_kvart.php?start=1" method="post" data-ajax="false">
    <?php

        $q = 1;

        
            echo "<fieldset><label>Konflikt omkring de sidste pladser i Jays finalerne</label>";
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