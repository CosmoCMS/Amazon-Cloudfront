<?php

include '../../../core/app/autoload.php';
include '../../../core/app/Cosmo.class.php';
$Cosmo = new Cosmo($pdo, $prefix, $salt);

// Check permissions for autorized requests
if($Cosmo->tokensRead($_SERVER['HTTP_USERID'], $_SERVER['HTTP_TOKEN'])){
    if($Cosmo->usersRead($_SERVER['HTTP_USERID'])['role'] === 'admin')
        $Cosmo->miscDelete('amazonCloudfrontSettings');
}

?>