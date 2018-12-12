<?php
require_once("functions.php");
$turnering = hentturnering();
$turnerings_id = $turnering["turnering_id"];
$turnering = hentturnering();

$turnerings_id = $turnering["turnering_id"];
//if($turnering["kvartfinaler_start"] == 0){
    foreach($_POST as $index => $test){
       $ipuljer[] = $HTTP_POST_VARS[$index];
    }

    $alleredemed = dbarraytoarray($turnering["kvartfinaler"]);
    $kvartfinaler = $ipuljer;
    $kvartfinalerArr = array_unique(array_merge($alleredemed,$kvartfinaler));
    $kvartfinalertotal = arraytodbarray($kvartfinalerArr);
    $opdater = mysqli_query($link,"UPDATE hbf_turnering SET kvartfinaler = '$kvartfinalertotal' WHERE turnering_id = '$turnerings_id'");

    $antalhold = sumdbarray($turnering["puljer"]);
  
    // Laver kampe
    foreach($kvartfinalerArr as $spiller_id){
        $spiller = getpuljespiller($spiller_id,$turnerings_id);
        echo $spiller["spiller_id"]." - ".$spiller["pulje_nr"]."<br >";
    }

    // Finder de 8 første
    // Puljetræk



            $holdtilsql = join(',',$kvartfinalerArr);
    
    //header("location:turnering_kvart.php");
//}

die("dd");

?>
<?php include_once("inc_header.php"); ?>
<body>
   
    <script type="text/javascript">

       $(document).ready(function(){

       });

    </script>
    
    <?php include_once("inc_navigation.php"); ?>

<section id="content">
    <h2 style="margin-bottom:10px;"></h2>
<div class='g12'>
    <h4 style='margin-bottom:5px;'>Kvartfinaler</h4>
    <form action="turnering_kvart.php" method="post" data-ajax="false">
  

        <label>Hold der går videre på point (<? echo $mangler; ?> i alt)</label>
 <?
 //echo $alleredemedids;
 $q=$fejl=0;
 foreach($nyehold as $index=>$puljespil){
        
            if($q <$mangler){
                
                echo "<fieldset>";
                $spiller_id = $puljespil;
                $navne = hentnavne($spiller_id,"-");
                

                echo "<section><label>Valg ".($q+1)."</label><div>";
               
                $check = true;
                // er det den sidste
                if($q == ($mangler-1)){
                    $stop = true;
                    $i = 1;
                    while($stop){
                        $ekstranavne = hentnavne($nyehold[$q+$i],"-");
                        $test = getpuljespiller($nyehold[$q+$i],$turnerings_id);
                        // tester om resultatet er det samme
                        $spiller1 = getpuljespiller($nyehold[$q],$turnerings_id);
                        $spiller2 = getpuljespiller($nyehold[$q+$i],$turnerings_id);
                        $tjekens = puljeresultatens($spiller1,$spiller2);
                        if($tjekens){
                            $ekstranavne = hentnavne($spiller2["spiller_id"]," - ");
                            echo "<input type='radio'  name='pulje_$q' id='hold_".$spiller2["spiller_id"]."' value='".$spiller2["spiller_id"]."'><label for='hold_".$spiller2["spiller_id"]."'>$ekstranavne</label><br />";
                             $check = false;

                        }

                        if(!isset($nyehold[$q+$i+1])){
                            $stop = false;
                        }
                        $i++;
                    } 
                }
                 if($check){
                     $checked = "checked";
                 } else {
                     $checked = "";
                 }
                 echo "<input type='radio' $checked  name='pulje_$q' id='hold_$spiller_id' value='$spiller_id'><label for='hold_$spiller_id'>$navne</label><br />";
              

                $q++;
                echo "</div></section>";
                echo "</fieldset>";
            }

}
    ?>
	<div style="text-align:right;padding:20px;"><button class="submit green" id="submut">Videre</button></div>
					

    </form>


 <h2 style="margin-bottom:10px;">Beregnede puljer</h2>
 <div></div>
    <?
    $nummerOld = $nummer ="";
    $puljer = mysqli_query($link,"SELECT * FROM hbf_puljer WHERE turnerings_id = '".$turnering["turnering_id"]."' order by pulje_nr, point DESC, (maal_scoret-maal_gaaetind) DESC,maal_scoret DESC,kampe DESC,pulje_id DESC") or die(mysqli_error($link));

    while($pulje = mysqli_fetch_array($puljer)){
        
        $nummer = $pulje["pulje_nr"];

        if($nummer != $nummerOld && $nummerOld != ""){
            echo "</table>
            </div>";
        }
        if($nummer == "3"){
            echo "<div style='clear:both;'></div>";
        }
        if($nummer != $nummerOld){
            echo "
            <div class='g4'>
            <h4 style='margin-bottom:5px;'>Pulje ".($nummer+1)."</h4>
            <table>
            <tr><th>Hold</th><th>Kampe</th><th>Mål</th><th>Point</th></tr>";
        }

        echo "
        <tr><td>".$pulje["spiller_id"].hentnavne($pulje["spiller_id"])."</td><td>".$pulje["kampe"]."</td><td>".$pulje["maal_scoret"]." / ".$pulje["maal_gaaetind"]."</td><td>".$pulje["point"]."</td></tr>
        ";

        $nummerOld = $nummer;
    }
        echo "</table>
            </div>";
    ?>




</div>


</body>
<?php include_once("inc_footer.php"); ?>