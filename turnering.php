<?php
require_once("functions.php");


if(isset($_GET["new"])){
  $query = mysql_query("INSERT INTO hbf_turnering (date) values (now())");
  $id = mysql_insert_id();
  header("location: turnering.php");
}
// Henter seneste turnerings id
$result = mysql_query("SELECT turnering_id from hbf_turnering order by date desc limit 0,1") or die(mysql_error());
$row = mysql_fetch_array($result);
if(mysql_num_rows($result) > 0){
  $turneringsid = $row["turnering_id"];
}
?>
<?php include_once("inc_header.php"); ?>


<script type="text/javascript">


 $(document).ready(function(){
   hentspillerdata();
   $.get('ajax/hent_bruger_navne.php', function(data) {
    var brugere = data.split(',')

    $('#nummer_spiller1').autocomplete({
      source: brugere,
      select: function(event, ui) {
        ui.item.value = ui.item.value.substr(-8);
        console.log(ui.item.value);
      }
    });

    $('#nummer_spiller2').autocomplete({
      source: brugere,
      select: function(event, ui) {
        ui.item.value = ui.item.value.substr(-8);
      }
    });
  });


   $('#formnyspiller').wl_Form({ ajax:true,
    confirmSend:false,
    onSuccess: function(data, status){
      $.msg(data);
      $.fancybox.close();
      $('#formnyspiller').wl_Form('enable');
      henttabel();
    }
  });

   $("a#nybrugerlink").fancybox({
    'speedIn'		:	250,
    'speedOut'		:	0   ,
    'overlayShow'	:	true,

    'onClosed': function(){

      $('#formnyspiller').wl_Form('reset');

      ;},

      'enableEscapeButton':true
    });



   $('#formgemspiller').wl_Form({
    ajax:true,
    confirmSend:false,
    onSuccess: function(data, status,jqXHR){
      var turneringsid = $('.turneringsid').val();
      data = data.split("##");
      if(data[0] == "1"){
        $('#formgemspiller').wl_Form('reset');
        $('#nummer_spiller1').val('');
        $('#nummer_spiller2').val('');
      }

      $('#spiller_nummer1').focus();

      $.msg(data[1]);
      hentspillerdata();

    }
  });

   $('#formfjernspiller').wl_Form({
    ajax:true,
    confirmSend:false,
    onSuccess: function(data, status){

     $.msg(data);
     hentspillerdata();
     $.fancybox.close();
   }
 });


 });
function checkvalid(){

  var turneringsid = $('.turneringsid').val();
  var startturnering = true;

      // Gemmer point
      var or_vinder = $.trim($('#or_vinder').val()) != "" ? $('#or_vinder').val() : "0";
      var or_finale = $.trim($('#or_finale').val()) != "" ? $('#or_finale').val() : "0";
      var or_semi = $.trim($('#or_semi').val()) != "" ? $('#or_semi').val() : "0";
      var or_kvart = $.trim($('#or_kvart').val()) != "" ? $('#or_kvart').val() : "0";
      var jay_vinder = $.trim($('#jay_vinder').val()) != "" ? $('#jay_vinder').val() : "0";
      var jay_finale = $.trim($('#jay_finale').val()) != "" ? $('#jay_finale').val() : "0";
      var jay_semi = $.trim($('#jay_semi').val()) != "" ? $('#jay_semi').val() : "0";
      var jay_kvart = $.trim($('#jay_kvart').val()) != "" ? $('#jay_kvart').val() : "0";
      $.get('ajax/point_opdater.php?id='+turneringsid+'&p1='+or_vinder+'&p2='+or_finale+'&p3='+or_semi+'&p4='+or_kvart+'&p5='+jay_vinder+'&p6='+jay_finale+'&p7='+jay_semi+'&p8='+jay_kvart+'', function(data) {});

      // Check borde
      var borde =  $("#borde").val();
      if(borde == null){
        $("#bord_error").show();
        startturnering = false;
      } else {
        $("#bord_error").hide();
      }
      
      loese = $("#antalloese").val();
      
      if(loese > 0){
        $("#loese_error").show();
        startturnering = false;
      } else {
        $("#loese_error").hide();
      }

      // gemmer borde
      var bordialt = "";
      $('.valgtbord:selected').each(function(index, value) {
        bordialt = bordialt +'-'+ value.value;
      });
      
      $.get('ajax/borde_opdater.php?id='+turneringsid+'&bord='+bordialt+'', function(data) {});

      // Puljer
      var antalhold = $(".antalhold").val();
      var antalholdforige = $(".antalholdforige").val();


      if(antalhold != antalholdforige){
        $("#pulje_error").show();
        startturnering = false;
      } else {
       $("#pulje_error").hide();
     }

      // Antal hold
      if(antalhold < 2){
        $("#deltager_error").show();
        startturnering = false;
      } else {
        $("#deltager_error").hide();
      }

      if(startturnering == true){
        $("#startturnering").show();
      } else {
        $("#startturnering").hide();
      }
      //   alert("ddd");
    }
    function hentspillerdata(data){

     var turneringsid = $('.turneringsid').val();

     if(turneringsid > 0){
       $.get('ajax/spillere_hent.php?id='+turneringsid+'', function(data) {
        $('.tilmeldtehold').html(data);

        $(".sletspillerlink").fancybox({

          /* This is basic - uses default settings */
          'speedIn'		:	250,
          'speedOut'		:	0   ,
          'overlayShow'	:	true,
          'onComplete':function(data,index){
           totalid = $(data).attr("id");
           totalid = totalid.split('-');
           feltid = totalid[0];
           felttype= totalid[1];
           $("#spillerid").val(feltid);
           $("#typeid").val(felttype);
           hentspillerdata(data);

         },
         'enableEscapeButton':true
       });

        $('#fortryd').click(function() {
         $.fancybox.close();
       });
      });
     }

   }

   function startturnering(){
     $.get('ajax/startturnering.php', function(data) {
      $('.turneringsid').val(data);

      hentspillerdata(data);
    });


   }


   function valgloesepiller(spillerid) {
    var turneringsid = $('.turneringsid').val();

    $.get('ajax/spillere_hent.php?id='+turneringsid+'&kunloese='+spillerid+'', function(data) {

      $('#data_loesspiller').html(data);

      $.fancybox(
      {

        'speedIn'		:	250,
        'speedOut'		:	0   ,
        'overlayShow'	:	true,
                    //'onStart': function(){alert("ttt")},
                    'enableEscapeButton':true,
                    'content':$("#data_loesspiller").html()
                  }
                  );
    });


  };




  function valgpartner(sp1,sp2){

    $.get('ajax/sethold.php?sp1='+sp1+'&sp2='+sp2+'', function(data) {
      $.fancybox.close();
      hentspillerdata();
      return false;
    });

  }

  function setpoint(p1,p2,p3,p4,p5,p6,p7,p8){

    $('#or_vinder').val(p1);
    $('#or_finale').val(p2);
    $('#or_semi').val(p3);
    $('#or_kvart').val(p4);

    $('#jay_vinder').val(p5);
    $('#jay_finale').val(p6);
    $('#jay_semi').val(p7);
    $('#jay_kvart').val(p8);


  }
  function puljer_start(){
   var turneringsid = $('.turneringsid').val();
   var antalhold = $(".antalhold").val();

   var antalholdforige = $(".antalholdforige").val();

   $(".slider").attr("data-value",'10');
   var antalpuljer = $("#antalpuljer").val();

   if(antalpuljer == "" || (antalhold != antalholdforige)){
     $.get('ajax/puljer_hentslider.php?id='+turneringsid+'&antalhold='+antalhold+'', function(data) {
      $('#puljeslider').html(data);

      $( ".slider" ).wl_Slider({

        change: function(event, ui) {
                        //alert(event);

                        slidervalue = ui.value;
                        
                        sliderid = this.id;

                        $.get('ajax/puljer_opdater.php?id='+turneringsid+'&antal='+slidervalue+'', function(data) {  });
                        $("#antalpuljer").val(slidervalue);

                        $.get('ajax/puljer_hent.php?id='+turneringsid+'&antal='+slidervalue+'&antalhold='+antalhold+'', function(data) {
                          $('#data_puljer').html(data);
                        });
                        $(".antalholdforige").val(antalhold);
                      }
                    });
    });
   }
 }





</script>
<body>
  <?php include_once("inc_navigation.php"); ?>


  <div style="display:none">
    <div id="data_loesspiller">

    </div>

    <div id="data_sletspiller">
     <form id="formfjernspiller" action="ajax/spiller_slet.php"  onSuccess="function(data, textStatus, jqXHR)" method="post" autocomplete="off" ajax="true" confirmSend="true">

       <fieldset>
        <label>Fjern hold</label>
        <section>
          <div style="width:300px;padding-left:20px;">
            <input type="hidden" name="turneringsid" value="<?php if(isset($turneringsid)){ echo $turneringsid;} ?>">
            <input type="hidden" name="spillerid" id="spillerid" value="-">
            <input type="hidden" name="typeid" id="typeid" value="-">
            <button class="red submit" id="sletspiller">Fjern hold</button>
            <button id="fortryd">fortryd</button>
          </div>     
        </section>
      </fieldset>
    </form>
  </div>


</div>
<div style="display:none;">
  <div id="nybruger" >
   <form id="formnyspiller" action="ajax/bruger_ny.php"  onSuccess="function(data, textStatus, jqXHR)" method="post" autocomplete="off" ajax="true" confirmSend="true">


    <label>Opret bruger</label>

    <fieldset>
      <section>
        <label>Navn</label>
        <div><input type="text" id="navn" name="navn" required data-errortext="Giv venligst brugeren et navn">

        </div>
      </section>
      <section>
        <label>Telefon</label>
        <div><input type="text" id="telefon" name="telefon" required data-errortext="Giv venligst brugeren et telefonnummer">

        </div>
      </section>
      <section>
       <div><button class="submit" id="gemspiller">Gem</button> </div>
     </section>

   </fieldset>
 </form>
</div>
</div>

<section id="content">


  <h1>Turneringsindstillinger</h1>
  <p></p>
  <div style="width:490px;">
    <ul class="breadcrumb" data-connect="breadcrumbcontent">

     <li><a href="#">Deltagere</a></li>
     <li><a href="#" onclick="javascript:puljer_start();">Puljer</a></li>
     <li><a href="#">Point</a></li>
     <li><a href="#">Borde</a></li>
     <li><a href="#" onclick="javascript:checkvalid();">Start</a></li>
   </ul>
   <div id="breadcrumbcontent">

     <div>
      <h3>Tilmelding</h3>
      <p>
       Tilmeld spillere i hold eller enkeltvis
     </p>
     <div  style="margin-top:20px;margin-bottom: 20px;margin-left:10px;"><a class='btn i_user_2 icon yellow' id="nybrugerlink" href="#nybruger">Opret bruger</a></div>

     <form id="formgemspiller" action="ajax/spiller_gem.php"  onSuccess="function(data, textStatus, jqXHR)" method="post" autocomplete="off" ajax="true" confirmSend="true">
      <fieldset>
        <input type="hidden" class="turneringsid" name="turneringsid" value="<?php if(isset($turneringsid)){ echo $turneringsid;} ?>">

        <section>
          <label for="nummer_spiller1">Spiller 1</label>
          <div><input type="text" tabindex="1" id="nummer_spiller1" name="nummer_spiller1" placeholder="Indtast telefonnummer på spiller 1" required >

           <div style="height:10px;"></div>
           <input type="checkbox" id="betalt_spiller1" checked="checked" value ="1" name="betalt_spiller1" ><label for="betalt_spiller1" >Betalt</label>
         </div>
       </section>
       <section>
        <label for="nummer_spiller2">Spiller 2</label>
        <div><input type="text" tabindex="2" id="nummer_spiller2" name="nummer_spiller2" placeholder="Indtast telefonnummer på spiller 2">
         <div style="height:10px;"></div>
         <input type="checkbox" id="betalt_spiller2" checked="checked" value ="1" name="betalt_spiller2" ><label for="betalt_spiller2" >Betalt</label>
       </div>
     </section>
     <section>
       <div><button tabindex="3" class="submit" id="gemspiller">Gem</button></div>
     </section>

   </fieldset>
 </form>
 <hr>
 <div class="fr"><a class="btn prev icon i_arrow_left">Start</a><a class="btn next icon i_arrow_right" >Puljer</a></div>

 <div style="clear:both;">
  <h3>Tilmeldte spillere</h3>
  <hr>
  <div class="tilmeldtehold">
    Ingen tilmeldte spillere


  </div>

  <div style="height:50px;"></div>
</div>
</div>
<form action="" method="post">
 <div>
  <h3>Puljer</h3>
  <p style="margin:0;padding:0;">Vælg antal af puljer ved at trække i slideren.</p>




  <section>
   <div style="text-align:center;" id="puljeslider">

   </div>
 </section>
 <div id="data_puljer">


 </div>

 <hr>
 <div class="fr"  style="margin-top:10px;"><a class="btn prev icon i_arrow_left">Deltagere</a><a class="btn next icon i_arrow_right">Point</a></div>
</div>
</form>
<form action="turnering_start.php" method="post">
 <div>
  <h3>Point</h3>
  <p>
   Indsæt de rangliste der skal fordeles når turneringen er slut

   <div style="margin-bottom:10px;">

    <?php
                                                    // Indsætter borde
    for ( $counter = 0; $counter <= 10; $counter += 1) {
      $point = getsetting("rangliste".$counter."");
      $point = dbarraytoarray($point);
      $points = "";
      foreach ($point as $index => $p) {
        if($index != 0){
         if($p == ""){$p = 0;}
         $points .= $p.",";
       } else {
         $navn = $p;
       }
     }
     $points = substr($points, 0,-1);
     $pointtotal = " <a class='btn' onclick='setpoint($points);'>".$navn."</a>";
     if($navn != ""){
      echo $pointtotal;
    }

  }


  ?>                        





</div>


<fieldset>

  <label>Ordinært spil</label>
  <section>
    <label for="integer">Vinder</label>
    <div><input id="or_vinder" name="or_vinder" type="number" class="integer point" value="0"></div>
  </section>
  <section>
    <label for="integer">Taber <br />finale</label>
    <div><input id="or_finale" name="or_finale" type="number" class="integer point" value="0"></div>
  </section>
  <section>
    <label for="integer">Taber <br />semifinale</label>
    <div><input id="or_semi" name="or_semi" type="number" class="integer point" value="0"></div>
  </section>
  <section>
    <label for="integer">Taber <br />kvartfinale</label>
    <div><input id="or_kvart" name="or_kvart" type="number" class="integer point" value="0"></div>
  </section>
</fieldset>
<fieldset>

  <label>Jays</label>
  <section>
    <label for="integer">Vinder</label>
    <div><input id="jay_vinder" name="jay_vinder" type="number" class="integer point" value="0"></div>
  </section>
  <section>
    <label for="integer">Taber <br />finale</label>
    <div><input id="jay_finale" name="jay_finale" type="number" class="integer point" value="0"></div>
  </section>
  <section>
    <label for="integer">Taber <br />semifinale</label>
    <div><input id="jay_semi" name="jay_semi" type="number" class="integer point" value="0"></div>
  </section>
  <section>
    <label for="integer">Taber <br />kvartfinale</label>
    <div><input id="jay_kvart" name="jay_kvart" type="number" class="integer point" value="0"></div>
  </section>


</fieldset>


<hr>
<div class="fr"  style="margin-top:10px;"><a class="btn prev icon i_arrow_left">Puljer</a><a class="btn next icon i_arrow_right">Borde</a></div>
</div>
</form>
<form action="turnering_start.php" method="post">
 <div>

  <input type="hidden" class="antalholdforige" name="antalholdforige" id="antalholdforige" value="0">
  <input type="hidden" name="antalpuljer" id="antalpuljer" value="">
  <h3>Borde</h3>
  <p>
   Vælg de borde der spilles på i turneringen
 </p>

 <fieldset>
  <section>
   <label for="borde">Borde<br><span>Brug ctrl + shift for at vælge flere borde samtidig.</span></label>
   <div>

    <select name="borde" class="borde" id="borde" multiple>
      <?php
                                                                        // Indsætter borde

      $borde = getsetting("borde");
      $borde = dbarraytoarray($borde);
      $i = 0;
      foreach ($borde as $index => $bord){
        echo "<option value='$index' class='valgtbord'>$bord</option>";

      }
      ?>
    </select>
  </div>
</section>

</fieldset>

<hr>
<div class="fr"  style="margin-top:10px;"><a class="btn prev icon i_arrow_left">Point</a><a class="btn next icon i_arrow_right">Start</a></div>

</div>
</form>

<div>
  <form action="turnering_start.php" method="post"  data-ajax="false">
    <p style="margin:0;padding:0;">Bemærk at når turneringen startes kan indstillingerne ikke længere laves om.</p>
    <input type="hidden" name="turneringsid" value="<?php if(isset($turneringsid)){ echo $turneringsid;} ?>">
    <input type="hidden" class="antalhold" name="antalhold" id="antalhold" value="0">
    <div class="alert warning" data-sticky="true" id="pulje_error" style="display:none;">Besøg puljerne for at sætte dem korrekt op</div>
    <div class="alert warning" data-sticky="true" id="bord_error" style="display:none;">Gå tilbage og vælg mindst et bord</div>
    <div class="alert warning" data-sticky="true" id="deltager_error" style="display:none;">Denne turnering mangler deltagere</div>
    <div class="alert warning" data-sticky="true" id="loese_error" style="display:none;">Turneringen kan ikke startes med løse deltagere</div>

    <button class="big green submit" id="startturnering" style="margin-top:15px;display:none;">Start turnering</button>
    <hr>
    <div class="fr"><a class="btn prev icon i_arrow_left">Borde</a></div>
  </form>
</div>

</div>
</div>

</section>

</body>
<?php include_once("inc_footer.php"); ?>