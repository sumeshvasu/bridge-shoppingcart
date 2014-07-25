<?php

/**
 * @project Bridge shoppingcart
 * Manage database queries and methods
 */


include_once 'config.php';

class DataBaseController {

    protected $db_host;
    protected $db_user;
    protected $db_pass;
    protected $db_name;
    protected $db_table_prefix;

    /**
     * Constructor
     */
    function __construct()
    {
        $config = getDbConfig();
        $this->db_host = $config['host'];
        $this->db_user = $config['user'];
        $this->db_pass = $config['password'];
        $this->db_name = $config['name'];
        $this->db_table_prefix = $config['table_prefix'];

        $this->dbConnect();
    }

    /**
     * Create the DB conenction
     */
    function dbConnect()
    {
        $link = mysql_connect($this->db_host, $this->db_user, $this->db_pass);

        if (!$link) {
            echo 'DataBase Connection Error!!!';
        } else {
            mysql_select_db($this->db_name);
        }
    }

    /**
     * Validate the user credentials
     * @param string $username
     * @param string $password
     */
    function userLogin($username, $password)
    {

        $query = "SELECT *
        		  FROM " . $this->db_table_prefix. "users u
        		  JOIN " . $this->db_table_prefix. "roles r ON u.roleId = r.id
        		  WHERE username='" . $username . "' AND password='" . $password . "'
        		  LIMIT 0,1";

        $result = $this->commonDatabaseAction($query);

        if (mysql_num_rows($result) > 0) {

            $user_details 					= mysql_fetch_assoc($result);
            $_SESSION ['user_id'] 			= $user_details['id'];
            $_SESSION ['user_first_name'] 	= $user_details['firstName'];

            if ($result['lastName'] !== '') {
                $_SESSION ['user_last_name'] = $user_details['lastName'];
            } else {
                $_SESSION ['user_last_name'] = '';
            }

            $_SESSION ['user_role'] 		= $user_details['roleId'];
        } else {
            $_SESSION ['user_login_error'] 	= 1;
        }
    }

    /**
     * Get all categories
     * @return array
     */
    public function categoryGetAll()
    {
        $query 	= "SELECT *
                   FROM " . $this->db_table_prefix . "categories";

        $result = $this->commonDatabaseAction($query);
        if (mysql_num_rows($result) > 0) {
            //return $result;
            return $this->resultArray($result);
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
        if (isset($data['id'])) {
            $query 	= "UPDATE
            		 " . $this->db_table_prefix . "categories
            		 SET name = '" . $data['name'] . "', status = " . $data['status'] . "
            		 WHERE id = " . $data['id'];
        } else {
            $query 	= "INSERT INTO
            		 " . $this->db_table_prefix . "categories(id, name, status)
            		 VALUES(null, '" . $data['name'] . "'," . $data['status'] . ")";
        }
        $result 	= $this->commonDatabaseAction($query);

        if (mysql_affected_rows($result) > 0) {
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
        $query 	= "SELECT *
                   FROM " . $this->db_table_prefix . "categories
        	   WHERE id = $id";
        $result = $this->commonDatabaseAction($query);
        if (mysql_num_rows($result) > 0) {
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
        $query 	= "DELETE
        	   FROM " . $this->db_table_prefix . "categories
        	   WHERE id = $id";
        $result = $this->commonDatabaseAction($query);
        if (mysql_affected_rows($result) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Get all products
     * @return array
     */
    public function productGetAll()
    {
        $query 	= "SELECT *
                   FROM " . $this->db_table_prefix . "products";
        $result = $this->commonDatabaseAction($query);
        if (mysql_num_rows($result) > 0) {
            return $this->resultArray($result);
        } else {
            return null;
        }
    }

    /**
     * Get the product by Id
     * @param int $id
     */
    public function productGetById($id)
    {
        $query 	= "SELECT *
        	   FROM " . $this->db_table_prefix . "products
        	   WHERE id = $id";
        $result = $this->commonDatabaseAction($query);
        if (mysql_num_rows($result) > 0) {
            return mysql_fetch_assoc($result);
        } else {
            return null;
        }
    }

    /**
     * Get the product by category Id
     * @param int $catId
     */
    public function productGetByCategory($catId)
    {
        $query 	= "SELECT *
        	   FROM " . $this->db_table_prefix . "products
        	   WHERE catId = $catId";
        $result = $this->commonDatabaseAction($query);
        if (mysql_num_rows($result) > 0) {
            return $this->resultArray($result);
        } else {
            return null;
        }
    }

    /**
     * Insert product data
     * @param array $data
     */
    public function productInsert($data)
    {
        if (isset($data['id'])) {
            $setValues = '';

            foreach($data as $key => $val) {
                $setValues .= $key .' = ' . $val .',';
            }
            $setValues = rtrim($setValues, ',') . ' ';
            $query = "UPDATE
                     " . $this->db_table_prefix . "products
            	     SET " . $setValues . "
            	     WHERE id = " . $data['id'];
        } else {
            $insertValues = 'null,';
            foreach($data as $key => $val) {
                $insertValues .= $val .',';
            }
            $insertValues = rtrim($insertValues, ',');
            $query = "INSERT INTO
            		 " . $this->db_table_prefix . "products
            		 VALUES(" . $insertValues. ")";
        }

        $result = $this->commonDatabaseAction($query);
        if (mysql_affected_rows($result) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Delete a product
     * @param int $id
     * @return bool
     */
    public function productDelete($id)
    {
        $query 	= "DELETE
        		  FROM " . $this->db_table_prefix . "products
        		  WHERE id = $id";
        $result = $this->commonDatabaseAction($query);
        if (mysql_affected_rows($result) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Creates an array result for a DB query
     * @param object $result mysql result object
     * @return array
     */
    public function resultArray($recordset)
    {
        $result 	= array();
        while ($row = mysql_fetch_assoc($recordset)) {
            $result[] = $row; // Inside while loop
        }
        return $result;
    }

    /**
     * Common method for Db query execution
     * @param string $query
     * @return array
     */
    function commonDatabaseAction($query)
    {
        $result = mysql_query($query);

        if (!mysql_error()) {
            return $result;
        } else {
            echo mysql_error();
        }
    }

    /**
     * This function used to return a field value FROM database.
     * @parameter 1.query, 2.status
     *
     */
    function singlevalue($sql,$stat=FALSE) {

    	$result = $this->commonDatabaseAction($sql);

    	if (mysql_num_rows($result)){
    		$returnResult = mysql_result($result,0);
    	}else {
    		if ($stat==true) {
    			$returnResult = "";
    		}else {
    			$returnResult = 0;
    		}
    	}
    	return $returnResult;
    }

    /*  */
    function userRegistration( $first_name, $last_name, $email, $username, $password )
    {

    	$checking_query= "SELECT *
						   FROM " . $this->db_table_prefix. "users
					       WHERE username='".$username."' AND roleId='2'
  		     			   LIMIT 0,1";

    	if( $this->singlevalue( $checking_query ) == 0 ) {
	    	$query 	= "INSERT INTO
	            	  " . $this->db_table_prefix. "users(username, password, firstName, lastName, email, roleId)
	            	  VALUES('" . $username . "','" . $password."','" . $first_name."','" . $last_name."','". $email."', 2 )";

	    	$this->commonDatabaseAction($query);
	    	return true;
    	}
    	else
    	{
    		return false;
    	}
    }

}
