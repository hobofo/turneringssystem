<?php
require_once("../functions.php");

$turnering = hentturnering();
$turneringsid = $turnering["turnering_id"];
$spiller_id = $_GET["spiller_id"];

mysqli_query($GLOBALS['link'],"UPDATE hbf_spillere SET betalt = 1 WHERE spiller_id = $spiller_id");

$spiller = spillerinfo($spiller_id);
$bruger = hentbruger($spiller["spiller"]);
echo $bruger["navn"]." har nu betalt";