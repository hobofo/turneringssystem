<?php
require_once("../functions.php");

$turnering = hentturnering();
$turneringsid = $turnering["turnering_id"];
$i = 0;

genberegnPuljer($turnering["turnering_id"]);

?>
    <?
    $nummerOld = $nummer ="";
    $puljer = mysql_query("SELECT * FROM hbf_puljer WHERE turnerings_id = '".$turnering["turnering_id"]."' order by pulje_nr, point DESC, (maal_scoret-maal_gaaetind) DESC,maal_scoret DESC,kampe DESC,initial_placering,spiller_id") or die(mysql_error());
    
    while($pulje = mysql_fetch_array($puljer)){

        $nummer = $pulje["pulje_nr"];

        if($nummer != $nummerOld && $nummerOld != ""){
            echo "</table>
            </div>";
        }
        if($nummer/3 == round(($nummer/3))){
            echo "<div style='clear:both;'></div>";
        }
        if($nummer != $nummerOld){
            echo "
            <div class='g4'>
            <h4 style='margin-bottom:5px;'>Pulje ".($nummer+1)."</h4>
            <table >
            <tr><th>Hold</th><th>Kampe</th><th>Mål</th><th>Point</th></tr>";
        }

        echo "
            <tr style='background-color:#fff;'><td>".hentnavne($pulje["spiller_id"])."</td><td>".$pulje["kampe"]."</td><td>".$pulje["maal_scoret"]." / ".$pulje["maal_gaaetind"]."</td><td>".$pulje["point"]."</td></tr>
        ";

        

        $nummerOld = $nummer;
    }
        echo "</table>
            </div>";
    ?>

<div style="clear:both;"></div>
<div class="fl" style="padding:5px;padding-left:10px;">
    <?
    $pointarray = dbarraytoarray($turnering["point"]);
    ?>
    <span style="font-weight:bold;">Point:</span> Ordinær <?=$pointarray[0]?>,<?=$pointarray[1]?>,<?=$pointarray[2]?>,<?=$pointarray[3]?> - Jays <?=$pointarray[4]?>,<?=$pointarray[5]?>,<?=$pointarray[6]?>,<?=$pointarray[7]?>
</div>
<div class="fr">
    <a id="nybrugerlink"  href="#nybruger" class="btn small">Tilføj hold</a>
</div>