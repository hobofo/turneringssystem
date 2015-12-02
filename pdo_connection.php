<?php

$dbHost = getenv('DB_HOST');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');
$dbName = getenv('DB_NAME');

$connectionString = "mysql:host=$dbHost;dbname=$dbName;charset=utf8";

return new PDO($connectionString, $dbUser, $dbPass);
