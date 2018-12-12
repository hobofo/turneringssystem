<?php

$default_url = "http://localhost/";
$link = mysqli_connect('localhost', 'root', '', 'hobofo_dk')OR die(mysqli_error($link));
mysqli_set_charset($link, 'utf8');
