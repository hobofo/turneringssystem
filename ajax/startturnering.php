<?php
require_once("../functions.php");

$query = mysqli_query($GLOBALS['link'],"INSERT INTO hbf_turnering (date) values (now())");
$id = mysqli_insert_id($link);

echo $id;