<?php
require_once("functions.php");
$turnering = hentturnering();

$turnerings_id = $turnering["turnering_id"];

$puljer = $turnering["puljer"];
$puljerArray = dbarraytoarray($puljer);
$antalpuljer = count($puljerArray);

$pulje_max = max($puljerArray);
$pulje_min = min($puljerArray);
$startforfra = false;
if(isset($_GET["forfra"])){
    $startforfra = true;
}

// Sender videre til kvartfinaler hvis der er nogen aktive kvartfinaler
$finaler = mysql_query("SELECT * FROM hbf_kampe WHERE type = 'f' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
if(mysql_num_rows($finaler)>0 && $startforfra == false){
    header("location:turnering_kvart.php");
    exit();
}

foreach($puljerArray as $puljenr => $puljeinfo){
 $i = 0;
 $results = mysql_query("SELECT * FROM hbf_puljer where turnerings_id = '$turnerings_id' and pulje_nr = '$puljenr'  order by point DESC, (maal_scoret-maal_gaaetind) DESC,maal_scoret DESC") or die(mysql_error());
 while($pulje = mysql_fetch_array($results)){
     $i++;
    $opdater = mysql_query("UPDATE hbf_puljer SET rangering = '$i' WHERE pulje_id = ".$pulje["pulje_id"]." AND turnerings_id = '$turnerings_id'");
 }
}

// Opdater konfliker
$sql = "SELECT *,
(SELECT pulje_id
    FROM hbf_puljer b
    WHERE a.`pulje_nr` = b.`pulje_nr`
    AND a.`point` = b.`point`
    AND (a.maal_scoret - a.maal_gaaetind) = ( b.maal_scoret - b.maal_gaaetind )
    AND a.`maal_scoret` = b.`maal_scoret`
    AND  `turnerings_id` =  '$turnerings_id'
    AND TYPE =  'p' limit 0,1
) as konfliknr
FROM hbf_puljer a
WHERE (
    SELECT COUNT( * )
    FROM hbf_puljer b
    WHERE a.`pulje_nr` = b.`pulje_nr`
    AND a.`point` = b.`point`
    AND (a.maal_scoret - a.maal_gaaetind) = ( b.maal_scoret - b.maal_gaaetind )
    AND a.`maal_scoret` = b.`maal_scoret`
    AND  `turnerings_id` =  '$turnerings_id'
    AND TYPE =  'p'
) >1
";
$result = mysql_query("$sql") or die(mysql_error());

while($spiller = mysql_fetch_array($result)){
    $opdater = mysql_query("UPDATE hbf_puljer SET rangering_konflikt = '".$spiller["konfliknr"]."' where pulje_id in (".$spiller["pulje_id"].") AND turnerings_id = '$turnerings_id'") or die(mysql_error());

}


// Tæller konflikter
$hent = mysql_query("SELECT DISTINCT `rangering_konflikt` as konflikt FROM `hbf_puljer` WHERE `turnerings_id` = '$turnerings_id' and rangering_konflikt != 0");
$konflikter = mysql_num_rows($hent);

if($konflikter < 2){
    header("location:turnering_kvart_rangkonflikt.php");
}

?>
<?php include_once("inc_header.php"); ?>
<body>

    <script type="text/javascript">

       $(document).ready(function(){

       });

    </script>

    <?php include_once("inc_navigation.php"); ?>

<section id="content">



<div class='g12'>
     <h2 style="margin-bottom:10px;">Konflikt</h2>
    <form action="turnering_kvart_rangkonflikt.php" method="post" data-ajax="false">


        <label>Hold i samme pulje der har samme point og målscore og derfor ikke kan rangeres</label>

<?php

        $q = 1;

        $hent = mysql_query("SELECT DISTINCT `rangering_konflikt` as konflikt FROM `hbf_puljer` WHERE `turnerings_id` = '$turnerings_id' and rangering_konflikt != 0");
        while($konflikt = mysql_fetch_array($hent)){
            echo "<fieldset><label></label>";
            echo "<section><label>Konflikt $q <br /><span>Sæt venligst hold i den korrekte rækkefølge</span></label><div>";
            echo "<select name='pulje_".$konflikt["konflikt"]."' class='multiple' id='multiple' multiple>";


            $result = mysql_query("SELECT * FROM hbf_puljer WHERE turnerings_id =  '$turnerings_id' AND rangering_konflikt = ".$konflikt["konflikt"]."") or die(mysql_error());
            while($spiller = mysql_fetch_array($result)){
                $spiller_id = $spiller["spiller_id"];
                echo "  <option value='$spiller_id'>".hentnavne($spiller_id,"-")."</option>";

            }

            echo "</select></div></section>";
            echo "</fieldset>";
            $q++;
        }
   ?>

   <fieldset>
<section>
        <div style="text-align:right;"><button class="submit green" id="submut">Videre</button></div>
</section>
</fieldset>

    </form>
</div>

</section>
</body>
<?php include_once("inc_footer.php"); ?>