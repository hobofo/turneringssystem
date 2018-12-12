<?php

$default_url = "http://localhost/";
$link = mysqli_connect('localhost', 'root', '', 'hobofo_dk')OR die(mysqli_error($link));
mysql_set_charset('utf8',$link);
