<?php
require_once("../functions.php");

$turnering = hentturnering();
$turneringsid = $turnering["turnering_id"];
$i = 0;

$betaling = mysqli_query($link,"SELECT * FROM hbf_spillere left join hbf_brugere on hbf_brugere.bruger_id  = hbf_spillere.spiller WHERE turnering_id = '".$turneringsid."' and betalt <> 1 order by hbf_brugere.navn") or die(mysqli_error($link));

if(mysqli_num_rows($betaling) > 0){
echo "<div class='g12'>";
   $class="";
   $i = 0;
   while($spiller = mysql_fetch_array($betaling)){
        if($class==""){ $class = ""; } else {$class="";}
        $i++;
        $bruger = hentbruger($spiller["spiller"]);
        if($i == 1){echo "<div style='clear:both'></div>";}
        echo "<div class='g4' style='margin-top:0;padding-top:0;margin-bottom:0;padding-bottom:0;'>";
        echo "<table>";
            echo "<tr style='background-color:#fff;'><td class='$class'>".$bruger["navn"]."</td></tr>";
            echo "<tr style=''><td style='background-color:#fff;'><a href='#' onClick='saetbetalt(".$spiller["spiller_id"].");return false;' class='btn small startkamp' rel='".$spiller["spiller_id"]."'>Betalt</a></td></tr>";
            echo "<tr style=''><td style='border:none;'></td></tr>";
        echo "</table>";
        echo "</div>";

        if($i == 3){
            $i = 0;
        }
   }
   

 echo "</div>";
}


?>

<? if(mysqli_num_rows($betaling) < 1){ ?>
<div class='g12'>Ingen mangler af betale.<br /></div>
<? } ?>