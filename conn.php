<?php

$dbHost = getenv('DB_HOST');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');
$dbName = getenv('DB_NAME');

// $default_url = "http://localhost/";
$default_url = getenv('BASE_URL');

$link = mysql_connect($dbHost, $dbUser, $dbPass) OR die(mysql_error());
mysql_set_charset('utf8', $link);
$db_selected = mysql_select_db($dbName, $link);