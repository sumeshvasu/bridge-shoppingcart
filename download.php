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
chmod("$file", 0777);
if (headers_sent())
{
    $error = 'HTTP header already sent';
}
else if(!file_exists($file))
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    $error =  'File not found';
}
else if (!is_readable($file))
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    $error =  'File not readable';
}
else
{
// ======== Old header format =============
//    header("Pragma: public");
//    header("Expires: 0");
//    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//    header("Cache-Control: public");
//    header("Content-Description: File Transfer");
//    header("Content-Type:pplication/octet-stream"); // Send type of file
//    $header = "Content-Disposition: attachment; filename=$filename;"; // Send File Name
//    header($header);
//    header("Content-Transfer-Encoding: binary");
//    header("Content-Length: " . $len); // Send File Size            
//    readfile($file);
// =======================================
    
    $fopen = @fopen($file,"rb");
    if($fopen != NULL)
    {		
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Type: application/force-download",FALSE);
        header("Content-Type: application/x-zip-compressed",FALSE);
        header("Content-Type: application/download", FALSE); 
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($file) + 100);			
        fpassthru($fopen);			
        fclose($fopen);
        exit();
    }
    else{
        $error =  'Error occured while reading file';
    }
}

ob_clean();

if(!empty($error)){
    include_once 'layout/header.php';
?>
<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">File downloading: </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-8" style="display: inline">
                    <div class="row">
                        <p>File download page</p>
                    </div>
                    <div id="message"><?php echo $error;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
}
include_once 'layout/footer.php';
?>
