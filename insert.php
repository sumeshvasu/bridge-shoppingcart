<?php
/**
 * @project Bridge shoppingcart
 * Manage insertions
 */
   
if(isset($_REQUEST['action']) && ($_REQUEST['action'] == 'CATEGORY')) {
        
    if($_POST['category-name']) {
        include_once 'controller/category-controller.php';
        $category = new CategoryController();
        $data['name'] = $_POST['category-name'];
        if(isset($_POST['category-id'])) {
            $data['id'] = $_POST['category-id'];
        }
        $data['status'] = $_POST['cat-status'];
        $result = $category->insert($data);
        
        // Redirect to categories page
        $category->redirect("index.php?page=categories");
        
    }
}

?>