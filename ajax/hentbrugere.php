<?php
require_once("../functions.php");
    // Henter seneste turnerings id
    $result = mysqli_query($GLOBALS['link'],"SELECT * from hbf_brugere where deaktiv != 1") or die(mysqli_error($GLOBALS['link']));
    while($row = mysqli_fetch_array($result)){
        if ($row["opdateret_medlemskab"] == '1') {
            $opdateret_medlemskab = "Tjek!";
        } else {
            $opdateret_medlemskab = "-";
        }
        
        echo "
        <tr>
        <td><a href='bruger.php?id=".$row["bruger_id"]."' style='font-weight:bold;'>".$row["navn"]."</a></td>
        <td>".$row["telefon"]."</td>
        <td>".$opdateret_medlemskab."</td>
        <td>".$row["rangliste"]."</td>

        </tr>

        ";
    }
