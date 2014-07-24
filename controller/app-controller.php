<?php

/**
 * @project Bridge shoppingcart
 * Main controller
 */

include_once 'controller/database-controller.php';

class AppController {

    public $protocal_array = '';
    public $host = '';
    public $protocal = '';
    public $request_uri_array = '';
    public $request_uri = '';
    public $database = '';

    /**
     * Constructor
     */
    function __construct() {
        $this->database = new DataBaseController();
    }    

    /**
     * Redirect to the page
     * @param string $page
     */
    function redirect($page) {
        $this->protocal_array = explode('/', $_SERVER['SERVER_PROTOCOL']);
        $this->host = $_SERVER['HTTP_HOST'] . '/';
        $this->protocal = strtolower($this->protocal_array[0]) . '://';
        $this->request_uri_array = explode('/', $_SERVER['REQUEST_URI']);
        $this->request_uri = $this->request_uri_array[1] . '/';

        header('location:' . $this->protocal . $this->host . $this->request_uri . $page);
    }

}
