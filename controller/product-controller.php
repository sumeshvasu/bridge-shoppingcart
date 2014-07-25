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
     * @param array $filters
     * @return array
     */
    public function get($filters = array())
    {        
        if(isset($filters['id'])) {
            $result = $this->database->productGetById($filters['id']);
        } else if(isset($filters['catId'])) {            
            $result = $this->database->productGetByCategory($filters['catId']);            
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
