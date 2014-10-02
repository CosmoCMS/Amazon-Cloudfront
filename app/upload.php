<?php
/* Uncomment for error reporting
ini_set('display_errors', true);
error_reporting(E_ALL);
 */
require_once '../../../core/app/autoload.php';
require_once '../../../core/app/Cosmo.class.php';
require_once 'S3.php';
$Cosmo = new Cosmo($pdo, $prefix, $salt);

date_default_timezone_set('America/New_York');

// Pull the security credentials from the database
$amazonObj = $Cosmo->miscRead('amazonCloudfrontSettings');
$amazonJson = json_decode($amazonObj['value']);
$s3 = new S3($amazonJson->accessKey, $amazonJson->secretKey);

echo $s3->listBuckets();

// Save original image
$file = $_SERVER['DOCUMENT_ROOT'] .'/'. $_GET['file'];
$s3->putObjectFile($file, $amazonJson->bucket, str_replace('uploads/', '', $_GET['file']), S3::ACL_PUBLIC_READ);

// Save resized images
foreach(array(320, 512, 1024, 2048) as $size){
    $extension = end(explode('.', $_GET['file']));
    $resizedFile = str_replace('.'.$extension, '-'.$size.'.'.$extension, $_GET['file']);
    $file = $_SERVER['DOCUMENT_ROOT'] .'/'. $resizedFile;
    $s3->putObjectFile($file, $amazonJson->bucket, str_replace('uploads/', '', $resizedFile), S3::ACL_PUBLIC_READ);
}

?>