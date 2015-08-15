<?php
require_once("functions.php");
$turnering = hentturnering();
$turnerings_id = $turnering["turnering_id"];
if(!isset($opdater)){
    $opdater = false;
}
if(isset($_GET["start"])){

    $ipuljer = array();
    foreach($_POST as $index => $test){
       $ipuljer = $HTTP_POST_VARS[$index];

    }

    if(count($ipuljer) > 0){
        $spillerids = join(',',$ipuljer);

        $hent_startnummer = mysql_query("SELECT * FROM hbf_puljer WHERE spiller_id in ($spillerids) and turnerings_id = '$turnerings_id' order by rangering_total") or die(mysql_error());
        $row = mysql_fetch_array($hent_startnummer);
        $nummer = $row["rangering_total"];

        foreach($ipuljer as $spiller_id){
               $opdater = mysql_query("UPDATE hbf_puljer SET rangering_total = '$nummer'  WHERE turnerings_id = '$turnerings_id' AND spiller_id = '$spiller_id' ") or die(mysql_error());
               $nummer++;
        }
    }


    // Indsætter kampe
    mysql_query("DELETE FROM hbf_kampe WHERE turnerings_id = '$turnerings_id' AND type IN ('k','jk','s','js','f','jf')") or die(mysql_error());
    mysql_query("UPDATE hbf_puljer SET kvartfinale = 0 WHERE turnerings_id = '$turnerings_id'") or die(mysql_error());

    // Antal hold
    $antalhold = sumdbarray($turnering["puljer"]);

    // Loop puljer
    $puljer = dbarraytoarray($turnering["puljer"]);
    $puljerantal = count($puljer);
    for($i = 0; $i < $puljerantal;$i++){
        $puljenumre[] = $i;
    }

    /////////////////
    // Ordinær
    /////////////////

    $desc = "";
    $kampspiller = array();
    $stop = false;

    // Skriver semifinaler og finale
    $insert = mysql_query("INSERT INTO hbf_kampe (turnerings_id,hold1,hold2,type,kampnr,pulje,parameter) values ('$turnerings_id','0','0','s','5','0','0')")or die(mysql_error());
    $kamp_semi_1 = mysql_insert_id();
    $insert = mysql_query("INSERT INTO hbf_kampe (turnerings_id,hold1,hold2,type,kampnr,pulje,parameter) values ('$turnerings_id','0','0','s','6','0','0')")or die(mysql_error());
    $kamp_semi_2 = mysql_insert_id();
    $insert = mysql_query("INSERT INTO hbf_kampe (turnerings_id,hold1,hold2,type,kampnr,pulje,parameter) values ('$turnerings_id','0','0','f','7','0','0')")or die(mysql_error());
    $kamp_finale = mysql_insert_id();

    // Hvis mindre end 8 hold - fordel hold
    $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total <= 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id'")or die(mysql_error());
    $holdcount = mysql_num_rows($hent);
    
    // Hvis lig end 7 hold:  
    if(in_array($holdcount, array(0))){
        $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_finale'");
        $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_semi_1'");
        $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_semi_2'");
    }
    if(in_array($holdcount, array(1))){
        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total <= 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold1 = '".$row["spiller_id"]."', vinder = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_finale'") or die(mysql_error());

         $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_semi_1'");
        $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_semi_2'");
    }

    if(in_array($holdcount, array(2))){
        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total <= 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold1 = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_finale'") or die(mysql_error());

        $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_semi_1'");
    }

    if(in_array($holdcount, array(3,2))){

        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total <= 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold2 = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_finale'") or die(mysql_error());

        $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_semi_2'");
    }
  if(in_array($holdcount, array(4,3))){
        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total <= 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold1 = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_semi_1'") or die(mysql_error());
    }
    if(in_array($holdcount, array(5,4,3))){
        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total <= 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold2 = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_semi_1'") or die(mysql_error());
    }

    if(in_array($holdcount, array(6,5,4))){
        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total <= 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold1 = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_semi_2'") or die(mysql_error());
    }

    if(in_array($holdcount, array(7,6,5,4))){
        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total <= 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold2 = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_semi_2'") or die(mysql_error());
    }

    // Sætter kampe på baggrund af rang.
    $kampprogram = array();
    $kampprogram[] = "";
    $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total <= 8 AND kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total")or die(mysql_error());
    while($row = mysql_fetch_array($hent)){
        $kampprogram[] = $row["spiller_id"];
    }

    // Sikre at op til 8 kampprogram er sat
    
    
    $kampnummer = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;

    //
    // Sætter kampe
    //
    
    if($puljerantal != 2){
    // Sæt kamp 1
    $result = setkvartfinalekamp($turnerings_id, $kampprogram,$kampnummer, 1, 8, "k");
    $kampprogram = $result["kamprogram"];
    $kampnummer = $result["kampnummer"];


    // Sæt kamp 2
    $result = setkvartfinalekamp($turnerings_id, $kampprogram,$kampnummer, 3, 6, "k");
    $kampprogram = $result["kamprogram"];
    $kampnummer = $result["kampnummer"];

    // Sæt kamp 3
    $result = setkvartfinalekamp($turnerings_id, $kampprogram,$kampnummer, 2, 7, "k");
    $kampprogram = $result["kamprogram"];
    $kampnummer = $result["kampnummer"];

    // Sæt kamp 4
    $result = setkvartfinalekamp($turnerings_id, $kampprogram,$kampnummer, 4, 5, "k");
    $kampprogram = $result["kamprogram"];
    $kampnummer = $result["kampnummer"];

    } else {
          
     
      $puljer2["A1"] = puljer2hent($turnerings_id,"1,2",0);
      $puljer2["A2"] = puljer2hent($turnerings_id,"3,4",0);
      $puljer2["A3"] = puljer2hent($turnerings_id,"5,6",0);
      $puljer2["A4"] = puljer2hent($turnerings_id,"7,8",0);
      $puljer2["B1"] = puljer2hent($turnerings_id,"1,2",1);
      $puljer2["B2"] = puljer2hent($turnerings_id,"3,4",1);
      $puljer2["B3"] = puljer2hent($turnerings_id,"5,6",1);
      $puljer2["B4"] = puljer2hent($turnerings_id,"7,8",1);
      
      $finaletype = "k";
      $kampnummer = 0;
      
      // Hvis 5 hold skal A2 spille mod A3
      if($holdcount == 5){
        $spec = "A2";
        $modarr = array("A3");
        $kampnummer = set2puljer($puljer2,$kampnummer,$spec,$modarr,$turnerings_id,$finaletype);
      }
      // A1 mod B4,B3,B2,B1
      $spec = "A1";
      $modarr = array("B4","B3","B2","B1");
      $kampnummer = set2puljer($puljer2,$kampnummer,$spec,$modarr,$turnerings_id,$finaletype);
      
      // A3 mod B2,B1 (hvis A3 findes)
      $spec = "A3";
      $modarr = array("B2","B1");
      $kampnummer = set2puljer($puljer2,$kampnummer,$spec,$modarr,$turnerings_id,$finaletype);
      
      // A2 mod B3,B2,B1 (hvis A2 findes)
      $spec = "A2";
      $modarr = array("B3","B2","B1");
      $kampnummer = set2puljer($puljer2,$kampnummer,$spec,$modarr,$turnerings_id,$finaletype);
      
      // A4 mod B1 (hvis A4 findes)
      $spec = "A4";
      $modarr = array("B1");
      $kampnummer = set2puljer($puljer2,$kampnummer,$spec,$modarr,$turnerings_id,$finaletype);

    }

    
    $i = $q = $nummer = 0;
    
 
    /////////////////
    // Jays
    /////////////////

    $desc = "";
    $kampspiller = array();
    $stop = false;

    // Skriver semifinaler og finale
    $insert = mysql_query("INSERT INTO hbf_kampe (turnerings_id,hold1,hold2,type,kampnr,pulje,parameter) values ('$turnerings_id','0','0','js','5','0','0')")or die(mysql_error());
    $kamp_semi_1 = mysql_insert_id();
    $insert = mysql_query("INSERT INTO hbf_kampe (turnerings_id,hold1,hold2,type,kampnr,pulje,parameter) values ('$turnerings_id','0','0','js','6','0','0')")or die(mysql_error());
    $kamp_semi_2 = mysql_insert_id();
    $insert = mysql_query("INSERT INTO hbf_kampe (turnerings_id,hold1,hold2,type,kampnr,pulje,parameter) values ('$turnerings_id','0','0','jf','7','0','0')")or die(mysql_error());
    $kamp_finale = mysql_insert_id();

    // Hvis mindre end 8 hold - fordel hold
    $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total > 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id'")or die(mysql_error());
    $holdcount = mysql_num_rows($hent);
 

    if(in_array($holdcount, array(0))){
        $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_finale'");
        $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_semi_1'");
        $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_semi_2'");

    }
    if(in_array($holdcount, array(1))){
        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total > 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold1 = '".$row["spiller_id"]."', vinder = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_finale'") or die(mysql_error());

        $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_semi_1'");
        $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_semi_2'");
    }
    if(in_array($holdcount, array(2))){
        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total > 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold1 = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_finale'") or die(mysql_error());

        $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_semi_1'");
    }
    if(in_array($holdcount, array(3,2))){
        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total > 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold2 = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_finale'") or die(mysql_error());

        $fjern = mysql_query("DELETE FROM hbf_kampe WHERE kamp_id ='$kamp_semi_2'");
    }
    if(in_array($holdcount, array(4,3))){
        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total > 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold1 = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_semi_1'") or die(mysql_error());
    }
    if(in_array($holdcount, array(5,4,3))){
        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total > 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold2 = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_semi_1'") or die(mysql_error());
    }

   if(in_array($holdcount, array(6,5,4))){
        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total > 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold1 = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_semi_2'") or die(mysql_error());
    }
    if(in_array($holdcount, array(7,6,5,4))){
        $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total > 8 and kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total $desc limit 0,1")or die(mysql_error());
        $row = mysql_fetch_array($hent);
        $opdater = mysql_query("UPDATE hbf_puljer SET kvartfinale = 1 WHERE spiller_id = '".$row["spiller_id"]."' AND turnerings_id = '$turnerings_id'") or die(mysql_error());
        $opdater = mysql_query("UPDATE hbf_kampe SET hold2 = '".$row["spiller_id"]."' WHERE turnerings_id = '$turnerings_id' AND kamp_id = '$kamp_semi_2'") or die(mysql_error());

    }

    // Sætter kampe på baggrund af rang.
    $kampprogram = array();
    $kampprogram[] = "";
    $hent = mysql_query("SELECT * FROM hbf_puljer WHERE rangering_total <= 16 AND rangering_total > 8 AND kvartfinale = 0 AND turnerings_id = '$turnerings_id' ORDER by rangering_total")or die(mysql_error());
    while($row = mysql_fetch_array($hent)){
        $kampprogram[] = $row["spiller_id"];
    }

    // Sikre at op til 8 kampprogram er sat
    $kampnummer = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;
    $kampprogram[] = 0;

   if($puljerantal != 2){
       
    // Sæt kamp 1
    $result = setkvartfinalekamp($turnerings_id, $kampprogram,$kampnummer, 1, 8, "jk");
    $kampprogram = $result["kamprogram"];
    $kampnummer = $result["kampnummer"];


    // Sæt kamp 2
    $result = setkvartfinalekamp($turnerings_id, $kampprogram,$kampnummer, 3, 6, "jk");
    $kampprogram = $result["kamprogram"];
    $kampnummer = $result["kampnummer"];

    // Sæt kamp 3
    $result = setkvartfinalekamp($turnerings_id, $kampprogram,$kampnummer, 2, 7, "jk");
    $kampprogram = $result["kamprogram"];
    $kampnummer = $result["kampnummer"];

    // Sæt kamp 4
    $result = setkvartfinalekamp($turnerings_id, $kampprogram,$kampnummer, 4, 5, "jk");
    $kampprogram = $result["kamprogram"];
    $kampnummer = $result["kampnummer"];

    } else {
          
      
      $puljer2["A1"] = puljer2hent($turnerings_id,"9,10",0);
      $puljer2["A2"] = puljer2hent($turnerings_id,"11,12",0);
      $puljer2["A3"] = puljer2hent($turnerings_id,"13,14",0);
      $puljer2["A4"] = puljer2hent($turnerings_id,"15,16",0);
      $puljer2["B1"] = puljer2hent($turnerings_id,"9,10",1);
      $puljer2["B2"] = puljer2hent($turnerings_id,"11,12",1);
      $puljer2["B3"] = puljer2hent($turnerings_id,"13,14",1);
      $puljer2["B4"] = puljer2hent($turnerings_id,"15,16",1);
      
      
      $finaletype = "jk";
      $kampnummer = 0;
      
      // Hvis 5 hold skal A2 spille mod A3
      if($holdcount == 5){
        $spec = "A2";
        $modarr = array("A3");
        $kampnummer = set2puljer($puljer2,$kampnummer,$spec,$modarr,$turnerings_id,$finaletype);
      }
      
      // A1 mod B4,B3,B2,B1
      $spec = "A1";
      $modarr = array("B4","B3","B2","B1");
      $kampnummer = set2puljer($puljer2,$kampnummer,$spec,$modarr,$turnerings_id,$finaletype);
        
      // A3 mod B2,B1 (hvis A3 findes)
      $spec = "A3";
      $modarr = array("B2","B1");
      $kampnummer = set2puljer($puljer2,$kampnummer,$spec,$modarr,$turnerings_id,$finaletype);
      
      // A2 mod B3,B2,B1 (hvis A2 findes)
      $spec = "A2";
      $modarr = array("B3","B2","B1");
      $kampnummer = set2puljer($puljer2,$kampnummer,$spec,$modarr,$turnerings_id,$finaletype);
      
      // A4 mod B1 (hvis A4 findes)
      $spec = "A4";
      $modarr = array("B1");
      $kampnummer = set2puljer($puljer2,$kampnummer,$spec,$modarr,$turnerings_id,$finaletype);
         
      
      
       
    }


    $i = $q = $nummer = 0;


header("location:turnering_kvart.php");
}


?>
<?php include_once("inc_header.php"); ?>
<body>

    <script type="text/javascript">

       $(document).ready(function(){


        function forbered(){
            $('#afslutkamp').wl_Form({
            ajax:true,
            confirmSend:false,
            onSuccess: function(data, status){

					$.msg(data);
                                        $.fancybox.close();
                                       
                                        hentfinaler();
                                        
				}
           
            });
            $('#fortryd').click(function() {
               $.fancybox.close();
            });

            $("a#afslutkamp").fancybox({
                    'speedIn'		:	250,
                    'speedOut'		:	0   ,
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
        }

        function hentfinaler(){
        
            $.get('ajax/turnering_finaler.php?id=<?=$turnerings_id?><? if(isset($_GET["rang"])){ echo "&rang=vis";}?>', function(data) {
                $('#data_finaler').html(data);
                forbered();
            });
            $.get('ajax/turnering_jaysfinaler.php?id=<?=$turnerings_id?><? if(isset($_GET["rang"])){ echo "&rang=vis";}?>', function(data) {
                $('#data_jaysfinaler').html(data);
                forbered();
            });
            $.get('ajax/turnering_testforvinder.php?id=<?=$turnerings_id?>', function(data) {
                if(data == 1){
                    $('#afslutturnering').show();
                }
                forbered();
            });

        }
        hentfinaler();


        <?php if($opdater){ ?>
        var refreshId = setInterval(function()
        {
            hentfinaler();
        }, 5000);

        <?php } ?>
       });

    </script>

    <?php include_once("inc_navigation.php"); ?>
    <div style="display:none;">
    <div id="afslutkampbox" style="width:450px;" >
       <form id="afslutkamp" action="ajax/turnering_kampafslut.php?finaler=1"  onSuccess="function(data, textStatus, jqXHR)" method="post" autocomplete="off" ajax="true" confirmSend="true">

            <fieldset style="width:430px;height:175px;" id="afslutsetkamp">

            </fieldset>
           <fieldset style="width:430px;">
                    <input type="hidden" name="kamp_id" id="kamp_id" value="">
                <section>
                    <div style="width:200px;">
                        <button class="green submit" id="sletspiller">Godkend</button>
                        <button id="fortryd">fortryd</button>
                        <input type="hidden" name="turneringsid" value="<?php if(isset($turnerings_id)){ echo $turnerings_id;} ?>">
                    </div>
                </section>


           </fieldset>
       </form>

    </div>
</div>

<section id="content">

    <div class="g12 widgets">
        <div class="widget" data-icon="cup">
        <h3 class="handle">Ordinære finaler</h3>
        <div>
            <div class='g12' id="data_finaler" class="finaler"></div>
            <div style="clear:both"></div>
        <div style="clear:both;"></div>
        </div>
    </div>
        <? $antalhold = sumdbarray($turnering["puljer"]);


  if($antalhold >= 9){?>
      <div class="widget" data-icon="male_contour">
        <h3 class="handle">Jays finaler</h3>
        <div>
            <div class='g12' id="data_jaysfinaler" class="jaysfinaler"></div>
            <div style="clear:both"></div>


        <div style="clear:both;"></div>
        </div>
    </div><? } ?>
    </div>

    <div style="text-align:right;padding:20px;">
    <div id="dialog" class="btn" title="Bekræft at turneringen er færdig">
  Er du sikker på at du vil afslutte turneringen? Du kan ikke fortryde.
</div>
                <div id="dialog2" class="btn" title="Genstart finalespil">
  Er du sikker på at du vil genstarte finalespillet? Du kan ikke fortryde.
</div>
         <div id="forfraturnering" class="fl">
            <a href="turnering_kvart_puljekonflikt.php?forfra=1" class="red btn confirmLink2" id="submut">Genstart finaler</a>
            <a href="turnering_kvart.php?rang=vis" class="btn" id="submut">Vis rangering</a>
        </div>

        <div id="afslutturnering" style="display:none;">
            <a href="turnering_afslut.php?afslut=1" class="green btn confirmLink" id="submut">Afslut turnering og fordel point</a>
        </div>
    </div>

</section>
<script type="text/javascript">
  $(document).ready(function() {
    $("#dialog").dialog({
      autoOpen: false,
      modal: true
    });
    $("#dialog2").dialog({
      autoOpen: false,
      modal: true
    });
  });

  $(".confirmLink").click(function(e) {
    e.preventDefault();
    var targetUrl = $(this).attr("href");

    $("#dialog").dialog({
      buttons : {
        "Afslut turnering" : function() {
          $(this).dialog("close");
          window.location.href = targetUrl;
        },
        "Annuller" : function() {
          $(this).dialog("close");
        }
      }
    });



    $("#dialog").dialog("open");
  });


    $(".confirmLink2").click(function(e) {
    e.preventDefault();
    var targetUrl = $(this).attr("href");

    $("#dialog2").dialog({
      buttons : {
        "Genstart finalespil" : function() {
          window.location.href = targetUrl;
        },
        "Annuller" : function() {
          $(this).dialog("close");
        }
      }
    });

    $("#dialog2").dialog("open");
  });
</script>
</body>
<?php include_once("inc_footer.php"); ?>