<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
/**
 * @project Bridge shoppingcart
 * Manage submitted form values( POST )
 */
if ( isset( $_REQUEST['action'] ) )
{
	/* User Registartion */
	if ( $_REQUEST['action'] == 'REGISTRATION' )
	{
		if ( ( $_POST['firstname'] ) &&  ( $_POST['email'] ) &&  ( $_POST['username'] ) && ( $_POST['password'] ) &&  ( $_POST['confirmpassword'] ) )
		{
			if ( ( $_POST['password'] == $_POST['confirmpassword'] ) )
			{
				include_once 'common/common-function.php';
				include_once 'controller/user-controller.php';
				$user 		= new UserController();

				$result 	= $user->userRegistration( bridge_trim_deep ( $_POST ) );

				$_SESSION ['user_registration_error'] 			= '';
				$_SESSION ['user_registration_password_error'] 	= '';
				$_SESSION ['user_registration_username_error'] 	= '';

				if ( $result )
				{
					/* Redirect to login page */
					$user->redirect("index.php?page=login");
				}
				else
				{

					$_SESSION ['user_registration_username_error'] 	= 1;
					/* Redirect to registration page */
					$user->redirect("index.php?page=registration");
				}

			}
			else
			{
				$_SESSION ['user_registration_error'] = 1;
				$_SESSION ['user_registration_password_error'] = 1;
			}
		}
		else
		{
			$_SESSION ['user_registration_error'] = 1;
		}
	}

    /* Category insert */
    if ( $_REQUEST['action'] == 'CATEGORY' )
    {
        if ( $_POST['category-name'] )
        {
            include_once 'controller/category-controller.php';
            $category = new CategoryController();
            $data['name'] = mysql_escape_string($_POST['category-name']);
            if ( isset($_POST['category-id'] ) )
            {
                $data['id'] = $_POST['category-id'];
            }
            $data['status'] = $_POST['cat-status'];
            $result = $category->insert($data);

            /* Redirect to categories page */
            $category->redirect("index.php?page=categories");
        }
    }

    /* Product insert */
    if ( $_REQUEST['action'] == 'PRODUCT' )
    {
        include_once 'controller/product-controller.php';
        include_once 'controller/upload-controller.php';

        $product 		= new ProductController();

        /* Upload the thumbnail image and set path */
        $uploadedFile 	= '';

        if ( $_FILES['product-image'] )
        {
            $config 		= array(
                				'upload_path' => 'uploads',
                				'allowed_types' => 'jpg|gif|png'
            		  		  );
            $upload 		= new UploadController($config);
            $uploadedFile 	= $upload->do_upload('product-image');
        }

        if ( isset($_POST['product-id'] ) )
        {
            $data['id'] 	= $_POST['product-id'];
            if( $uploadedFile == '' )
            {
                $uploadedFile = $_POST['hid-product-image'];
            }
        }

        $data['name'] 			= "'". mysql_escape_string($_POST['product-name']) ."'";
        $data['description'] 	= "'". mysql_escape_string($_POST['product-desc']) ."'";
        $data['catId'] 			= $_POST['product-cat'];
        $data['price'] 			= (is_numeric($_POST['product-price'])) ? $_POST['product-price'] : 0;
        $data['downloadLink'] 	= "'" . $_POST['product-link'] . "'";
        $data['validity'] 		= (is_numeric($_POST['product-validity'])) ? $_POST['product-validity'] : 0;
        $data['imagePath'] 		= "'" . $uploadedFile . "'";
        $data['status'] 		= $_POST['product-status'];

        $result = $product->insert($data);

        /* Redirect to categories page */
        $product->redirect("index.php?page=products");
    }
}
?>