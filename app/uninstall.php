<?php

include '../../../core/app/autoload.php';
include '../../../core/app/Cosmo.class.php';
$Cosmo = new Cosmo($pdo, $prefix, $salt);

$Cosmo->miscDelete('amazonCloudfrontSettings');

?>