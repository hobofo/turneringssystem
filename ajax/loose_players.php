<?php
setlocale(LC_ALL, 'da_DK');
date_default_timezone_set('Europe/Copenhagen');
session_start();
require_once('../auth.php');
$dbh = require_once('../pdo_connection.php');

$tournamentId = $_GET['tournamentId'];
$playerId = $_GET['exceptPlayer'];

// TODO: validation

$query = "SELECT spillere.spiller_id, brugere.navn
FROM hbf_brugere as brugere
INNER JOIN hbf_spillere AS spillere
ON spillere.spiller = brugere.bruger_id
WHERE medspiller = ''
AND primaer = 1
AND turnering_id = ?
AND spiller_id <> ?
ORDER BY brugere.navn";

$stmt = $dbh->prepare($query);
$success = $stmt->execute(array($tournamentId, $playerId));

// TODO: Error handling for failing MySQL query
if(!$success) {
    die('Something went wrong');
}

$rows = $stmt->fetchAll();

$players = array_map(function($row) {
    return [
        'playerId' => $row['spiller_id'],
        'name' => $row['navn']
    ];
}, $rows);

header('Content-Type:application/json');
echo json_encode($players);