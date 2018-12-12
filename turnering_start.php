<?php
require_once("functions.php");
$turnering = hentturnering();

if(isset($_POST["turneringsid"])){
   
    // Info
    $turneringsid = $turnering["turnering_id"];
    //$antalhold = $_POST["antalhold"];
   
    $turnering = hentturnering();
    $antalhold = sumdbarray($turnering["puljer"]);
    $puljer = $turnering["puljer"];

    $puljerArray = dbarraytoarray($puljer);


    $pulje = $point = 0;

    // Sætter ind i puljer
    $results = mysqli_query($link,"DELETE FROM hbf_puljer WHERE turnerings_id = '$turneringsid'") or die(mysqli_error($link));
    $result = mysqli_query($link,"SELECT *, rang as ranglistetotal FROM `hbf_spillere` a where turnering_id = '$turneringsid' and primaer = '1' order by ranglistetotal DESC,spiller_id");
    while($row = mysqli_fetch_array($result)){
        $spiller_id = $row["spiller_id"];
        $holdpulje = $pulje;
        if(isset($puljerArray[$pulje+1])){ $pulje++; } else {$pulje = 0;}      
        $results = mysqli_query($link,"INSERT INTO hbf_puljer (turnerings_id,pulje_nr,spiller_id,point,kampe,type) values ('$turneringsid','$holdpulje','$spiller_id','$point','0','p')") or die(mysqli_error($link));   
    }

    // Sætter ind i kampe
    $kampnr = $i = 0;
    $results = mysqli_query($link,"DELETE FROM hbf_kampe WHERE turnerings_id = '$turneringsid'") or die(mysqli_error($link));
    $result = mysqli_query($link,"SELECT *  FROM `hbf_puljer` where turnerings_id = '$turneringsid' ORDER BY pulje_id DESC") or die(mysqli_error($link));

    while($row = mysqli_fetch_array($result)){
        $spiller_id =  $row["spiller_id"];
        $pulje_nr =  $row["pulje_nr"];
        $i++;

        $result_partner = mysqli_query($link,"SELECT * FROM `hbf_puljer` where turnerings_id = '$turneringsid' and pulje_nr = '$pulje_nr' and spiller_id <> '$spiller_id' order by pulje_id DESC") or die(mysqli_error($link));
        while($rowp = mysqli_fetch_array($result_partner)){
            $modstander =  $rowp["spiller_id"];
            // Ser om kombinationen findes
            $komp = mysqli_query($link,"SELECT *  FROM `hbf_kampe` where turnerings_id = '$turneringsid'  and ((hold1 = '$spiller_id' AND hold2 = '$modstander') OR (hold1 = '$modstander' AND hold2 = '$spiller_id') )") or die(mysqli_error($link));
            if(mysqli_num_rows($komp)< 1){
                    $rang1 = hentrang($spiller_id);
                    $rang2 = hentrang($modstander);
                    $insert = mysqli_query($link,"INSERT INTO hbf_kampe (turnerings_id,hold1,hold2,rang1,rang2,type,kampnr,pulje,parameter) values ('$turneringsid','$spiller_id','$modstander','$rang1','$rang2','p','$kampnr','$pulje_nr','$i')")or die(mysqli_error($link));
            }
        }

    }
      
    $update = mysqli_query($link,"UPDATE hbf_kampe SET kampnr = '0' WHERE turnerings_id = '$turneringsid' AND vinder = '' AND type = 'p'")or die(mysqli_error($link));
    updatekampnr();
    header("location:turnering_start.php?slut=1");
}

$turnering = hentturnering();
$puljer = $turnering["puljer"];
$puljerArray = dbarraytoarray($puljer);

$turnerings_id =$turnering["turnering_id"];
$borde = dbarraytostring($turnering["borde"]);

if(!isset($opdater)){
    $opdater = false;
}

?>
<?php include_once("inc_header.php"); ?>
<body>
   
    <script type="text/javascript">

    $(document).ready(function(){


        hentpuljer();
        hentkampe();
        hentkampeafsluttet();
        hentbetalinger();
        <?php if($opdater){ ?>
        var refreshId = setInterval(function()
        {
            hentpuljer();
            hentkampe();
            hentkampeafsluttet();
            hentbetalinger();
              // tester om kvartfinaler er startet
             $.ajax({
                    url: 'ajax/check_kvartfinaler.php',
                    success: function(data){
                     if(data == "1"){
                         window.location = "turnering_live.php?kvart=true"
                     }
                    }

                    });

        }, 5000);
        
        <?php } ?>
    });


    function hentbetalinger(){

         $.ajax({
                    url: 'ajax/betaling.php',
                    success: function(data){
                      $('#data_betaling').html(data);
                    },
                    complete: function(){

                    }
                    });
        }

       function saetbetalt(spillerid){
      
        $.ajax({
                    url: 'ajax/betaling_set.php?spiller_id='+spillerid,
                    success: function(data){$.msg(data);},
                    complete: function(){hentbetalinger();}
         });
         

        return false;
       }
        function startkamp(kamp, bord){
            
            $.fancybox.close();
           
            $.get('ajax/turnering_skiftbord.php?kamp='+kamp+'&bord='+bord+'', function(data) {
                 $.msg(data);
                 hentkampe();
                 hentkampekommende();
            });
        }
        function hentkampekommende(){
            var turneringsid = $('.turneringsid').val();

            $.get('ajax/turnering_kampekommende.php?id='+turneringsid+'', function(data) {
                $('#data_kampekommende').html(data);
                //aktiverkampafslutning();

                    $("a.startkamp").fancybox({
                        'speedIn'		:	250,
                        'speedOut'		:	0   ,
                        'showNavArrows' : false,
                        'overlayShow'	:	true,
                        'onStart': function(data){
                            var kamp_id = $(data).attr("rel");
                            $("#startkamp_id").val(kamp_id);
                             $.get('ajax/turnering_kampstartfields.php?kamp_id='+kamp_id+'', function(data) {
                                
                                $('#startsetkamp').html(data);
                             });
                        ;},
                        'onClosed': function(){
                           
                        ;},

                    'enableEscapeButton':true
                  });


                    $('#startkamp').wl_Form({
                    ajax:true,
                    confirmSend:false,
                    onSuccess: function(data, status){
                                                hentpuljer();
                                                hentkampe();
                                                hentkampekommende();
                                                hentkampeafsluttet();
                                                $.msg(data);
                                                //hentspillerdata();
                                                $.fancybox.close();
                                        }
                    });
                    $('.fortryd').click(function() {
                       $.fancybox.close();
                    });


                });

        }

        function aktiverkampafslutning(){
            var turneringsid = $('.turneringsid').val();
            $.get('ajax/turnering_kampe.php?id='+turneringsid+'', function(data) {
                $('#data_kampe').html(data);

                $("a.afslutkamp").fancybox({
                    'speedIn'		:	250,
                    'speedOut'		:	0   ,
                    'showNavArrows' : false,
                    'overlayShow'	:	true,
                    'onStart': function(data){
                        var kamp_id = $(data).attr("rel");
                        $("#kamp_id").val(kamp_id);
                         $.get('ajax/turnering_kampafslutfields.php?kamp_id='+kamp_id+'', function(data) {
                            $('#afslutsetkamp').html(data);

                            $('#resultathold1').focus();
                         });
                    ;},
                    'onClosed': function(){
                        $('#afslutkamp').wl_Form('reset');
                    ;},

                'enableEscapeButton':true
              });
            });

        }
        function hentkampe(){
            
            aktiverkampafslutning();
            hentkampekommende();
            $('#afslutkamp').wl_Form({
            ajax:true,
            confirmSend:false,
            onSuccess: function(data, status){
                                        hentpuljer();
                                        hentkampe();
                                        hentkampekommende();
                                        hentkampeafsluttet();
					$.msg(data);
                                        //hentspillerdata();
                                        $.fancybox.close();
				}
            });
            $('.fortryd').click(function() {
               $.fancybox.close();
            });

        }
        function hentkampeafsluttet(){
            var turneringsid = $('.turneringsid').val();
             $.ajax({
  				url: 'ajax/turnering_kampeafsluttet.php?id='+turneringsid+'',
  				success: function(data){

  				  $('#data_kampeafsluttet').html(data);
  				},
  				complete: function(){
  				  aktiverkampafslutning();
  				}
				});
        }

        function hentpuljer(){
            var turneringsid = $('.turneringsid').val();
            $.get('ajax/turnering_puljer.php?id='+turneringsid+'', function(data) {
                $('#data_puljer').html(data);
                sentilmelding();
            });
        }
        function afslutkamp(hold1,hold2){
            hentpuljer();
            hentkampe();
            hentkampekommende();
            hentkampeafsluttet();
        }

        function sentilmelding(){

            $("a#nybrugerlink").fancybox({
                    'speedIn'		:	250,
                    'speedOut'		:	0   ,
                    'overlayShow'	:	true,
                    'onClosed': function(){

                    $('#formgemspiller').wl_Form('reset');

                    ;},

                    'enableEscapeButton':true
            });
            $('#formgemspiller').wl_Form({
                ajax:true,
                confirmSend:false,
                onSuccess: function(data, status,jqXHR){
                //var turneringsid = $('.turneringsid').val();
                /*data = data.split("##");
                if(data[0] == "1"){
                    $('#nummer_spiller1').val('');
                    $('#nummer_spiller2').val('');
                }
                $('#spiller_nummer1').focus();
                */
                $('#formgemspiller').wl_Form('reset');
                
                $.fancybox.close();
                $.msg(data);
                hentpuljer();
                hentkampe();
                hentkampekommende();
                hentbetalinger();
				}
            });
        }
    </script>
    
    <?php include_once("inc_navigation.php"); ?>
<div style="display:none;">
                        <div id="nybruger" >
                               <form id="formgemspiller" action="ajax/spiller_gem_late.php"  onSuccess="function(data, textStatus, jqXHR)" method="post" autocomplete="off" ajax="true" confirmSend="true">
                                                <fieldset>
                                                <input type="hidden" class="turneringsid" name="turneringsid" value="<?php if(isset($turneringsid)){ echo $turneringsid;} ?>">

                                                <section>
                                                        <label for="nummer_spiller1">Spiller 1</label>
							<div><input type="text" id="nummer_spiller1" name="nummer_spiller1" placeholder="Indtast telefonnummer på spiller 1" required >

                                                         <div style="height:10px;"></div>
                                                             <input type="checkbox" id="betalt_spiller1" value ="1" name="betalt_spiller1" ><label for="betalt_spiller1" >Betalt</label>
                                                         </div>
						</section>
                                                <section>
                                                        <label for="nummer_spiller2">Spiller 2</label>
							<div><input type="text" id="nummer_spiller2" name="nummer_spiller2" placeholder="Indtast telefonnummer på spiller 2">
							<div style="height:10px;"></div>
                                                             <input type="checkbox" id="betalt_spiller2" value ="1" name="betalt_spiller2" ><label for="betalt_spiller2" >Betalt</label>
                                                         </div>
						</section>
                                                <section>
							<div><button class="submit" id="gemspiller">Gem</button></div>
						</section>

                                                </fieldset>
                               </form>
                        </div>
          </div>
<div style="display:none;">
    <div id="afslutkampbox" style="width:450px;" >
       <form id="afslutkamp" action="ajax/turnering_kampafslut.php"  onSuccess="function(data, textStatus, jqXHR)" method="post" autocomplete="off" ajax="true" confirmSend="true">
            
            <fieldset style="width:430px;height:175px;" id="afslutsetkamp">
           
            </fieldset>
           <fieldset style="width:430px;">
                    <input type="hidden" name="kamp_id" id="kamp_id" value="">
                <section>
                    <div style="width:200px;">
                        <button class="green submit" id="sletspiller">Godkend</button>
                        <button class="fortryd">fortryd</button>
                        <input type="hidden" name="turneringsid" value="<?php if(isset($turnerings_id)){ echo $turnerings_id;} ?>">
                    </div>
                </section>

        
           </fieldset>
       </form>
    </div>
</div>

<div style="display:none;">
    <div id="startkampbox" style="width:450px;" >
       <form id="startkamp" action="ajax/turnering_valgbord.php"  onSuccess="function(data, textStatus, jqXHR)" method="post" autocomplete="off" ajax="true" confirmSend="true">
            <input type="hidden" name="startkamp_id" id="kamp_id" value="">
            <fieldset style="width:430px;height:175px;" id="startsetkamp">

            </fieldset>
      
          
       </form>
    </div>
</div>

<section id="content">
    <div class="g12 nodrop">
		<input type="hidden" name="turneringsid" class="turneringsid" value="<?=$turnerings_id?>" />
    </div>
    

		<div class="g12 widgets">
                        <div class="widget" data-icon="blocks_images">
					<h3 class="handle">Puljer</h3>
					<div>
                                            <div class='g12'  id="data_puljer" class="puljer"></div>
                                            <div style="clear:both"></div>
					</div>
			</div>
		</div>
                <div class="g6 widgets" >
                        <div class="widget" data-icon="running_man">
					<h3 class="handle">Nuværende kampe</h3>
					<div>
                                            <div class='g12' id="data_kampe" class="kampe"></div>
                                            <div style="clear:both"></div>
					</div>
			</div>

                        <div class="widget" data-icon="piggy_bank">
					<h3 class="handle">Manglende betaling</h3>
					<div>
                                            <div class='g12' id="data_betaling" class="betaling"></div>
                                            <div style="clear:both"></div>
					</div>
			</div>
                
                </div>
                
                <div class="g6 widgets">
                        <div class="widget" data-icon="megaphone">
					<h3 class="handle">Kommende kampe</h3>
					<div>
                                            <div class='g12' id="data_kampekommende" class="kampekommende"></div>
                                            <div style="clear:both"></div>
					</div>
			</div>
		</div>
                <div class="g12 widgets">
                        <div class="widget" data-icon="table">
					<h3 class="handle">Afsluttede kampe</h3>
					<div>
                                            <div class='g12' id="data_kampeafsluttet"></div>
                                            <div style="clear:both"></div>
					</div>
			</div>
		</div>







</section>
</body>
<?php include_once("inc_footer.php"); ?>