<?php

/**
 * @project Bridge shoppingcart
 * Manage categories
 */

include_once 'app-controller.php';
include_once 'database-controller.php';

Class CategoryController extends AppController{
    
    public $database = '';

    /**
     * Constructor
     */
    function __construct() {
        $this->database = new DataBaseController();
    }
    
    /**
     * Get all categories
     * @param int $categoryId
     * @return array
     */
    public function get($categoryId = null)
    {   
        if($categoryId != null) {
            $categories = $this->database->categoryById($categoryId);
        } else {
            $categories = $this->database->categoryGetAll();
        }
        return $categories;
    }
    
    /**
     * Manage category insert
     * @param array $data
     */
    public function insert($data) {
        $result = $this->database->categoryInsert($data);
        return $result;
    }
    
    /**
     * Delete category
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $result = $this->database->categoryDelete($id);
        return $result;
    }
}
