<?php
include_once 'controller/database-controller.php';
class UserController
{
	public $protocal_array 	= '';
	public $host 				= '';
	public $protocal 			= '';
	public $request_uri_array 	= '';
	public $request_uri 		= '';
	public $database			= '';

	function __construct()
	{
		$this->database 	= new DataBaseController();
	}

	function userLogin( $post ){
// 		echo '<pre>';
// 		print_r($post);
// 		die;
		$username = addslashes( $post['username'] );
		$password = addslashes( $post['password'] );

		if( $username != '' && $password != '' )
		{
			$this->database->userLogin( $username, $password);
		}
		else
		{
			$_SESSION ['user_login_error']	= 1;
		}


	}

	function pageRedirect( $page )
	{
		$this->protocal_array 		= explode( '/', $_SERVER['SERVER_PROTOCOL'] );
		$this->host 				= $_SERVER['HTTP_HOST'].'/';
		$this->protocal 			= strtolower( $this->protocal_array[0] ).'://';
		$this->request_uri_array 	= explode( '/', $_SERVER['REQUEST_URI'] );
		$this->request_uri 			= $this->request_uri_array[1].'/';

		header('location:'.$this->protocal.$this->host.$this->request_uri.$page);
	}
}