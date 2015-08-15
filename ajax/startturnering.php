<?php
require_once("../functions.php");

$query = mysql_query("INSERT INTO hbf_turnering (date) values (now())");
$id = mysql_insert_id();

echo $id;