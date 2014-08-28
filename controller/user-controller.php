<?php

/**
 * @project Bridge shoppingcart
 * Manage User actions
 */
include_once 'controller/application-controller.php';

class UserController extends AppController
{

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Manage login submit
     * @param array $post
     */
    function userLogin($post)
    {
        $username = addslashes($post['username']);
        $password = addslashes($post['password']);

        if ($username != '' && $password != '')
        {
            $this->database->userLogin($username, $password);
        }
        else
        {
            $_SESSION ['user_login_error'] = 1;
        }
    }

    /**
     * Manage user registration submit
     * @param array $post
     */
    function userRegistration($post)
    {

        $first_name = addslashes($post['firstname']);
        $last_name  = addslashes($post['lastname']);
        $email      = addslashes($post['email']);
        $username   = addslashes($post['username']);
        $password   = addslashes($post['password']);
        $result     = $this->database->userRegistration($first_name, $last_name, $email, $username, $password);
        return $result;
    }

}
