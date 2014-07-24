<?php

/**
 * @project Bridge shoppingcart
 * Manage Product actions
 */

include_once 'controller/app-controller.php';

class ProductController extends AppController{

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
     * Get all products/Specific product
     * @param int $id
     * @return array
     */
    public function get($id = null)
    {
        if($id != null) {
            $result = $this->database->productGetById($id);
        } else {
            $result = $this->database->productGetAll();
        }
        return $result;
    }
    
    /**
     * Insert the products data
     * @param array $data
     * @return int
     */
    public function insert($data)
    {
        $result = $this->database->productInsert($data);        
        return $result;
    }
    
    /**
     * Delete the product data
     * @param int $id
     */
    public function delete($id)
    {
        $result = $this->database->productDelete($id);        
        return $result;
    }
    
    

}
