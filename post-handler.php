<?php

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
/**
 * @project Bridge shoppingcart
 * Manage submitted form values( POST )
 */
if (isset($_REQUEST['action']))
{
    /* User Registartion */
    if ($_REQUEST['action'] == 'REGISTRATION')
    {
        if (( $_POST['firstname'] ) && ( $_POST['email'] ) && ( $_POST['username'] ) && ( $_POST['password'] ) && ( $_POST['confirmpassword'] ))
        {
            if (( $_POST['password'] == $_POST['confirmpassword']))
            {
                include_once 'common/common-function.php';
                include_once 'controller/user-controller.php';
                $user = new UserController();

                $result = $user->user_registration(bridge_trim_deep($_POST));

                $_SESSION ['user_registration_error']          = '';
                $_SESSION ['user_registration_password_error'] = '';
                $_SESSION ['user_registration_username_error'] = '';
                $_SESSION ['user_registration_post']           = '';

                if ($result)
                {
                    /*
                     * Registration success
                     * Redirect to login page */
                    $user->redirect("index.php?page=login");
                }
                else
                {

                    $_SESSION ['user_registration_username_error'] = 1;
                    $_SESSION ['user_registration_post']           = $_POST;
                    /*
                     * Registration error (Username already exist)
                     * Redirect to registration page */
                    $user->redirect("index.php?page=registration");
                }
            }
            else
            {
                /*
                 * Registartion error (Password and Confirmpassword are not matched)
                 *   */
                $_SESSION ['user_registration_error']          = 1;
                $_SESSION ['user_registration_password_error'] = 1;
            }
        }
        else
        {
            /*
             * Registartion error (Mandatory fields are empty)
             *   */
            $_SESSION ['user_registration_error'] = 1;
        }
    }

    /* Category insert */
    if ($_REQUEST['action'] == 'CATEGORY')
    {
        if ($_POST['category-name'])
        {
            include_once 'controller/category-controller.php';
            $category     = new CategoryController();
            $data['name'] = mysql_escape_string($_POST['category-name']);
            if (isset($_POST['category-id']))
            {
                $data['id'] = $_POST['category-id'];
            }
            $data['status'] = $_POST['cat-status'];
            $result         = $category->insert($data);

            /* Redirect to categories page */
            $category->redirect("index.php?page=categories");
        }
    }

    /* Product insert */
    if ($_REQUEST['action'] == 'PRODUCT')
    {
        include_once 'controller/product-controller.php';
        include_once 'controller/upload-controller.php';

        $product         = new ProductController();
        $uploadedFile    = '';
        $uploadedProduct = '';

        $image_path    = (isset($_FILES['product-image']['name']) && $_FILES['product-image']['name'] != null) ? "'" . mysql_escape_string($_FILES['product-image']['name']) . "'" : '';
        $download_link = (isset($_FILES['product-upload']['name']) && $_FILES['product-upload']['name'] != null) ? "'" . mysql_escape_string($_FILES['product-upload']['name']) . "'" : '';
        $product_id    = (isset($_POST['product-id'])) ? $_POST['product-id'] : '';

        if (isset($_POST['product-id']))
        {
            $data['id'] = $_POST['product-id'];
            $product_id  = $data['id'];
            if ($image_path === '')
            {
                $image_path    = "'" . $_POST['hid-product-image'] . "'";
                $uploadedFile = $image_path;
            }

            if ($download_link === '')
            {
                $download_link    = "'" . $_POST['hid-product-upload'] . "'";
                $uploadedProduct = $download_link;
            }
        }

        $data['name']         = "'" . mysql_escape_string($_POST['product-name']) . "'";
        $data['description']  = "'" . mysql_escape_string($_POST['product-desc']) . "'";
        $data['cat_id']        = $_POST['product-cat'];
        $data['price']        = (is_numeric($_POST['product-price'])) ? $_POST['product-price'] : 0;
        $data['download_link'] = $download_link;
        $data['validity']     = (is_numeric($_POST['product-validity'])) ? $_POST['product-validity'] : 0;
        $data['image_path']    = $image_path;
        $data['status']       = $_POST['product-status'];

        $result = $product->insert($data);

        /* Upload the thumbnail image and set path */
        $config = array(
            'overwrite'       => true,
            'upload_path'     => 'uploads',
            'allowed_types'   => $config['allowed_types'],
            'filename_prefix' => (mysql_insert_id() != null) ? mysql_insert_id() . '_' : $product_id . '_'
        );

        $upload = new UploadController($config);
        if ($uploadedFile === '')
        {
            $uploadedFile = $upload->do_upload('product-image');
        }

        // Upload product
        if ($uploadedProduct === '')
        {
            $uploadedPrduct = $upload->do_upload('product-upload');
        }

        /* Redirect to categories page */
        $product->redirect("index.php?page=products");
    }
}
?>