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

// get product info
$product        = new ProductController();
$product_info   = $product->get(array('token' => $_GET['token']));

ob_clean();
$error = false;
$download = false;

// create zip of products
$zipname = $config['uploads_folder'] . '/products'.  strtotime(date("Y-m-d")).'.zip';
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);

if (headers_sent())
{
    $error .= 'HTTP header already sent <br>';
}

foreach ($product_info as $key => $value) 
{
    
    $filename   = '';
    $file       = '';
    $filename   = $product_info[$key]['id'] . '_' . $product_info[$key]['download_link'];
    $file       = $config['uploads_folder'] . '/' . $filename;

    chmod("$file", 0777);

    if(!file_exists($file))
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        $error .=  'File not found  <br>';
    }
    else if (!is_readable($file))
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
        $error .=  'File not readable  <br>';
    }
    else if(!$fopen = @fopen($file,"rb")){
        $error .=  'Error occured while reading file  <br>';
    }

    if(!$error){
        $download = true;
        $zip->addFile($file);
    }

}
$zip->close();
//die($file);

if($download){
   
    $db = new DataBaseController();
    $db->update_downlaod_count(array(
        'token' => $_GET['token']));

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Type: application/force-download",FALSE);
    header("Content-Type: application/x-zip-compressed",TRUE);
    header("Content-Type: application/download", FALSE); 
    header("Content-Disposition: attachment; filename=\"$zipname\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . filesize($zipname) + 100);	
    readfile($zipname);
    unlink($zipname);
    exit();
}

// Old format

//$finfo          = finfo_open(FILEINFO_MIME_TYPE);
//$file_mime_type = finfo_file($finfo, $file);

//    $fopen = @fopen($file,"rb");
//    if($fopen != NULL)
//    {	
//        header(...)...
//        header("Content-Disposition: attachment; filename=\"$filename\"");
//        header("Content-Length: " . filesize($file) + 100);			
//        fpassthru($fopen);			
//        fclose($fopen);
//        exit();
//    }
//    else{
//        $error =  'Error occured while reading file';
//    }

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
<?php } 
include_once 'layout/footer.php';
?>
