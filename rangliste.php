<?php 
 require_once("functions.php");
 include_once("inc_header.php");
 ?>

<body>

    <?php include_once("inc_navigation.php"); ?>
 
		<section id="content">
                    <div class="g12">
                        <ul class="breadcrumb">
                            <h1>Rangliste</h1>
                        </ul>
                        <div style="margin-bottom:20px;"><a href="rangliste.php" class="btn <?php if(!isset($_GET["start"])){ echo "green";}?>" >Ordinær</a><a href="rangliste.php?start=<?=getsetting("final_10")?>" class="btn <?php if(isset($_GET["start"]) && $_GET["start"]== getsetting("final_10")){ echo "green";}?>">Final 10</a></div>
                        <?php if(isset($_GET["start"])){ ?>
                        <div style="font-weight:bold;font-size:14px;margin-bottom:20px;">Point akkumuleret siden <?=date("d/m Y",  strtotime(getsetting("final_10")))?></div>
                        <?php } ?>
                        <table class="brugere" id="brugere">
				<thead>
					<tr>
						<th>Nr</th><th>Navn</th><th>Rangliste point</th>
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
                    url: "ajax/hentrangliste.php<?php if(isset($_GET["start"])){ echo "?start=".$_GET["start"];}?>",
                    success: function(data){
                        if (typeof oTable != 'undefined') { oTable.fnDestroy(); }
                        $('#tabelbrugere').html(data);
                    },
                    complete: function(data){
                         
                            oTable = $('#brugere').dataTable( {
                            "iDisplayLength": 50,
                            "aLengthMenu": [[10, 50,100, -1], [10, 50,100, "Alle"]],
                            "aaSorting": [[2,'desc'] ]
                            });
                         
                    }

                 });
/*
                
      */
         }
           
           $(document).ready( function() {

            henttabel();


    } );

    </script>
</body>


<?php include_once("inc_footer.php"); ?>