<?php
$opdater = true;

if(!isset($_GET["kvart"])){
    require_once("turnering_start.php");
} else {
    require_once("turnering_kvart.php");
}
?>
