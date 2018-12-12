<?php
require_once("../functions.php");

$turneringsid = $_GET["id"];
$antalpuljer = $_GET["antal"];
$i = 0;

$query = mysqli_query($link,"SELECT * FROM hbf_spillere where turnering_id = '$turneringsid' AND primaer = '1' AND medspiller <> ''") or die(mysqli_error($link));
$antal = mysql_num_rows($query);

$antalmulige = floor($antal/2);
$overskud = $antal-floor($antal/3)*3;
$type1 = floor($antal/$antalpuljer);
$type2 = floor($antal/$antalpuljer)+1;
$overskud = $antal-floor($antal/$antalpuljer)*$antalpuljer;
$antaltype1 = $antalpuljer-$overskud;
$antaltype2 = $overskud;
?>

<br /><br />
<table>
    <tr>
        <th>Antal puljer</th><th>Antal spillere i hver pulje</th>
    </tr>

<?php if($antaltype1 > $antaltype2){ ?>
    <? if($antaltype1 != "0"){ ?>
        <tr>
             <td><?=$antaltype1;?></td><td><?=$type1;?></td>
        </tr>
    <? } ?>
    <? if($antaltype2 != "0"){ ?>
        <tr>
             <td><?=$antaltype2;?></td><td><?=$type2;?></td>
        </tr>
    <? } ?>
<?php }  else { ?>
    <? if($antaltype2 != "0"){ ?>
        <tr>
             <td><?=$antaltype2;?></td><td><?=$type2;?></td>
        </tr>
    <? } ?>
    <? if($antaltype2 != "0"){ ?>
        <tr>
             <td><?=$antaltype1;?></td><td><?=$type1;?></td>
        </tr>
    <? } ?>

<?php } ?>
    <tr>
     <th>Total</th><th><?=$antal;?> spillere</th>
    </tr>
</table>

<input type="hidden" name="pulje_samlet_type1" value ="<?=$type1;?>">
<input type="hidden" name="pulje_samlet_stk1" value ="<?=$antaltype1;?>">
<input type="hidden" name="pulje_samlet_type2" value ="<?=$type2;?>">
<input type="hidden" name="pulje_samlet_stk2" value ="<?=$antaltype2;?>">
