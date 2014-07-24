<?php
/**
 * @project Bridge shoppingcart
 * Main Index page
 * 
 */
session_start();

error_reporting(E_ALL);

if (!isset($_SESSION ['user_id'])) {
    $_SESSION ['user_id'] = '';
}
        
include_once 'controller/user-controller.php';
include_once 'common/common-function.php';

$user = new UserController();


if (( isset($_POST) ) && ( isset($_POST['btnLoginSubmit']) )) {
    $user->userLogin(bridge_trim_deep($_POST));
}


include_once 'layout/header.php';

if (isset($_SESSION ['user_id']) && ($_SESSION ['user_id'] == '' )) {
    include_once 'login.php';
} else {
    //echo $_SERVER['REQUEST_URI'];
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");
    $current_file_name = '';

    if (basename($_SERVER['REQUEST_URI'], ".php") == 'index') {
        $current_file_name = 'index';
    } else {
        $current_file_name = $_GET['page'];
    }

    selectMenuItem($current_file_name);    
    if ($current_file_name == 'index') {
        include_once 'templates/home.phtml';
    } 
    // Categories List
    else if ($current_file_name == 'categories') {
        include_once 'controller/category-controller.php';
        $category = new CategoryController();
        $categories = $category->get();        
        include_once 'templates/categories.phtml';
    } 
    // New category
    else if ($current_file_name == 'new-category') {               
        include_once 'templates/new-category.phtml';
    } 
    // Edit category
    else if ($current_file_name == 'edit-category') {
        $categoryId = $_GET['id'];
        include_once 'controller/category-controller.php';
        $category = new CategoryController();
        $categoryInfo = $category->get($categoryId);    
        include_once 'templates/new-category.phtml';
    } 
    // Delete category
    else if ($current_file_name == 'delete-category') {               
        include_once 'controller/category-controller.php';
        $category = new CategoryController();
        $id = $_GET['id'];
        $category->delete($id);     
        $category->redirect('index.php?page=categories');
    } 
    // Products listing
    else if ($current_file_name == 'products') {
        //include_once 'products.php';
        include_once 'controller/product-controller.php';
        $product = new ProductController();
        $products = $product->get();
        include_once 'templates/products.phtml';
    } 
    // New product
    else if($current_file_name == 'new-product') {
        include_once 'controller/category-controller.php';
        $category = new CategoryController();
        $categories = $category->database->resultArray($category->get());
        include_once 'templates/new-product.phtml';
    }
    // Edit product
    else if($current_file_name == 'edit-product') {
        $productId = $_GET['id'];
        include_once 'controller/product-controller.php';
        $product = new ProductController();
        $productInfo = $product->get($productId);
        
        include_once 'controller/category-controller.php';
        $category = new CategoryController();
        $categories = $category->database->resultArray($category->get());
        include_once 'templates/new-product.phtml';
    }
    // Delete product
    else if($current_file_name == 'delete-product') {
        include_once 'controller/product-controller.php';
        $product = new ProductController();
        $id = $_GET['id'];
        $product->delete($id);     
        $product->redirect('index.php?page=products');
    }
    // Purchases
    else if ($current_file_name == 'purchases') {
        include_once 'purchasess.php';
    } 
    // Customers
    else if ($current_file_name == 'customers') {
        include_once 'customers.php';
    }
    
}
include_once 'layout/footer.php';
?>