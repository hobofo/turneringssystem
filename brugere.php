<?php 
require_once("functions.php");
include_once("inc_header.php");
?>

<body>

  <?php include_once("inc_navigation.php"); ?>

  <section id="content">
    <div class="g12">
      <ul class="breadcrumb">

        <li><a href="brugere.php" class="active">Brugere</a></li>
      </ul>

      <div  style="margin-top:20px;margin-bottom: 20px;margin-left:10px;"><a class='btn i_user_2 icon yellow' id="nybrugerlink" href="#nybruger">Tilføj bruger</a></div>
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
   <table class="brugere" id="brugere">
    <thead>
     <tr>
      <th>Navn</th><th>Telefonnummer</th><th>Opdateret medlemskab</th><th>Rangliste point</th>
    </tr>
  </thead>
  <tbody id="tabelbrugere">

  </tbody>
</table>
</div>

</section>
<script type="text/javascript">
 function henttabel(){


   $.ajax({
    url: "ajax/hentbrugere.php",
    success: function(data){
      if (typeof oTable != 'undefined') { oTable.fnDestroy(); }
      $('#tabelbrugere').html(data);
    },
    complete: function(data){

      oTable = $('#brugere').dataTable( {
        "iDisplayLength": 50,
        "aLengthMenu": [[10, 50,100, -1], [10, 50,100, "Alle"]],
        "aaSorting": [[0,'asc'] ]
      });

    }

  });
/*
                
*/
}

$(document).ready( function() {

  henttabel();

  $("a#nybrugerlink").fancybox({
    'speedIn'		:	250,
    'speedOut'		:	0   ,
    'overlayShow'	:	true,
    'onClosed': function(){

      $('#formnyspiller').wl_Form('reset');

      ;},

      'enableEscapeButton':true
    });

  $('#formnyspiller').wl_Form({ ajax:true,
    confirmSend:false,
    onSuccess: function(data, status){   
      $.msg(data);
      $.fancybox.close();
      henttabel();
    }
  });


} );

</script>
</body>


<?php include_once("inc_footer.php"); ?>