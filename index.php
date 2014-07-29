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

if ((isset($_POST) ) && (isset($_POST ['btnLoginSubmit']) ))
{

	$user->userLogin ( bridge_trim_deep ( $_POST ) );

	if (isset ( $_SESSION ['user_id'] ) && ( isset( $_SESSION ['user_role'] ) && $_SESSION ['user_role'] == 1 ) )
	{
		if ( isset( $_POST['hiddenRedirect'] ) && $_POST['hiddenRedirect'] != '' )
		{
			if ( $_POST['hiddenRedirect'] == 'buyitnow' )
			{
				$application->redirect ( 'templates/buy-it-now.php?productId='.$_SESSION ['buy_it_now_product_id']);
			}
			else if ( $_POST['hiddenRedirect'] == 'addtocart' )
			{
				include_once 'templates/cart.php?productId='.$_SESSION ['add_to_cart_product_id'];
			}
		}
		else
		{
			$application->redirect ( 'index.php?page=dashboard' );
		}
	}
	else
	{

		if ( isset( $_POST['hiddenRedirect'] ) && $_POST['hiddenRedirect'] != '' )
		{
			if ( $_POST['hiddenRedirect'] == 'buyitnow' )
			{
				$application->redirect ( 'index.php?page=buyitnow&productId='.$_SESSION ['buy_it_now_product_id'] );
			}
			else if ( $_POST['hiddenRedirect'] == 'addtocart' )
			{
				$application->redirect ( 'index.php?page=addtocart&productId='.$_SESSION ['add_to_cart_product_id'] );
			}
		}

	}

}

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

        $product = new ProductController();
        $products = $product->get();

        // Get categories
        include_once 'controller/category-controller.php';
        $category   = new CategoryController();
        $categories = $category->get();
        include_once 'templates/home.php';

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
	if ( isset ( $_SESSION ['user_id'] ) && ( $_SESSION ['user_id'] == '' ) )
	{
		if ( isset( $_GET['productId'] ) && $_GET['page'] == 'buyitnow' )
		{
			$_SESSION ['buy_it_now_product_id'] = $_GET['productId'] ;
			include_once 'templates/login.php';
		}
		else
		{
			include_once 'templates/home.php';
		}
	}
	else
	{
		include_once 'templates/buy-it-now.php';
	}
}
// Add To Cart
else if ($current_file_name == 'addtocart')
{
	if ( isset ( $_SESSION ['user_id'] ) && ( $_SESSION ['user_id'] == '' ) )
	{
		if ( isset( $_GET['productId'] ) && $_GET['page'] == 'addtocart' )
		{
			$_SESSION ['add_to_cart_product_id'] = $_GET['productId'] ;
			include_once 'templates/login.php';
		}
		else
		{
			include_once 'templates/home.php';
		}
	}
	else
	{
		include_once 'templates/buy-it-now.php';
	}
}
// Dashboard
else if ($current_file_name == 'dashboard')
{
        if (isset($_SESSION ['user_id']) && ( $_SESSION ['user_id'] == '' ))
        {
                $application->redirect('index.php?page=login');
        }
        else
        {
                include_once 'templates/admin-dashboard.php';
        }
}
// Categories List
else if ($current_file_name == 'categories')
{
        if (!isset($_SESSION ['user_role']) && ( $_SESSION ['user_role'] != 1 ))
        {
                $application->redirect('index.php?page=login');
        }
        
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
        if (!isset($_SESSION ['user_role']) && ( $_SESSION ['user_role'] != 1 ))
        {
                $application->redirect('index.php?page=login');
        }

        include_once 'templates/new-category.php';
}
// Edit category
else if ($current_file_name == 'edit-category')
{
        if (!isset($_SESSION ['user_role']) && ( $_SESSION ['user_role'] != 1 ))
        {
                $application->redirect('index.php?page=login');
        }
        
        $categoryId = $_GET ['id'];

        include_once 'controller/category-controller.php';
        $category      = new CategoryController ();
        $category_info = $category->get($categoryId);

        include_once 'templates/new-category.php';
}
// Delete category
else if ($current_file_name == 'delete-category')
{
        if (!isset($_SESSION ['user_role']) && ( $_SESSION ['user_role'] != 1 ))
        {
                $application->redirect('index.php?page=login');
        }
        
        include_once 'controller/category-controller.php';
        $category = new CategoryController ();
        $id       = $_GET ['id'];
        $category->delete($id);
        $category->redirect('index.php?page=categories');
}
// Products listing
else if ($current_file_name == 'products')
{
        if (!isset($_SESSION ['user_role']) && ( $_SESSION ['user_role'] != 1 ))
        {
                $application->redirect('index.php?page=login');
        }
        
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
        if (!isset($_SESSION ['user_role']) && ( $_SESSION ['user_role'] != 1 ))
        {
                $application->redirect('index.php?page=login');
        }
        
        include_once 'controller/category-controller.php';
        $category   = new CategoryController ();
        $categories = $category->get();

        include_once 'templates/new-product.php';
}
// Edit product
else if ($current_file_name == 'edit-product')
{
        if (!isset($_SESSION ['user_role']) && ( $_SESSION ['user_role'] != 1 ))
        {
                $application->redirect('index.php?page=login');
        }
        
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
        if (!isset($_SESSION ['user_role']) && ( $_SESSION ['user_role'] != 1 ))
        {
                $application->redirect('index.php?page=login');
        }
        
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


include_once 'layout/footer.php';
?>