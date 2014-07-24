<?php

/**
 * @project Bridge shoppingcart
 * Manage insertions
 */
if (isset($_REQUEST['action'])) {

    // Category insert
    if ($_REQUEST['action'] == 'CATEGORY') {
        if ($_POST['category-name']) {
            include_once 'controller/category-controller.php';
            $category = new CategoryController();
            $data['name'] = mysql_escape_string($_POST['category-name']);
            if (isset($_POST['category-id'])) {
                $data['id'] = $_POST['category-id'];
            }
            $data['status'] = $_POST['cat-status'];
            $result = $category->insert($data);

            // Redirect to categories page
            $category->redirect("index.php?page=categories");
        }
    }

    // Product insert
    if ($_REQUEST['action'] == 'PRODUCT') {
        include_once 'controller/product-controller.php';
        include_once 'controller/upload-controller.php';
        $product = new ProductController();
        
        
        // Upload the thumbnail image and set path  
        $uploadedFile = '';
        if ($_FILES['product-image']) {
            $config = array(
                'upload_path' => 'uploads',
                'allowed_types' => 'jpg|gif|png'
            );
            $upload = new UploadController($config);
            $uploadedFile = $upload->do_upload('product-image');
        }
        
        if (isset($_POST['product-id'])) {
            $data['id'] = $_POST['product-id'];
            if($uploadedFile == '') {
                $uploadedFile = $_POST['hid-product-image'];
            }
        }
        
        $data['name'] = "'". mysql_escape_string($_POST['product-name']) ."'";
        $data['description'] = "'". mysql_escape_string($_POST['product-desc']) ."'";
        $data['catId'] = $_POST['product-cat'];
        $data['price'] = $_POST['product-price'];
        $data['downloadLink'] = "'" . $_POST['product-link'] . "'";
        $data['validity'] = $_POST['product-validity'];                
        $data['imagePath'] = "'" . $uploadedFile . "'";
        $data['status'] = $_POST['product-status']; 
        
        $result = $product->insert($data);
        
        
        // Redirect to categories page
        $product->redirect("index.php?page=products");
    }
}
?>