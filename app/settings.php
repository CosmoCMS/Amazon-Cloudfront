<?php

include '../../../core/app/autoload.php';
include '../../../core/app/Cosmo.class.php';
require_once 'S3.php';
$Cosmo = new Cosmo($pdo, $prefix, $salt);

$_POST = json_decode(file_get_contents("php://input"), TRUE);

// Check for a valid token
if($Cosmo->tokensRead($_SERVER['HTTP_USERSID'], $_SERVER['HTTP_TOKEN'])){
    if($_POST) {
        if($Cosmo->usersRead($_SERVER['HTTP_USERSID'])['role'] === 'admin')
        {
            $settings = array(
                'accessKey' => $_POST['accessKey'],
                'secretKey' => $_POST['secretKey'],
                'bucket' => $_POST['bucket'],
                'cloudfrontURL' => $_POST['cloudfrontURL']
            );
            $Cosmo->miscUpdate('amazonCloudfrontSettings', json_encode($settings));
            
            // Upload all files in the upload folder to the cloud
            $s3 = new S3($_POST['accessKey'], $_POST['secretKey']);
            foreach (glob('../../../uploads/*') as $file)
            {
                // Upload image to Amazon
                $fileName = str_replace('../../../', '', $file);
                $rootFile = $_SERVER['DOCUMENT_ROOT'] .'/'. FOLDER . $fileName;
                $s3->putObjectFile($rootFile, $_POST['bucket'], str_replace('uploads/', '', $fileName), S3::ACL_PUBLIC_READ);
            }
        }
    } else if($_GET['auth']){
        if($Cosmo->usersRead($_SERVER['HTTP_USERSID'])['role'] === 'admin')
            echo $Cosmo->miscRead('amazonCloudfrontSettings');
    } else
        echo json_encode(array('url'=>  json_decode($Cosmo->miscRead('amazonCloudfrontSettings'))->cloudfrontURL));
} else // For when the initial load happens before headers are ready
    echo json_encode(array('url'=>  json_decode($Cosmo->miscRead('amazonCloudfrontSettings'))->cloudfrontURL));
?>