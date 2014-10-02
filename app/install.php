<?php

include '../../../core/app/autoload.php';
include '../../../core/app/Cosmo.class.php';
$Cosmo = new Cosmo($pdo, $prefix, $salt);

// Check permissions for autorized requests
if($_SERVER['HTTP_USERNAME'] && $_SERVER['HTTP_TOKEN'])
{
    if($Cosmo->tokenValidate($_SERVER['HTTP_USERNAME'], $_SERVER['HTTP_TOKEN'])){
        $username = $_SERVER['HTTP_USERNAME'];
        $role = $Cosmo->usersRead(null, $username);
    }
}

if($role === 'admin'){

    $Cosmo->miscCreate('amazonCloudfrontSettings', '');
    
}

?>