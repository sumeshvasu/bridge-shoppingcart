<?php

/**
 * @project Bridge shoppingcart
 * Main Index page
 *
 */
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

error_reporting(E_ALL);

if (!isset($_SESSION ['user_id']))
{
    $_SESSION ['user_id'] = '';
}

include_once 'controller/user-controller.php';
include_once 'common/common-function.php';

$user        = new UserController ();
$application = new AppController ();

// Login Submit Handler
if ((isset($_POST) ) && (isset($_POST ['btnLoginSubmit']) ))
{

    $user->userLogin(bridge_trim_deep($_POST));

    // If admin logged in
    if ($application->is_logged_in(1, false))
    {
        $application->redirect("index.php?page=index");
    }
    else
    {
        if (isset($_SESSION['page']) && $_SESSION['page'] != '')
        {
            $url_string = 'index.php?page=' . $_SESSION['page'];
            if (isset($_SESSION['url_params']) && !empty($_SESSION['url_params']))
            {
                foreach ($_SESSION['url_params'] as $key => $value)
                {
                    $url_string .= '&' . $key . '=' . $value;
                }
            }

            $application->redirect($url_string);
        }
        else
        {
            $application->redirect("index.php?page=index");
        }
    }
}

// Include the header layout
include_once 'layout/header.php';


$current_file_name = basename($_SERVER ['REQUEST_URI'], ".php");
$current_file_name = '';

if (basename($_SERVER ['REQUEST_URI'], ".php") == 'index')
{
    $current_file_name = 'index';
}
else
{
    if (isset($_GET ['page']))
    {
        $current_file_name = $_GET ['page'];
    }
    else
    {
        $current_file_name = 'index';
    }
}

selectMenuItem($current_file_name);

if ($current_file_name == 'index')
{    
    include_once 'controller/product-controller.php';
    // Get products

    $product  = new ProductController();
    $products = $product->get();

    // Get categories
    include_once 'controller/category-controller.php';
    $category   = new CategoryController();
    $categories = $category->get();
    if($application->is_logged_in(1,false)){
        $home_page  = true;
        include_once 'templates/admin-dashboard.php';
    }
    else
    {
        $home_page  = true;
        include_once 'templates/home.php';
    }
}
// Login page
else if ($current_file_name == 'login')
{	
    if (isset($_SESSION ['user_id']) && ( $_SESSION ['user_id'] == '' ))
    {
        include_once 'templates/login.php';        
    }
    else
    {
		$application->redirect("index.php");        
    }
}
// Registartion
else if ($current_file_name == 'registration')
{
    if (isset($_SESSION ['user_id']) && ( $_SESSION ['user_id'] == '' ))
    {
        include_once 'templates/registration.php';
    }
    else
    {
        if ($_SESSION ['user_role'] == 1)
        {
            $application->redirect('index.php?page=dashboard');
        }
        else
        {
            include_once 'templates/home.php';
        }
    }
}
// Buy It Now
else if ($current_file_name == 'buyitnow')
{
    if (!$application->is_logged_in(0, false))
    {
        if (isset($_GET['productId']) && $_GET['page'] == 'buyitnow')
        {
            $_SESSION ['buy_it_now_product_id'] = $_GET['productId'];
            //include_once 'templates/login.php';
            $_SESSION['page']                   = $_GET['page'];
            $_SESSION['url_params']             = array('productId' => $_GET['productId']);
        }
        $application->redirect("index.php?page=login");
    }
    else
    {
        if(!$application->is_admin()) 
        {
            // Get the selected product detail
            $product_id = (isset($_GET['productId'])) ? $_GET['productId'] : '';
            if ($product_id != '')
            {
                include_once 'controller/product-controller.php';
                $product     = new ProductController();
                $productInfo = $product->get(array('id' => $product_id));
            }

            include_once 'templates/buy-it-now.php';
        }
        else
        {
            $application->redirect('index.php?page=index');
        }
    }
}
// Add To Cart
else if ($current_file_name == 'addtocart')
{
    if (!$application->is_logged_in(0, false))
    {
        if (isset($_GET['productId']) && $_GET['page'] == 'addtocart')
        {
            //$_SESSION ['buy_it_now_product_id'] = $_GET['productId'];
            //include_once 'templates/login.php';
            $_SESSION['page']       = $_GET['page'];
            $_SESSION['url_params'] = array('productId' => $_GET['productId']);
        }
        $application->redirect("index.php?page=login");
    }
    else
    {
        include_once 'templates/addtocart.php';
    }
}
// Dashboard
else if ($current_file_name == 'dashboard')
{
    $application->is_logged_in(1);
    include_once 'templates/admin-dashboard.php';
}
// Categories List
else if ($current_file_name == 'categories')
{
    $application->is_logged_in(1);

    include_once 'controller/category-controller.php';
    include_once 'controller/paginate-controller.php';

    $paginator  = new PaginateController ();
    $category   = new CategoryController ();
    $categories = $category->get();

    include_once 'templates/categories.php';
}
// New category
else if ($current_file_name == 'new-category')
{
    $application->is_logged_in(1);

    include_once 'templates/new-category.php';
}
// Edit category
else if ($current_file_name == 'edit-category')
{
    $application->is_logged_in(1);

    $categoryId = $_GET ['id'];

    include_once 'controller/category-controller.php';
    $category      = new CategoryController ();
    $category_info = $category->get($categoryId);

    include_once 'templates/new-category.php';
}
// Delete category
else if ($current_file_name == 'delete-category')
{
    $application->is_logged_in(1);

    include_once 'controller/category-controller.php';
    $category = new CategoryController ();
    $id       = $_GET ['id'];
    $category->delete($id);
    $category->redirect('index.php?page=categories');
}
// Products listing
else if ($current_file_name == 'products')
{
    $application->is_logged_in(1);

    // include_once 'products.php';
    include_once 'controller/product-controller.php';
    include_once 'controller/paginate-controller.php';

    $paginator = new PaginateController ();
    $product   = new ProductController ();
    $filters   = (!empty($_GET)) ? $_GET : array();
    $products  = $product->get($filters);

    include_once 'templates/products.php';
}
// New product
else if ($current_file_name == 'new-product')
{
    $application->is_logged_in(1);

    include_once 'controller/category-controller.php';
    $category   = new CategoryController ();
    $categories = $category->get();

    include_once 'templates/new-product.php';
}
// Edit product
else if ($current_file_name == 'edit-product')
{
    $application->is_logged_in(1);

    $productId    = $_GET ['id'];
    include_once 'controller/product-controller.php';
    $product      = new ProductController ();
    $product_info = $product->get(array('id' => $productId));

    include_once 'controller/category-controller.php';
    $category   = new CategoryController ();
    $categories = $category->get();

    include_once 'templates/new-product.php';
}
// Delete product
else if ($current_file_name == 'delete-product')
{
    $application->is_logged_in(1);

    include_once 'controller/product-controller.php';
    $product = new ProductController ();
    $id      = $_GET ['id'];
    $product->delete($id);
    $product->redirect('index.php?page=products');
}
// Purchases
else if ($current_file_name == 'purchases')
{
    include_once 'purchases.php';
}
// Customers
else if ($current_file_name == 'customers')
{
    include_once 'customers.php';
}

// products view
else if ($current_file_name == 'products-view')
{
    include_once 'controller/product-controller.php';
    // Get products
    $product = new ProductController();
    if (isset($_GET['catId']))
    {
        $products = $product->get(array('catId' => $_GET['catId']));
        if (count($products) > 0)
        {
            $cat_name = (isset($products[0]['catName'])) ? $products[0]['catName'] : '';
        }
        else
        {
            $cat_name = '';
        }
    }
    else
    {
        $products = $product->get();
    }

    // Get categories
    include_once 'controller/category-controller.php';
    $category   = new CategoryController();
    $categories = $category->get();

    include_once 'templates/home.php';
}


include_once 'layout/footer.php';
?>
