<?php

/**
 * File downloader
 */
include('config.php');
include_once 'controller/user-controller.php';
include_once 'controller/product-controller.php';
include_once 'controller/database-controller.php';
include_once 'common/common-function.php';
//include_once 'layout/footer.php';

$product        = new ProductController();
$product_info   = $product->get(array('token' => $_GET['token']));
$filename       = $product_info[0]['id'] . '_' . $product_info[0]['download_link'];
//die($filename);
$file           = $config['uploads_folder'] . '/' . $filename;
$finfo          = finfo_open(FILEINFO_MIME_TYPE);
$file_mime_type = finfo_file($finfo, $file);
$len            = filesize($file); // Calculate File Size
ob_clean();
//chmod("$file", 0777);
if (headers_sent())
{
    echo 'HTTP header already sent';
}
else if (!is_readable($file))
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    echo 'File not readable';
}
else
{
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Type:pplication/octet-stream"); // Send type of file
    $header = "Content-Disposition: attachment; filename=$filename;"; // Send File Name
    header($header);
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . $len); // Send File Size            
    readfile($file);
}

ob_clean();
include_once 'layout/footer.php';
?>
