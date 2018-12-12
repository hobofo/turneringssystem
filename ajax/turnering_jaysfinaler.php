<?php
require_once("../functions.php");



$i = 0;
$turnerings_id = $_GET["id"];

                // Sidste kamp
                $kampe = mysqli_query($link,"SELECT * FROM hbf_kampe WHERE turnerings_id = '".$turnerings_id."'  and type <> 'p' and hold1 > 0 and hold2 > 0 order by startet DESC, bord desc") or die(mysql_error());
                $sidstekamp = mysql_fetch_array($kampe);

                $camp = "background-image:url('css/images/icons/light/cup.png'); background-repeat:no-repeat;";
                $typer = array("jk","js","jf");
                $tekster = array("Kvartfinaler","Semifinaler","Finale");
                foreach($typer as $index=>$type){
                    echo "<div class='g4'><h4 style='margin-bottom:5px;'>".$tekster[$index]."</h4>";

                        $results = mysqli_query($link,"SELECT * FROM hbf_kampe WHERE turnerings_id = '$turnerings_id' AND type = '$type' order by kampnr") or die(mysql_error());
                        while($row = mysql_fetch_array($results)){
                            if($row["hold2"] != 0){$navne1 = hentnavne($row["hold2"]," - ");} else {$navne1 = "-";}
                            if($row["hold1"] != 0){$navne2 = hentnavne($row["hold1"]," - ");} else {$navne2 = "-";}
                            $camp1 = $camp2 = "";
                            $vinder = false;
                            if($row["vinder"] == $row["hold1"]){ $camp1 = $camp; $vinder = true; }
                            if($row["vinder"] == $row["hold2"]){ $camp2 = $camp; $vinder = true;}
                            if($row["bord"] != "" && !$vinder){
                                $bord = settingNumberToName('borde',$row["bord"]);
                            } else {
                                $bord = "-";
                            }
                            
                           if($sidstekamp["kamp_id"] == $row["kamp_id"] && !($bord == "-")){
                              $baggrund = "background-color:#a2e8a2;font-weight:bold;";
                            } else {
                              $baggrund = "background-image:url(css/light/images/paper_02.png);font-weight:bold;";
                            }
                            // rangering
                            $r1 = $r2 = "";
                            if(isset($_GET["rang"])){
                                $rang_qr1 = mysqli_query($link,"Select * from hbf_puljer where turnerings_id = '".$turnerings_id."' and spiller_id = '".$row["hold1"]."'") or die(mysql_error());
                                $rowrang1 = mysql_fetch_array($rang_qr1);
                                $rang_qr2 = mysqli_query($link,"Select * from hbf_puljer where turnerings_id = '".$turnerings_id."' and spiller_id = '".$row["hold2"]."'") or die(mysql_error());
                                $rowrang2 = mysql_fetch_array($rang_qr2);
                                if($rowrang1["rangering_total"] > 0){
                                    $r1 = " (".$rowrang1["rangering_total"].")";
                                }
                                if($rowrang2["rangering_total"] > 0){
                                    $r2 = " (".$rowrang2["rangering_total"].")";
                                }
                            }
                            echo "
                            <table>
                                <tr style='$baggrund'><td style='$baggrund'>$bord</td></tr></th>
                                <tr style='background-color:#fff;'><td style=\"$camp1\">$navne2 $r1</td></tr>
                                <tr style='background-color:#fff;'><td style=\"$camp2\">$navne1 $r2</td></tr>
                                <tr style='background-color:#fff;'>
                                    <td>";
                                     if($row["hold2"] != 0 && $row["hold1"] != 0) { echo  " <a href='#afslutkampbox' id='afslutkamp' rel='".$row["kamp_id"]."' class='btn small'>Afslut kamp</a>"; } else { echo  " <a href='javascript:void(0)' id='' rel='' class='btn small'>-</a>"; }
                                 echo" </td>
                                </tr>
                            </table>
                            ";
                        }

                    echo "</div>";
                }