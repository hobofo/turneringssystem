<?php

function getSetting($db, $setting){
    $statement = $db->prepare("SELECT * FROM hbf_indstillinger where short = ?");
    $statement->execute([$setting]);
    $setting = $statement->fetch();

    return $setting["setting"];
}
