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
                        <li><a href="bruger.php?id=<?=$bruger_id;?>" >Rediger bruger</a></li>
                        <li><a href="bruger_rang.php?id=<?=$bruger_id;?>" class="active">Rangliste</a></li>
                    </ul>
                        <div >
                       
    
                           
                            

<h3 style="margin-bottom:20px;">Rangliste</h3>
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
                        $newdate = strtotime ( '-10 YEARS' , strtotime ( $date ) ) ;
                        $newdate = date ( 'Y-m-d H:i:s' , $newdate );

                        $rangliste = mysqli_query($link,"SELECT * FROM hbf_rangliste WHERE date > '$newdate' and bruger_id = '".$bruger["bruger_id"]."' ORDER BY DATE DESC") or die(mysqli_error($link));
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
                        
                </table>
            </div>

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

            }
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