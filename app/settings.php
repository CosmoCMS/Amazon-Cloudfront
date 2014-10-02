<?php

include '../../../core/app/autoload.php';
include '../../../core/app/Cosmo.class.php';
$Cosmo = new Cosmo($pdo, $prefix, $salt);

$_POST = json_decode(file_get_contents("php://input"), TRUE);

if($_POST) {
    $settings = array(
        'accessKey' => $_POST['accessKey'],
        'secretKey' => $_POST['secretKey'],
        'bucket' => $_POST['bucket'],
        'cloudfrontURL' => $_POST['cloudfrontURL']
    );

    $Cosmo->miscUpdate('amazonCloudfrontSettings', json_encode($settings));
} else if($_GET['auth']){
    echo $Cosmo->miscRead('amazonCloudfrontSettings');
} else
    echo json_encode(array('url'=>  json_decode($Cosmo->miscRead('amazonCloudfrontSettings'))->cloudfrontURL));

?>