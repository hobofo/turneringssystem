<?php
require_once("../functions.php");

$query = mysqli_query($link,"INSERT INTO hbf_turnering (date) values (now())");
$id = mysql_insert_id();

echo $id;