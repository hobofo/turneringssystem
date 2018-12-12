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

                <div style="display:none;">
                    <div id="nulstilmedlemskaber" >
                        <form id="formnulstilmedlemskaber" action="ajax/nulstil_medlemskaber.php"  onSuccess="function(data, textStatus, jqXHR)" method="post" autocomplete="off" ajax="true" confirmSend="true">
                            <label>KUN FOR BESTYRELSEN! Nulstil medlemskaber</label>

                            <fieldset>
                                <section>
                                    <label>Nulstil</label>
                                    <div><input type="text" id="navn" name="navn"  required data-regex="^[LITSLUN]+$" data-errortext="Skriv venligst NULSTIL bagfra i feltet for at bekræfte nulstilningen.">
                                        <span>Skriv NULSTIL bagfra i feltet for at bekræfte nulstilningen - det kan ikke fortrydes. Skal kun gøres af bestyrelsen!</span>
                                    </div>
                                </section>

                                <section>
                                    <div><button class="submit red btn" id="nulstil" rel="">Nulstil medlemskaber</button><button id="fortryd" href="#fortryd" class="btn">Fortryd nulstilning</button> </div>
                                </section>
                            </fieldset>
                        </form>
                    </div>
                </div>


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
 <div><button class="submit" id="gemspiller">Gem</button><a style="float:right" class="btn i_access_denied icon red" id="nulstilmedlemskaberlink" href="#nulstilmedlemskaber">Nulstil medlemskaber</a></div>
</section>
</fieldset>
</form>
<section>
<h2>Medlemskaber i periode</h2>
Fra <input type="date" style="width:80px;" id="dato" /> til i dag.  <button id="opdaterBtn" type="button">Opdater</button> 
<section>
    <div class="g12">


      <table class="medlemskaber" id="medlemskaber">
    <thead>
     <tr>
      <th>Navn</th><th>Telefonnummer</th><th>Dato</th>
    </tr>
  </thead>
  <tbody id="tabelmedlemskaber">

  </tbody>
</table>
</div>

</section>
</div>
</div>


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
  function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
  }

 function henttabel(){

    var datoString = "" + $("#dato").val() + " 00:00:00";
    if(Date.parse(datoString) != NaN) {
      $.ajax({
        url: "ajax/hentmedlemskaber.php",
        type: "GET",
        data: {"dato": datoString},
        success: function(data){
          if (typeof oTable != 'undefined') { oTable.fnDestroy(); }
          $('#tabelmedlemskaber').empty();
          $('#tabelmedlemskaber').html(data);
        },
        complete: function(data){

          oTable = $('#medlemskaber').dataTable( {
            "iDisplayLength": 50,
            "aLengthMenu": [[10, 50,100, -1], [10, 50,100, "Alle"]],
            "aaSorting": [[0,'asc'] ]
            });
          }
        });
    }
  }



    $(document).ready(function(){

      var d = new Date();
      d.setDate(d.getDate()-7);
      d = new Date(d);
      var dateString = d.getUTCFullYear() + "-" + twoDigits(1 + d.getUTCMonth()) + "-" + twoDigits(d.getUTCDate());// + " " + twoDigits(d.getUTCHours()) + ":" + twoDigits(d.getUTCMinutes()) + ":" + twoDigits(d.getUTCSeconds());
      $("#dato").val(dateString);

      $('#opdaterBtn').click(function () {
        henttabel();
      });

      $('#dato').keyup(function (e) {
        if(e.keyCode == 13) {
          henttabel();
        }
      });

      henttabel();

        $('.formgemindstillinger').wl_Form({
            ajax:true,
            confirmSend:false,
            onSuccess: function(data, status){
                $.msg(data);
            }
        });

        $("a#nulstilmedlemskaberlink").fancybox({
            'speedIn'       :   250,
            'speedOut'      :   0   ,
            'overlayShow'   :   true,
            'onComplete': function(){
            },
            'onClosed': function(){
                $('#formnyspiller').wl_Form('reset');
            },

            'enableEscapeButton':true
        });

        $('#fortryd').click(function() {
            $('#formnulstilmedlemskaber').wl_Form('reset');
            $.fancybox.close();
        });

        $('#formnulstilmedlemskaber').wl_Form({
            ajax:true,
            confirmSend:false,
            onSuccess: function(data, status){},
            onComplete: function(){window.location.replace("indstillinger.php");}
        });
    });
</script>
<?php include_once("inc_footer.php"); ?>