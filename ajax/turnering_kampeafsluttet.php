<?php
require_once("../functions.php");

$turnering = hentturnering();
$turneringsid = $turnering["turnering_id"];
$i = 0;

 $kampe = mysqli_query($GLOBALS['link'],"SELECT * FROM hbf_kampe WHERE turnerings_id = '".$turneringsid."' and vinder <> '' and type = 'p' order by kampnr") or die(mysqli_error($GLOBALS['link']));
// <h4 style='margin-bottom:5px;'>Afsluttede kampe</h4>
 ?>
   
<?php
    if(mysqli_num_rows($kampe) > 0){
    echo "<div class='g12'><table>";
    $vinner="font-weight:bold;";
    while($kamp = mysqli_fetch_array($kampe)){
        $class1 = $class2 = "";
        if($kamp["vinder"] == $kamp["hold1"]){ $class1 = $vinner; } else if($kamp["vinder"] == $kamp["hold2"]){$class2 = $vinner;}
        echo "<tr style='background-color:#fff;'><td style='$class1'>".hentnavne($kamp["hold1"]," og ")."</td><td><a href='#afslutkampbox' rel='".$kamp["kamp_id"]."'  class='afslutkamp btn small'>".$kamp["resultat1"]." - ".$kamp["resultat2"]."</a></td><td style='$class2'>".hentnavne($kamp["hold2"]," og ")."</td></tr>";
    }
    echo "</table></div>";
    }
?>

<? if(mysqli_num_rows($kampe) < 1){ ?>
    <div class='g12'>Ingen afsluttede kampe endnu </div>
<? } ?>
				