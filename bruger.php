<?php 
require_once("functions.php");
include_once("inc_header.php");

$bruger_id = $_GET["id"];
$bruger = hentbruger($bruger_id);




?>

<body>

  <?php include_once("inc_navigation.php"); ?>

  <section id="content">

    <div class="g12">
      <ul class="breadcrumb">

        <li><a href="brugere.php">Brugere</a></li>
        <li><a href="bruger.php?id=<?=$bruger_id;?>" class="active">Rediger bruger</a></li>
      </ul>
      <div >

        <div style="display:none;">
          <div id="sletbruger" >
           <form id="formsletspiller" action="ajax/bruger_slet.php?id=<?php echo $_GET["id"];?>"  onSuccess="function(data, textStatus, jqXHR)" method="post" autocomplete="off" ajax="true" confirmSend="true">
            <label>Slet bruger</label>

            <fieldset>
              <section>
                <label>Slet</label>
                <div><input type="text" id="navn" name="navn"  required data-regex="^[SLET]+$" data-errortext="Skriv venligst SLET i feltet for at bekræfte sletningen">
                  <span>Skriv SLET i feltet for at bekræfte sletningen - det kan ikke fortrydes</span>
                </div>

              </section>

              <section>
               <div><button class="submit red btn" id="sletspiller" rel="">Slet bruger</button><button id="fortryd" href="#fortryd" class="btn">Fortryd sletning</button> </div>
             </section>

           </fieldset>

         </form>
       </div>
     </div>
     <form id="formgembruger" action="ajax/bruger_gem.php?id=<?=$bruger_id?>"  onSuccess="function(data, textStatus, jqXHR)" method="post" autocomplete="off" ajax="true" confirmSend="true">


      <label>Rediger bruger<?php if($bruger["deaktiv"] == 1){echo " - BRUGEREN ER SLETTET";} ?></label>

      <fieldset>
        <section>
          <label>Navn</label>
          <div><input type="text" id="navn" name="navn" required data-errortext="Giv venligst brugeren et navn" value="<? echo  $bruger["navn"];?>">
          </div>
        </section>
        <section>
          <label>Telefon</label>
          <div><input type="text" id="telefon" name="telefon" required data-errortext="Giv venligst brugeren et telefonnummer"  value="<? echo  $bruger["telefon"];?>">
          </div>
        </section>
        <section>
          <label>Opdateret medlemskab?</label>
          <div>
            <input type="checkbox" id="medlemskab" name="medlemskab" <? if ($bruger["opdateret_medlemskab"]) { echo 'checked'; } ?>>
          </div>
          <label>Modtage SMS?</label>
          <div>
            <input type="checkbox" id="sms" name="sms" <? if ($bruger["modtage_sms"]) { echo 'checked'; } ?>>
          </div>
        </section>
        <section>
         <div><button class="submit" id="gemspiller">Gem</button><a style="float:right" class="btn i_access_denied icon red" id="sletbrugerlink" href="#sletbruger">Slet bruger</a></div>
       </section>
     </fieldset>
   </form>
   <form id="formgemrangliste" action="ajax/rangliste_ny.php?id=<?=$bruger_id?>"  onSuccess="function(data, textStatus, jqXHR)" method="post" autocomplete="off" ajax="true" confirmSend="true">


     <label>Rangliste</label>

     <fieldset>
      <section>

        <label>Rangliste point</label>
        <div id="ranglistepoint"><? echo  $bruger["rangliste"];?></div>
      </section>
      <section>

        <label>Ændring i rangliste point</label>
        <div><input id="rangliste" name="rangliste" type="number" class="integer" required data-errortext="Angiv venligst et tal">


        </div>
      </section>

      <section>
       <div><button class="submit" id="gemspiller">Gem</button></div>
     </section>
   </fieldset>
 </form>

 <h3 style="margin-bottom:20px;">Beregningsgrundlag for rangliste</h3>

 <table>
  <thead>
    <tr>
      <th style="width:250px;">Dato</th>
      <th>Tekst</th>
      <th>Point</th>
    </tr>
  </thead>
  <?php
  $date = date("Y-m-d H:i:s");
  $newdate = strtotime ( '-8 WEEKS' , strtotime ( $date ) ) ;
  $newdate = date ( 'Y-m-d H:i:s' , $newdate );

  $rangliste = mysql_query("SELECT * FROM hbf_rangliste WHERE date > '$newdate' and bruger_id = '".$bruger["bruger_id"]."' ORDER BY DATE DESC") or die(mysql_error());
  while($rowrang = mysql_fetch_array($rangliste)){
    ?>
    <tbody>
      <tr>
        <th><? echo date("d-m-Y",  strtotime($rowrang["date"]));?></th>
        <td><?=$rowrang["text"]?></td>
        <td><?=$rowrang["point"]?></td>

      </tr>

    </tbody>

    <?php } ?>
    <tfoot>
      <tr>
        <th>Total</th>
        <th></th>
        <th><? echo  $bruger["rangliste"];?></th>

      </tr>
    </tfoot>
  </table>

</div>
<div style="text-align: left;">
  <a class="btn i_arrow_right icon" href="bruger_rang.php?id=<?=$bruger["bruger_id"]?>">Se alle kampe</a>
</div>

 <h3 style="margin-bottom:20px;">medlemskabshistorik</h3>

 <table>
  <thead>
    <tr>
      <th style="width:250px;">Registreret</th>
    </tr>
  </thead>
  <?php

  $medlemskaber = mysql_query("SELECT * FROM hbf_medlemskaber WHERE bruger_id = '".$bruger["bruger_id"]."' ORDER BY registreret DESC") or die(mysql_error());
  while($rowmedlemskab = mysql_fetch_array($medlemskaber)){
    ?>
    <tbody>
      <tr>
        <th><? echo $rowmedlemskab["registreret"];?></th>
      </tr>
    </tbody>

    <?php } ?>
  </table>

</div>

</section>

</body>
<script type="text/javascript">
  $(document).ready(function(){

   $('#formgembruger').wl_Form({
    ajax:true,
    confirmSend:false,
    onSuccess: function(data, status){
      $.msg(data);
    },
    onComplete: function(){window.location.replace("brugere.php");}
  });
   $('#formgemrangliste').wl_Form({
    ajax:true,
    confirmSend:false,
    onSuccess: function(data, status){

      data = data.split('#');
      $.msg(data[1]);
      antal = data[0];
      $('#formgemrangliste').wl_Form('reset');

      $("#ranglistepoint").html(antal);
    }
  });

   $("a#sletbrugerlink").fancybox({
    'speedIn'		:	250,
    'speedOut'		:	0   ,
    'overlayShow'	:	true,
    'onComplete': function(){


    },
    'onClosed': function(){


      $('#formnyspiller').wl_Form('reset');

      ;},

      'enableEscapeButton':true
    });

   $('#fortryd').click(function() {
    $('#formsletspiller').wl_Form('reset');
    $.fancybox.close();
  });

   $('#formsletspiller').wl_Form({
    ajax:true,
    confirmSend:false,
    onSuccess: function(data, status){},
    onComplete: function(){window.location.replace("brugere.php");}
  });

 });
</script>
<?php include_once("inc_footer.php"); ?>