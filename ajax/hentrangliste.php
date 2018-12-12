<?php
require_once("../functions.php");
   
    // Henter seneste turnerings id
    
    if(isset($_GET["start"])){
        $start = $_GET["start"];
        $sql = "SELECT 
                    (SELECT SUM(point) FROM hbf_rangliste b where a.bruger_id = b.bruger_id and date > '$start' ) as rang
                    , a.* 
                from hbf_brugere a where deaktiv != 1 and telefon != '88888888' and (SELECT SUM(point) FROM hbf_rangliste b where a.bruger_id = b.bruger_id and date > '$start' ) > 0 ORDER BY rang DESC,navn";
    } else {
        $sql = "SELECT *, rangliste as rang from hbf_brugere where deaktiv != 1 and rangliste > 0 ORDER BY rang DESC,navn ";
    }
    
     $i = 0;
    $result = mysqli_query($link,$sql) or die(mysqli_error($link));
    while($row = mysqli_fetch_array($result)){
        $i++;
        echo "
        <tr>
        <td>".$i."</td>
        <td><a href='bruger.php?id=".$row["bruger_id"]."' style='font-weight:bold;'>".$row["navn"]."</a></td>
        <td>".$row["rang"]."</td>

        </tr>

        ";
    }
