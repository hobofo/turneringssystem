<?php
require_once("../functions.php");

$turnering = hentturnering();
$turneringsid = $turnering["turnering_id"];
$select = mysqli_query($GLOBALS['link'],"SELECT * FROM hbf_kampe WHERE  turnerings_id = '$turneringsid' AND type <> 'p'")or die (mysqli_error($GLOBALS['link']));
if(mysqli_num_rows($select) > 0 ){
    echo "1";
} else {
    echo "0";
}
