<?php
/**
 * @project Bridge shoppingcart
 * Main Index page
 *
 */
session_start ();

error_reporting ( E_ALL );

if (! isset ( $_SESSION ['user_id'] ))
{
	$_SESSION ['user_id'] = '';
}

include_once 'controller/user-controller.php';
include_once 'common/common-function.php';

$user 	= new UserController ();
$application 	= new AppController ();

if ( (isset ( $_POST ) ) && (isset ( $_POST ['btnLoginSubmit'] ) ) )
{
	$user->userLogin ( bridge_trim_deep ( $_POST ) );

	if (isset ( $_SESSION ['user_id'] ) && ( isset( $_SESSION ['user_role'] ) && $_SESSION ['user_role'] == 1 ) )
	{
		$application->redirect ( 'index.php?page=dashboard' );
	}
	else
	{
		if ( (isset ( $_POST ['btnLoginSubmit'] ) ) )
		{
			$application->redirect ( 'index.php?page=login' );
		}
		else
		{
			$application->redirect ( 'index.php?page=home' );
		}
	}
}

include_once 'layout/header.php';


$current_file_name = basename ( $_SERVER ['REQUEST_URI'], ".php" );
$current_file_name = '';

if (basename ( $_SERVER ['REQUEST_URI'], ".php" ) == 'index' )
{
	$current_file_name = 'index';
}
else
{
	if( isset( $_GET ['page'] ) )
	{
		$current_file_name = $_GET ['page'];
	}
	else
	{
		$current_file_name = 'index';
	}
}

selectMenuItem ( $current_file_name );

if ( $current_file_name == 'index' )
{
        include_once 'controller/product-controller.php';
        $product = new ProductController();
        $products = $product->get();
	include_once 'templates/home.php';
}
// Login page
else if ( $current_file_name == 'login' )
{
	if ( isset ( $_SESSION ['user_id'] ) && ( $_SESSION ['user_id'] == '' ) )
	{
		include_once 'templates/login.php';
	}
	else
	{
		if ( $_SESSION ['user_role'] == 1 )
		{
			$application->redirect ( 'index.php?page=dashboard' );
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
	if ( isset ( $_SESSION ['user_id'] ) && ( $_SESSION ['user_id'] == '' ) )
	{
		include_once 'templates/registration.php';
	}
	else
	{
		if ( $_SESSION ['user_role'] == 1 )
		{
			$application->redirect ( 'index.php?page=dashboard' );
		}
		else
		{
			include_once 'templates/home.php';
		}
	}
}
// Dashboard
else if ( $current_file_name == 'dashboard' )
{
	if ( isset ( $_SESSION ['user_id'] ) && ( $_SESSION ['user_id'] == '' ) )
	{
		$application->redirect ( 'index.php?page=login' );
	}
	else
	{
		include_once 'templates/admin-dashboard.php';
	}
}
// Categories List
else if ( $current_file_name == 'categories' )
{
	include_once 'controller/category-controller.php';
	include_once 'controller/paginate-controller.php';

	$paginator 		= new PaginateController ();
	$category 		= new CategoryController ();
	$categories 	= $category->get ();

	include_once 'templates/categories.php';
}
// New category
else if ( $current_file_name == 'new-category' )
{
	include_once 'templates/new-category.php';
}
// Edit category
else if ( $current_file_name == 'edit-category' )
{
	$categoryId 	= $_GET ['id'];

	include_once 'controller/category-controller.php';
	$category 		= new CategoryController ();
	$category_info 	= $category->get ( $categoryId );

	include_once 'templates/new-category.php';
}
// Delete category
else if ( $current_file_name == 'delete-category' )
{
	include_once 'controller/category-controller.php';
	$category 	= new CategoryController ();
	$id 		= $_GET ['id'];
	$category->delete ( $id );
	$category->redirect ( 'index.php?page=categories' );
}
// Products listing
else if ( $current_file_name == 'products' )
{
	// include_once 'products.php';
	include_once 'controller/product-controller.php';
	include_once 'controller/paginate-controller.php';

	$paginator 	= new PaginateController ();
	$product 	= new ProductController ();
	$filters 	= (! empty ( $_GET )) ? $_GET : array ();
	$products 	= $product->get ( $filters );

	include_once 'templates/products.php';
}
// New product
else if ( $current_file_name == 'new-product' )
{
	include_once 'controller/category-controller.php';
	$category 	= new CategoryController ();
	$categories = $category->get ();

	include_once 'templates/new-product.php';
}
// Edit product
else if ( $current_file_name == 'edit-product' )
{
	$productId = $_GET ['id'];
	include_once 'controller/product-controller.php';
	$product 		= new ProductController ();
	$product_info 	= $product->get( array( 'id' => $productId ) );

	include_once 'controller/category-controller.php';
	$category 	= new CategoryController ();
	$categories = $category->get ();

	include_once 'templates/new-product.php';
}
// Delete product
else if ( $current_file_name == 'delete-product' )
{
	include_once 'controller/product-controller.php';
	$product 	= new ProductController ();
	$id 		= $_GET ['id'];
	$product->delete ( $id );
	$product->redirect ( 'index.php?page=products' );
}
// Purchases
else if ( $current_file_name == 'purchases' )
{
	include_once 'purchases.php';
}
// Customers
else if ( $current_file_name == 'customers' )
{
	include_once 'customers.php';
}


include_once 'layout/footer.php';
?>