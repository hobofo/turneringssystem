<?php

require_once('../functions.php');

$tournamentId = $_POST['tournamentId'];
$playerId = $_POST['playerId'];

$query = "DELETE FROM hbf_spillere WHERE spiller_id = ".$playerId." AND turnering_id = " . $tournamentId;

$success = mysql_query($query);

if ($success) {
	$status = 200;
	$response = ['message' => 'Spiller fjernet'];	
} else {
	$status = 500;
	$response = ['message' => 'Kunne ikke fjerne spiller'];	
}

http_response_code($status);
header('Content-Type:application/json');
echo json_encode($response);