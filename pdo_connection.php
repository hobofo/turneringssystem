<?php

$connectionString = 'mysql:host=localhost;dbname=hobofo_dk;charset=utf8';
$username = 'homestead';
$password = 'secret';

return new PDO($connectionString, $username, $password);