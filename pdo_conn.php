<?php

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'hobofo_dk';

$connectionString = "mysql:host=$hostname;dbname=$database;charset=utf8";

return new PDO($connectionString, $username, $password);

