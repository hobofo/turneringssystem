<?php
require_once("../functions.php");
    $result = mysql_query("SELECT hbf_brugere.bruger_id, hbf_brugere.navn, hbf_brugere.telefon, hbf_medlemskaber.registreret FROM hbf_medlemskaber INNER JOIN hbf_brugere ON hbf_brugere.bruger_id = hbf_medlemskaber.bruger_id WHERE hbf_medlemskaber.registreret > '".$_GET['dato']."'") or die(mysql_error());
    while($row = mysql_fetch_array($result)){
        
        echo "
        <tr>
        <td><a href='bruger.php?id=".$row["bruger_id"]."' style='font-weight:bold;'>".$row["navn"]."</a></td>
        <td>".$row["telefon"]."</td>
        <td>".$row["registreret"]."</td>
        </tr>

        ";
    }