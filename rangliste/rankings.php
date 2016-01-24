<?php

// Get a connection to the database, using a PDO instance
$db = include('../pdo_conn.php');

// Define the query for the rankings list
$sql = "SELECT rangliste as points, navn as name from hbf_brugere where deaktiv != 1 and rangliste > 0 ORDER BY points DESC";

// Prepare and execute the statement
$statement = $db->prepare($sql);

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
