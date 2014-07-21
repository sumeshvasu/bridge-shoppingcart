<?php
class DataBaseController {

	const DB_SERVER 	= "localhost";
	const DB_USER 		= "root";
	const DB_PASSWORD 	= "";
	const DB 			= "bridge-store";
	const TABLE_PREFIX	= 'bs_';

	function __construct(){
		$this->dbConnect();
	}

	function dbConnect()
	{
		$link 	= mysql_connect( self::DB_SERVER, self::DB_USER, self::DB_PASSWORD );

		if( !$link )
		{
			echo 'DataBase Connection Error!!!';
		}
		else
		{
			mysql_select_db( self::DB );
		}
	}

	function userLogin( $username, $password )
	{

		$query = "SELECT * FROM ".self::TABLE_PREFIX."users WHERE username='".$username."' AND password='".$password."' LIMIT 0,1";
		$result = $this->commonDatabaseAction( $query );

		if( mysql_num_rows( $result ) > 0 )
		{

			$user_details = mysql_fetch_assoc( $result );
			$_SESSION ['user_id'] 				= $user_details['id'];
			$_SESSION ['user_first_name'] 		= $user_details['firstName'];
			if( $result['lastName'] !== '')
			{
				$_SESSION ['user_last_name'] 	= $user_details['lastName'];
			}
			else
			{
				$_SESSION ['user_last_name'] 	= '';
			}
			$_SESSION ['user_role'] 			= $user_details['roleId'];

		}
		else{
			$_SESSION ['user_login_error']			= 1;
		}


	}

	function commonDatabaseAction( $query )
	{
		$result = mysql_query( $query );

		if( !mysql_error() )
		{
			return $result;
		}
		else
		{
			echo mysql_error();
		}
	}



}

