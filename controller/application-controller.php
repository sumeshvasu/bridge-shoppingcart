<?php

/**
 * @project Bridge shoppingcart
 * Main controller
 */
include_once 'controller/database-controller.php';

class AppController
{

    public $protocal_array    = '';
    public $host              = '';
    public $protocal          = '';
    public $request_uri_array = '';
    public $request_uri       = '';
    public $database          = '';

    /**
     * Constructor
     */
    function __construct()
    {
        $this->database = new DataBaseController();
    }

    /**
     * Redirect to the page
     * @param string $page
     */
    function redirect($page)
    {
        $this->protocal_array    = explode('/', $_SERVER['SERVER_PROTOCOL']);
        $this->host              = $_SERVER['HTTP_HOST'] . '/';
        $this->protocal          = strtolower($this->protocal_array[0]) . '://';
        $this->request_uri_array = explode('/', $_SERVER['REQUEST_URI']);
        $this->request_uri       = $this->request_uri_array[1] . '/';

        header('location:' . $this->protocal . $this->host . $this->request_uri . $page);
    }

    /**
     * Check whether the user is logged in
     * @param int $role
     * @param bool $redirect
     * @return boolean
     */
    public function is_logged_in($role = 0, $redirect = true)
    {
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != '')
        {
            // Check role if it is passed: (For admin user)
            if ($role != 0)
            {
                if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] == $role))
                {
                    return true;
                }
                else
                {
                    if ($redirect)
                    {
                        $this->redirect('index.php?page=login');
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else
            {
                return true;
            }
        }
        else
        {
            if ($redirect)
            {
                $this->redirect('index.php?page=login');
            }
            else
            {
                return false;
            }
        }
    }
    
    /**
     * Is the current user is admin 
     * @return bool
     */
    public function is_admin()
    {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1)
        {            
            return true;
        }
        else
        {            
            return false;
        }
    }
}
