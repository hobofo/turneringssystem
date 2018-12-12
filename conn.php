<?php

$default_url = "http://localhost/";
$link = mysql_connect('localhost', 'root', '')OR die(mysql_error());
mysql_set_charset('utf8',$link);
$db_selected = mysql_select_db('hobofo_dk', $link); 