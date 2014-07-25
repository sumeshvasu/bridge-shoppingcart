<?php

/**
 * @project Bridge shoppingcart
 * Manage User actions
 */

include_once 'controller/application-controller.php';

class UserController extends AppController
{

    public $protocal_array 		= '';
    public $host 				= '';
    public $protocal 			= '';
    public $request_uri_array 	= '';
    public $request_uri 		= '';

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

        if ($username != '' && $password != '') {
            $this->database->userLogin($username, $password);
        } else {
            $_SESSION ['user_login_error'] = 1;
        }
    }

}
