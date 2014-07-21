<?php
session_start();

include_once 'controller/user-controller.php';

if( isset( $_SESSION['user_id'] ) )
{
	unset( $_SESSION['user_id'] );
	unset( $_SESSION['user_first_name'] );
	unset( $_SESSION['user_last_name'] );
	unset( $_SESSION['user_role'] );
	unset( $_SESSION['user_login_error'] );

	$applicationController = new UserController();
	$applicationController -> pageRedirect( 'index.php' );

 }
?>