<?php
require_once("../functions.php");

$kamp_id = $_GET["kamp_id"];
$query = mysqli_query($link,"SELECT * FROM hbf_kampe where kamp_id  = '$kamp_id'") or die(mysqli_error($link));
$row = mysql_fetch_array($query);
//$turnering = hentturnering();
$i = 0;
//echo $turneringsid;
?>

<label>Afslut kamp</label>

                    <section>
                        <label style="width:200px;"><? echo hentnavne($row["hold1"]," og "); ?></label>
                            <div style="width:200px;">
                                <input type="number" class="integer" id="resultathold1" name="resultathold1" required data-errortext="Angiv venligt en målscore" min="0" value="" />
                            </div>
                    </section>
                    <section>
                       <label style="width:200px;"><? echo hentnavne($row["hold2"]," og "); ?></label>
                            <div style="width:200px;">
                                <input type="number" class="integer" id="resultathold2" name="resultathold2" required data-errortext="Angiv venligt en målscore" min="0" value="" />
                            </div>
                    </section>