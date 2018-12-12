<?php

require_once("../functions.php");
$bruger_id = $_GET["id"];
$rangliste = $_POST["rangliste"];
//$navn = $_POST["navn"];

$bruger = hentbruger($bruger_id);
$query = mysqli_query($link,"INSERT INTO hbf_rangliste (bruger_id,text,date,point) values ('".$bruger_id."','Manuel opdatering',now(),'".$rangliste."')") or die(mysql_error());

opdaterrangliste();

$ranglistesql = mysqli_query($link,"SELECT rangliste FROM  hbf_brugere WHERE  bruger_id = '$bruger_id'") or die(mysql_error());
$row = mysql_fetch_array($ranglistesql);

$nyrangliste = $row["rangliste"];

echo "$nyrangliste#".$bruger["navn"]." har fået $rangliste point";


