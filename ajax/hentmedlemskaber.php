<?php
require_once("../functions.php");
    $result = mysqli_query($link,"SELECT hbf_brugere.bruger_id, hbf_brugere.navn, hbf_brugere.telefon, hbf_medlemskaber.registreret FROM hbf_medlemskaber INNER JOIN hbf_brugere ON hbf_brugere.bruger_id = hbf_medlemskaber.bruger_id WHERE hbf_medlemskaber.registreret > '".$_GET['dato']."'") or die(mysqli_error($link));
    while($row = mysqli_fetch_array($result)){
        
        echo "
        <tr>
        <td><a href='bruger.php?id=".$row["bruger_id"]."' style='font-weight:bold;'>".$row["navn"]."</a></td>
        <td>".$row["telefon"]."</td>
        <td>".$row["registreret"]."</td>
        </tr>

        ";
    }
