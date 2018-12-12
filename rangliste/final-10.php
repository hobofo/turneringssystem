<?php

// Include the PDO helper functions
require_once('../pdoFunctions.php');

// Get a connection to the database, using a PDO instance
$db = include('../pdo_conn.php');

// Get the final 10 start date
$final10StartDate = getSetting($db, 'final_10');

// This piece of SQL was used twice in the following statement, so it made sense to
// extract it out to a separate variable
$pointsSql = "(SELECT SUM(point) FROM hbf_rangliste b where a.bruger_id = b.bruger_id and date > :startDate)";

// Define the query for the rankings list
$sql = "SELECT $pointsSql as points,
        a.navn as name from hbf_brugere a where deaktiv != 1 and telefon != '88888888' and $pointsSql > 0 ORDER BY points DESC";

// Prepare statement and bind the start date
$statement = $db->prepare($sql);
$statement->bindParam(':startDate', $final10StartDate);

// Set default return values to error case
$statusCode = 500;
$result = [
    'status_code' => 500,
    'message' => 'Something went wrong.'
];

// If the statement executes successfully (which it most likely will) ...
if ($statement->execute()) {

    // Set the $result variable to the result of the query
    $result = array_map(function($row) {
        return [
            'name'      => $row['name'],
            'points'    => $row['points']
        ];
    }, $statement->fetchAll());

    // Set the status code to 200
    $statusCode = 200;
}

// Return response to client
http_response_code($statusCode);
header('Content-Type: application/json');
echo json_encode($result);
