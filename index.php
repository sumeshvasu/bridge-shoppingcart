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
    } else if ($current_file_name == 'categories') {
        include_once 'controller/category-controller.php';
        $category = new CategoryController();
        $categories = $category->get();        
        include_once 'templates/categories.phtml';
    } else if ($current_file_name == 'new-category') {               
        include_once 'templates/new-category.phtml';
    } else if ($current_file_name == 'edit-category') {
        $categoryId = $_GET['id'];
        include_once 'controller/category-controller.php';
        $category = new CategoryController();
        $categoryInfo = $category->get($categoryId);    
        include_once 'templates/new-category.phtml';
    } else if ($current_file_name == 'delete-category') {               
        include_once 'controller/category-controller.php';
        $category = new CategoryController();
        $id = $_GET['id'];
        $category->delete($id);     
        $category->redirect('index.php?page=categories');
    } else if ($current_file_name == 'products') {
        include_once 'products.php';
    } else if ($current_file_name == 'purchases') {
        include_once 'purchasess.php';
    } else if ($current_file_name == 'customers') {
        include_once 'customers.php';
    }
    
}
include_once 'layout/footer.php';
?>