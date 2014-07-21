<?php
session_start();

error_reporting(E_ALL);

if( !isset( $_SESSION ['user_id'] ) )
{
	$_SESSION ['user_id'] = '';
}

include_once 'controller/user-controller.php';
include_once 'common/common-function.php';

$user		= new UserController();


if( ( isset( $_POST ) ) && ( isset( $_POST['btnLoginSubmit'] ) ) )
{
	$user->userLogin(  bridge_trim_deep( $_POST ) );
}


include_once 'layout/header.php';

if ( isset( $_SESSION ['user_id'] ) && ($_SESSION ['user_id'] == '' ) )
{
	include_once 'login.php';
}
else
{
	//echo $_SERVER['REQUEST_URI'];
	$current_file_name = basename($_SERVER['REQUEST_URI'], ".php");
	$current_file_name = '';

	if( basename($_SERVER['REQUEST_URI'], ".php") == 'index' )
	{
		$current_file_name = 'index';
	}
	else
	{
		$current_file_name = $_GET['page'];
	}





/*
	$indicesServer = array('PHP_SELF',
					'argv',
					'argc',
					'GATEWAY_INTERFACE',
					'SERVER_ADDR',
					'SERVER_NAME',
					'SERVER_SOFTWARE',
					'SERVER_PROTOCOL',
					'REQUEST_METHOD',
					'REQUEST_TIME',
					'REQUEST_TIME_FLOAT',
					'QUERY_STRING',
					'DOCUMENT_ROOT',
					'HTTP_ACCEPT',
					'HTTP_ACCEPT_CHARSET',
					'HTTP_ACCEPT_ENCODING',
					'HTTP_ACCEPT_LANGUAGE',
					'HTTP_CONNECTION',
					'HTTP_HOST',
					'HTTP_REFERER',
					'HTTP_USER_AGENT',
					'HTTPS',
					'REMOTE_ADDR',
					'REMOTE_HOST',
					'REMOTE_PORT',
					'REMOTE_USER',
					'REDIRECT_REMOTE_USER',
					'SCRIPT_FILENAME',
					'SERVER_ADMIN',
					'SERVER_PORT',
					'SERVER_SIGNATURE',
					'PATH_TRANSLATED',
					'SCRIPT_NAME',
					'REQUEST_URI',
					'PHP_AUTH_DIGEST',
					'PHP_AUTH_USER',
					'PHP_AUTH_PW',
					'AUTH_TYPE',
					'PATH_INFO',
					'ORIG_PATH_INFO') ;

			echo '<table cellpadding="10">' ;
			foreach ($indicesServer as $arg) {
			    if (isset($_SERVER[$arg])) {
			        echo '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>' ;
			    }
			    else {
			        echo '<tr><td>'.$arg.'</td><td>-</td></tr>' ;
			    }
			}
			echo '</table>' ;
*/


	selectMenuItem( $current_file_name );

	if( $current_file_name == 'index' )
	{
		include_once 'home.php';
	}
	else if( $current_file_name == 'category' )
	{
		include_once 'categories.php';
	}
	else if( $current_file_name == 'product' )
	{
		include_once 'products.php';
	}
	else if( $current_file_name == 'purchase' )
	{
		include_once 'purchasess.php';
	}
	else if( $current_file_name == 'customer' )
	{
		include_once 'customers.php';
	}

?>
<div class="row">



</div>
<?php
}
include_once 'layout/footer.php';
?>