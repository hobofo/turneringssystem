<?php
require_once("../functions.php");


$turnering = hentturnering();

$turneringsid = $turnering["turnering_id"];
$i = 0;
 $kampe = mysqli_query($link,"SELECT * FROM hbf_kampe WHERE turnerings_id = '".$turneringsid."' and bord = '' and type = 'p' order by kampnr") or die(mysqli_error($link));
 $antal = mysqli_num_rows($kampe);
 // <h4 style='margin-bottom:5px;'>Kommende kampe (<?=$antal)</h4>
?>



    <?php
   if(mysqli_num_rows($kampe) > 0){
   echo "<div class='g12'><table>";
   $class="";
   while($kamp = mysqli_fetch_array($kampe)){
        if($class==""){ $class = ""; } else {$class="";}
        echo "<tr style='background-color:#fff;'><td class='$class'>".hentnavne($kamp["hold1"]," og ")." </td></tr>";
        echo "<tr style='background-color:#fff;'><td class='$class'>".hentnavne($kamp["hold2"]," og ")."</td></tr>";
        echo "<tr style=''><td style='background-color:#fff;'><a href='#startkampbox' class='btn small startkamp' rel='".$kamp["kamp_id"]."'>Sæt i gang</a></td></tr>";
        echo "<tr style=''><td style='border:none;'></td></tr>";

    }
    echo "</table></div>";
   }
   

    ?>

<? if(mysqli_num_rows($kampe) < 1){ ?>
<div class='g12'>Ingen kommende kampe.<br /> </div>
<? } ?>


<?
$kampe = mysqli_query($link,"SELECT * FROM hbf_kampe WHERE turnerings_id = '".$turneringsid."' and vinder = '' AND type = 'p'") or die(mysqli_error($link));
if(mysqli_num_rows($kampe) < 1){

?>
<br /><a href="turnering_kvart_puljekonflikt.php" class="btn green">Gå til finaler</a></div>
<? } ?>