<?php

/**
 * @project Bridge shoppingcart
 * Manage database queries and methods
 */
class DataBaseController {

    const DB_SERVER = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "root";
    const DB = "bridge-store";
    const TABLE_PREFIX = 'bs_';

    /**
     * Constructor
     */
    function __construct() {
        $this->dbConnect();
    }

    /**
     * Create the DB conenction
     */
    function dbConnect() {
        $link = mysql_connect(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD);

        if (!$link) {
            echo 'DataBase Connection Error!!!';
        } else {
            mysql_select_db(self::DB);
        }
    }

    /**
     * Validate the user credentials
     * @param string $username
     * @param string $password
     */
    function userLogin($username, $password) {

        $pre = self::TABLE_PREFIX;
        $query = "SELECT * FROM " . $pre . "users u JOIN " . $pre . "roles r ON u.roleId = r.id WHERE username='" . $username . "' AND password='" . $password . "' LIMIT 0,1";
        $result = $this->commonDatabaseAction($query);

        if (mysql_num_rows($result) > 0) {

            $user_details = mysql_fetch_assoc($result);
            $_SESSION ['user_id'] = $user_details['id'];
            $_SESSION ['user_first_name'] = $user_details['firstName'];
            if ($result['lastName'] !== '') {
                $_SESSION ['user_last_name'] = $user_details['lastName'];
            } else {
                $_SESSION ['user_last_name'] = '';
            }
            $_SESSION ['user_role'] = $user_details['roleId'];
        } else {
            $_SESSION ['user_login_error'] = 1;
        }
    }

    /**
     * Get all categories
     * @return array
     */
    public function categoryGetAll()
    {        
        $query = "SELECT * FROM " . self::TABLE_PREFIX . "categories";        
        $result = $this->commonDatabaseAction($query);        
        if(mysql_num_rows($result) > 0) {
            return $result;
        } else {
            return array();
        }
    }
    
    /**
     * Insert category
     * @param array $data
     * @return boolean
     */
    public function categoryInsert($data)
    {
        if(isset($data['id'])) {
            $query = "UPDATE " . self::TABLE_PREFIX . "categories SET name = '" . $data['name']. "', status = " . $data['status'] . " WHERE id = " . $data['id'];            
        } else {
            $query = "INSERT INTO " . self::TABLE_PREFIX . "categories(id, name, status) VALUES(null, '" . $data['name']. "'," . $data['status'] . ")";
        }
        $result = $this->commonDatabaseAction($query);        
        if(mysql_affected_rows($result) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Get category by id
     * @param int $id
     */
    public function categoryById($id)
    {
        $query = "SELECT * FROM " . self::TABLE_PREFIX . "categories WHERE id = $id";        
        $result = $this->commonDatabaseAction($query);        
        if(mysql_num_rows($result) > 0) {
            return mysql_fetch_assoc($result);
        } else {
            return array();
        }
    }
    
    /**
     * Delete a category
     * @param int $id
     */
    public function categoryDelete($id)
    {        
        $query = "DELETE FROM " . self::TABLE_PREFIX . "categories WHERE id = $id";
        $result = $this->commonDatabaseAction($query);        
        if(mysql_affected_rows($result) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Common method for Db query execution
     * @param string $query
     * @return array
     */
    function commonDatabaseAction($query) {
        $result = mysql_query($query);

        if (!mysql_error()) {
            return $result;
        } else {
            echo mysql_error();
        }
    }

}
