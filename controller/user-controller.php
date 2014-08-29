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
    function user_login($post)
    {
        $username = addslashes($post['username']);
        $password = addslashes($post['password']);

        if ($username != '' && $password != '')
        {
            $this->database->user_login($username, $password);
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
    function user_registration($post)
    {

        $first_name = addslashes($post['firstname']);
        $last_name  = addslashes($post['lastname']);
        $email      = addslashes($post['email']);
        $username   = addslashes($post['username']);
        $password   = addslashes($post['password']);        
        $result     = $this->database->user_registration($first_name, $last_name, $email, $username, $password);        
        return $result;
    }
    
    /**
     * Get the uset info based on the filters
     * $filters array conditional params
     * @return array
     */
    function get($filters = array())
    {
        if (isset($filters['id']))
        {
            $result = $this->database->user_get_by_id($filters['id']);
        }        
        else
        {
            $result = $this->database->user_get_all();
        }
        return $result;
    }

}
