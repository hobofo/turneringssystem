<?php
require_once("../functions.php");
    // Henter seneste turnerings id
    $result = mysql_query("SELECT * from hbf_brugere where deaktiv != 1") or die(mysql_error());
    while($row = mysql_fetch_array($result)){
        echo "
        <tr>
        <td><a href='bruger.php?id=".$row["bruger_id"]."' style='font-weight:bold;'>".$row["navn"]."</a></td>
        <td>".$row["telefon"]."</td>
        <td>".$row["rangliste"]."</td>

        </tr>

        ";
    }
