<?php 
 require_once("functions.php");
 include_once("inc_header.php");
 $setting_bord = getsetting("borde");
 $setting_brugernavn = getsetting("brugernavn");
 $setting_password = getsetting("password");
 $setting_final_10 = getsetting("final_10");
 ?>

<body>

    <?php include_once("inc_navigation.php"); ?>
 
		<section id="content">

                    <div class="g12">
                    <ul class="breadcrumb">
                        
                       
                        <li><a href="indstillinger.php" class="active">Indstillinger</a></li>
                    </ul>
                        <div >
                       
        
                             <form class="formgemindstillinger" action="ajax/indstillinger_gem.php?type=borde"  onSuccess="function(data, textStatus, jqXHR)" method="post" autocomplete="off" ajax="true" confirmSend="true">
                                                

						<label>Indstillinger</label>
						
                                       <fieldset>
                                                <section>
                                                    <label>Borde</label>
                                                        <div><input type="text" id="borde" name="borde" required data-errortext="Angiv venlist mindst et bord" value="<? echo dbarraytostring($setting_bord); ?>">
							<span>Adskil hvert bord med et komma</span>
							</div>
						</section>
                                                <section>
                                                    <label>Brugernavn </label>
                                                        <div><input type="text" id="brugernavn" name="brugernavn" required data-errortext="Angiv venlist et brugernavn" value="<? echo dbarraytostring($setting_brugernavn); ?>">
							
							</div>
						</section>
                                                <section>
                                                    <label>Kodeord</label>
                                                        <div><input type="password" id="password" name="password" required data-errortext="Angiv venlist et password" value="<? echo dbarraytostring($setting_password); ?>">
							
							</div>
						</section>
                                                <section>
                                                    <label>Final 10 dato</label>
                                                        <div><input type="text" id="final_10" name="final_10"  value="<? echo dbarraytostring($setting_final_10); ?>">
							<span>Angiv dato på formen yyyy-mm-dd</span>
							</div>
						</section>
                                                <section>
                                                    <label>Rangliste</label>
                                                        <div>
                                                            <button class="submit yellow" id="gemspiller">Gen-beregn rangliste</button>
							</div>
						</section>
                                                <section>
							<div><button class="submit" id="gemspiller">Gem</button></div>
						</section>
                                        </fieldset>
                              </form>
                            <?php
                            $antalskabeloner = array("0","1","2","3","4","5","6");
                            foreach($antalskabeloner as $nummer){
                                
                                // Henter info
                                $setting = getsetting("rangliste".$nummer);
                                $setting = dbarraytoarray($setting);
                            ?>
                            <form class="formgemindstillinger" action="ajax/indstillinger_gem.php?type=rangliste&nummer=<?=$nummer?>" onSuccess="function(data, textStatus, jqXHR)" method="post" autocomplete="off" ajax="true" confirmSend="true">
                                       <fieldset>
                                           <label><? echo $nummer+1; ?>. Skabelon for rangliste point</label>
                                                <section>
                                                    <label>Navn</label>
                                                        <div><input type="text" id="navn" name="navn" value="<?=$setting[0]?>">
							<span>Hvis ranglisteskabelonen ikke har et navn vil den ikke være aktiv</span>
							</div>
						</section>
                                                <section>
                                                <label>Ordinært spil</label>
                                                        <div>
                                                            <input id="rangliste11" name="rangliste11" value="<?=$setting[1]?>" type="number" class="integer" title="Vinder" >
                                                            <input id="rangliste12" name="rangliste12" value="<?=$setting[2]?>" type="number" class="integer" title="Taber finale">
                                                            <input id="rangliste13" name="rangliste13" value="<?=$setting[3]?>" type="number" class="integer" title="Taber semifinale">
                                                            <input id="rangliste14" name="rangliste14" value="<?=$setting[4]?>" type="number" class="integer" title="Taber kvartfinale">
                                                   
                                                            
							</div>
						</section>
                                                <section>
                                                <label>Jays spil</label>
                                                        <div>
                                                            <input id="rangliste21" name="rangliste21" type="number" value="<?=$setting[5]?>" class="integer" title="Vinder">
                                                            <input id="rangliste22" name="rangliste22" type="number" value="<?=$setting[6]?>" class="integer" title="Taber finale">
                                                            <input id="rangliste23" name="rangliste23" type="number" value="<?=$setting[7]?>" class="integer" title="Taber semifinale">
                                                            <input id="rangliste24" name="rangliste24" type="number" value="<?=$setting[8]?>" class="integer" title="Taber kvartfinale">
                                                   
                                                           
							</div>
						</section>
                                                <section>
							<div><button class="submit" id="gemspiller">Gem</button></div>
						</section>
                                        </fieldset>
                            </form>
                            <? } ?>




                  
       
                        </div>
                        
		</div>

    </section>
      
</body>
<script type="text/javascript">
$(document).ready(function(){
       
       $('.formgemindstillinger').wl_Form({
            ajax:true,
            confirmSend:false,
            onSuccess: function(data, status){
              $.msg(data);

            }
       });
       
   });
</script>
<?php include_once("inc_footer.php"); ?>