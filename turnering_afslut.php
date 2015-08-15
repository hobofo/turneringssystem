<?php
require_once("functions.php");
$turnering = hentturnering();
$turnerings_id = $turnering["turnering_id"];
$point = $turnering["point"];
$point = dbarraytoarray($point);

///////////////////
// Ordinær
///////////////////

// Finaler
$hent = mysql_query("SELECT * FROM hbf_kampe WHERE turnerings_id = '$turnerings_id' AND type = 'f'") or die(mysql_error());
$row = mysql_fetch_array($hent);

$vinder = $row["vinder"];

if($row["vinder"] == $row["hold1"]){
    $finaletaber = $row["hold2"];
} else {
    $finaletaber = $row["hold1"];
}

// Semifinaler
$hent = mysql_query("SELECT * FROM hbf_kampe WHERE turnerings_id = '$turnerings_id' AND type = 's'") or die(mysql_error());
while($row = mysql_fetch_array($hent)){
    if($row["vinder"] == $row["hold1"]){
        $tabersemifinale[] = $row["hold2"];
    } else {
        $tabersemifinale[] = $row["hold1"];
    }
}
$tabersemifinale1 = $tabersemifinale2 = 0;

if(isset($tabersemifinale[0])){$tabersemifinale1 = $tabersemifinale[0];}
if(isset($tabersemifinale[0])){$tabersemifinale2 = $tabersemifinale[1];}

// Kvartfinaler
$hent = mysql_query("SELECT * FROM hbf_kampe WHERE turnerings_id = '$turnerings_id' AND type = 'k'") or die(mysql_error());
while($row = mysql_fetch_array($hent)){
    if($row["vinder"] == $row["hold1"]){
        $taberkvart[] = $row["hold2"];
    } else {
        $taberkvart[] = $row["hold1"];
    }
}
$taberkvartfinale1 = $taberkvartfinale2 = $taberkvartfinale3 = $taberkvartfinale4 = 0;

if(isset($taberkvart[0])){$taberkvartfinale1 = $taberkvart[0];}
if(isset($taberkvart[1])){$taberkvartfinale2 = $taberkvart[1];}
if(isset($taberkvart[2])){$taberkvartfinale3 = $taberkvart[2];}
if(isset($taberkvart[3])){$taberkvartfinale4 = $taberkvart[3];}


///////////////////
// Jays
///////////////////
$antalhold = sumdbarray($turnering["puljer"]);

// Finaler
$hent = mysql_query("SELECT * FROM hbf_kampe WHERE turnerings_id = '$turnerings_id' AND type = 'jf'") or die(mysql_error());
$row = mysql_fetch_array($hent);

$vinder_jays = $row["vinder"];

if($row["vinder"] == $row["hold1"]){
    $finaletaber_jays = $row["hold2"];
} else {
    $finaletaber_jays = $row["hold1"];
}

// Semifinaler
$tabersemifinale = array();
$hent = mysql_query("SELECT * FROM hbf_kampe WHERE turnerings_id = '$turnerings_id' AND type = 'js'") or die(mysql_error());
while($row = mysql_fetch_array($hent)){
    if($row["vinder"] == $row["hold1"]){
        $tabersemifinale[] = $row["hold2"];
    } else {
        $tabersemifinale[] = $row["hold1"];
    }
}
$tabersemifinale1_jays = $tabersemifinale2_jays  = 0;
if(isset($tabersemifinale[0])){$tabersemifinale1_jays = $tabersemifinale[0];}
if(isset($tabersemifinale[1])){$tabersemifinale2_jays = $tabersemifinale[1];}

// Kvartfinaler
$taberkvart = array();
$hent = mysql_query("SELECT * FROM hbf_kampe WHERE turnerings_id = '$turnerings_id' AND type = 'jk'") or die(mysql_error());
while($row = mysql_fetch_array($hent)){
    if($row["vinder"] == $row["hold1"]){
        $taberkvart[] = $row["hold2"];
    } else {
        $taberkvart[] = $row["hold1"];
    }
}
$taberkvartfinale1_jays = $taberkvartfinale2_jays = $taberkvartfinale3_jays = $taberkvartfinale4_jays = 0;

if(isset($taberkvart[0])){$taberkvartfinale1_jays = $taberkvart[0];}
if(isset($taberkvart[1])){$taberkvartfinale2_jays = $taberkvart[1];}
if(isset($taberkvart[2])){$taberkvartfinale3_jays = $taberkvart[2];}
if(isset($taberkvart[3])){$taberkvartfinale4_jays = $taberkvart[3];}


if(isset($_GET["afslut"])){

    // Kan kun opdateres en gang
    $hent = mysql_query("SELECT * FROM hbf_rangliste WHERE turnerings_id = '$turnerings_id'");
    if(mysql_num_rows($hent)<9999999){
        // Ordinært
        $rangliste = $point[0];
        $type = "Vinder af finale";
        $runrangliste = afslutkamprangliste ($vinder,$rangliste,$type,$turnerings_id);

        $rangliste = $point[1];
        $type = "Taber af finale";
        $runrangliste = afslutkamprangliste ($finaletaber,$rangliste,$type,$turnerings_id);

        $rangliste = $point[2];
        $type = "Taber af semifinale";
        $runrangliste = afslutkamprangliste ($tabersemifinale1,$rangliste,$type,$turnerings_id);
        $runrangliste = afslutkamprangliste ($tabersemifinale2,$rangliste,$type,$turnerings_id);

        $rangliste = $point[3];
        $type = "Taber af kvartfinale";
        $runrangliste = afslutkamprangliste ($taberkvartfinale1,$rangliste,$type,$turnerings_id);
        $runrangliste = afslutkamprangliste ($taberkvartfinale2,$rangliste,$type,$turnerings_id);
        $runrangliste = afslutkamprangliste ($taberkvartfinale3,$rangliste,$type,$turnerings_id);
        $runrangliste = afslutkamprangliste ($taberkvartfinale4,$rangliste,$type,$turnerings_id);

        // Jays
        $antalhold = sumdbarray($turnering["puljer"]);

        $rangliste = $point[4];
        $type = "Vinder af jays-finale";
        $runrangliste = afslutkamprangliste ($vinder_jays,$rangliste,$type,$turnerings_id);

        $rangliste = $point[5];
        $type = "Taber af jays-finale";
        $runrangliste = afslutkamprangliste ($finaletaber_jays,$rangliste,$type,$turnerings_id);

        $rangliste = $point[6];
        $type = "Taber af jays-semifinale";
        $runrangliste = afslutkamprangliste ($tabersemifinale1_jays,$rangliste,$type,$turnerings_id);
        $runrangliste = afslutkamprangliste ($tabersemifinale2_jays,$rangliste,$type,$turnerings_id);

        $rangliste = $point[7];
        $type = "Taber af jays-kvartfinale";
        $runrangliste = afslutkamprangliste ($taberkvartfinale1_jays,$rangliste,$type,$turnerings_id);
        $runrangliste = afslutkamprangliste ($taberkvartfinale2_jays,$rangliste,$type,$turnerings_id);
        $runrangliste = afslutkamprangliste ($taberkvartfinale3_jays,$rangliste,$type,$turnerings_id);
        $runrangliste = afslutkamprangliste ($taberkvartfinale4_jays,$rangliste,$type,$turnerings_id);

    }

   opdaterrangliste();
   header("location:turnering_afslut.php");
}


?>
<?php include_once("inc_header.php"); ?>
<body>

    <script type="text/javascript">

       $(document).ready(function(){

       });

    </script>

    <?php include_once("inc_navigation.php"); ?>

<section id="content">

<div class='g6'>
    <h2 style="margin-bottom: 10px;">Resultat af ordinære finaler</h2>

    <table>
        <tr><th>Resultat</th><th>Hold</th><th>Point</th></tr>
        <tr><td>Vinder</td><td><?=hentnavne($vinder," og ")?></td><td><?=$point[0]?></td></tr>
        <tr><td>Taber finale</td><td><?=hentnavne($finaletaber," og ")?></td><td><?=$point[1]?></td></tr>
        <tr><td>Taber semifinale</td><td><?=hentnavne($tabersemifinale1," og ")?></td><td><?=$point[2]?></td></tr>
        <tr><td>Taber semifinale</td><td><?=hentnavne($tabersemifinale2," og ")?></td><td><?=$point[2]?></td></tr>
        <tr><td>Taber kvartfinale</td><td><?=hentnavne($taberkvartfinale1," og ")?></td><td><?=$point[3]?></td></tr>
        <tr><td>Taber kvartfinale</td><td><?=hentnavne($taberkvartfinale2," og ")?></td><td><?=$point[3]?></td></tr>
        <tr><td>Taber kvartfinale</td><td><?=hentnavne($taberkvartfinale3," og ")?></td><td><?=$point[3]?></td></tr>
        <tr><td>Taber kvartfinale</td><td><?=hentnavne($taberkvartfinale4," og ")?></td><td><?=$point[3]?></td></tr>
           <tr></tr>
    </table>
</div>

<div class='g6'>
    <h2 style="margin-bottom: 10px;">Resultat af jays finaler</h2>

    <table>
        <tr><th>Resultat</th><th>Hold</th><th>Point</th></tr>
        <tr><td>Vinder</td><td><?=hentnavne($vinder_jays," og ")?></td><td><?=$point[4]?></td></tr>
        <tr><td>Taber finale</td><td><?=hentnavne($finaletaber_jays," og ")?></td><td><?=$point[5]?></td></tr>
        <tr><td>Taber semifinale</td><td><?=hentnavne($tabersemifinale1_jays," og ")?></td><td><?=$point[6]?></td></tr>
        <tr><td>Taber semifinale</td><td><?=hentnavne($tabersemifinale2_jays," og ")?></td><td><?=$point[6]?></td></tr>
        <tr><td>Taber kvartfinale</td><td><?=hentnavne($taberkvartfinale1_jays," og ")?></td><td><?=$point[7]?></td></tr>
        <tr><td>Taber kvartfinale</td><td><?=hentnavne($taberkvartfinale2_jays," og ")?></td><td><?=$point[7]?></td></tr>
        <tr><td>Taber kvartfinale</td><td><?=hentnavne($taberkvartfinale3_jays," og ")?></td><td><?=$point[7]?></td></tr>
        <tr><td>Taber kvartfinale</td><td><?=hentnavne($taberkvartfinale4_jays," og ")?></td><td><?=$point[7]?></td></tr>
           <tr></tr>
    </table>
</div>

</body>
<?php include_once("inc_footer.php"); ?>