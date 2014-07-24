<?php

/**
 * @project Bridge shoppingcart
 * Manage User actions
 */
include_once 'controller/app-controller.php';

class UserController extends AppController{

    public $protocal_array = '';
    public $host = '';
    public $protocal = '';
    public $request_uri_array = '';
    public $request_uri = '';    

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Manage login submit
     * @param array $post
     */
    function userLogin($post) {
        $username = addslashes($post['username']);
        $password = addslashes($post['password']);

        if ($username != '' && $password != '') {
            $this->database->userLogin($username, $password);
        } else {
            $_SESSION ['user_login_error'] = 1;
        }
    }

    /**
     * Redirect to the page
     * @param string $page
     */
    function pageRedirect($page) {
        $this->protocal_array = explode('/', $_SERVER['SERVER_PROTOCOL']);
        $this->host = $_SERVER['HTTP_HOST'] . '/';
        $this->protocal = strtolower($this->protocal_array[0]) . '://';
        $this->request_uri_array = explode('/', $_SERVER['REQUEST_URI']);
        $this->request_uri = $this->request_uri_array[1] . '/';

        header('location:' . $this->protocal . $this->host . $this->request_uri . $page);
    }

}
