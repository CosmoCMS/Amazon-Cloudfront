<?php

require_once '../../../core/app/autoload.php';
require_once '../../../core/app/Cosmo.class.php';
$Cosmo = new Cosmo($pdo, $prefix, $salt);

// Check permissions for authorized requests
if($Cosmo->tokensRead($_SERVER['HTTP_USERSID'], $_SERVER['HTTP_TOKEN'])){
    if($Cosmo->usersRead($_SERVER['HTTP_USERSID'])['role'] === 'admin')
    {
        // This record will be used for settings when they set them up
        $Cosmo->miscCreate('amazonCloudfrontSettings', '');
    }
}

?>