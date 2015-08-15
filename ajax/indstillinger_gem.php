<?php
require_once("../functions.php");

if($_GET["type"]== "borde"){
    $borde = $_POST["borde"];
    $borde = stringtodbarray($borde);
    $password = $_POST["password"];
    $brugernavn = $_POST["brugernavn"];
    $final_10 = $_POST["final_10"];

    $query = mysql_query("UPDATE hbf_indstillinger SET setting = '$borde' WHERE short = 'borde'");
    $query = mysql_query("UPDATE hbf_indstillinger SET setting = '$password' WHERE short = 'password'");
    $query = mysql_query("UPDATE hbf_indstillinger SET setting = '$brugernavn' WHERE short = 'brugernavn'");
    $query = mysql_query("UPDATE hbf_indstillinger SET setting = '$final_10' WHERE short = 'final_10'");
    opdaterrangliste();
    echo "Indstillingerne er opdateret";
}

if($_GET["type"]== "rangliste"){
    $nummer = $_GET["nummer"];

    $rangliste = "";
    $rangliste .= $_POST["navn"].",";
    $rangliste .= $_POST["rangliste11"].",";
    $rangliste .= $_POST["rangliste12"].",";
    $rangliste .= $_POST["rangliste13"].",";
    $rangliste .= $_POST["rangliste14"].",";
    $rangliste .= $_POST["rangliste21"].",";
    $rangliste .= $_POST["rangliste22"].",";
    $rangliste .= $_POST["rangliste23"].",";
    $rangliste .= $_POST["rangliste24"];

    $rangliste = stringtodbarray($rangliste);

    $query = mysql_query("UPDATE hbf_indstillinger SET setting = '$rangliste' WHERE short = 'rangliste$nummer'");

    echo "Rangliste skabelon nummer ".($nummer+1)." er opdateret";
}