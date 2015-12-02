<?php

if (isset($_SESSION['username'])) {
    $user_set = true;
} else {
    if (!isset($Global_ignorecheck)) {
        header("location:/login.php");
    }
}