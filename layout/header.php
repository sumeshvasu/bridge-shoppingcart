<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<title>Bridge-Store-Dashboard</title>

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.css" rel="stylesheet">

<!-- Add custom CSS here -->
<link rel="stylesheet" href="css/sb-admin.css">
<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/custom.css">
<link rel="stylesheet" href="css/morris-0.4.3.min.css">
<link rel="stylesheet" href="css/jquery-ui.css">

<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/admin.js"></script>


</head>
<body>
	<script>
		$(function() {
			$( "#login-error-banner" ).hide();
			var pull 		= $('#pull');
				menu 		= $('nav ul');
				menuHeight	= menu.height();

			$('#pull').on('click', function(e) {
				e.preventDefault();
				menu.slideToggle();
			});

			$(window).resize(function(){
        		var w = $(window).width();
        		if(w > 320 && menu.is(':hidden')) {
        			menu.removeAttr('style');
        		}
    		});
    		<?php
    		if( isset($_SESSION ['user_login_error']) && $_SESSION ['user_login_error'] == 1 )
    		{
			?>
				$("#login-banner").hide();
				$( "#login-error-banner" ).show();
				$( "#login-error-banner" ).effect( "shake" );
				setTimeout(function() { $("#login-error-banner").hide('blind', {}, 500); $("#login-banner").show('blind', {}, 500)     }, 2500);
			<?php
			}
			?>
		});

	</script>

	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-info">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-10">
								<i class="fa fa-5x">Bridge Store</i>
							</div>

						</div>
					</div>
					<?php
					if ( isset( $_SESSION ['user_id'] ) && ($_SESSION ['user_id'] != '' ) )
					{
					?>
					<div class="panel-footer announcement-bottom">
						<div class="row ">
							<nav class="clearfix">
								<ul class="clearfix col-lg-12">
									<li><a href="./index.php">Home</a></li>
									<li><a href="./index.php?page=categories">Categories</a></li>
									<li><a href="./index.php?page=products">Products</a></li>
									<li><a href="./index.php?page=purchases">Purchases</a></li>
									<li><a href="./index.php?page=customers">Customers</a></li>
									<li id="user-link" style="float:right;"><a href="./logout.php">Logout</a></li>
									<li style="float:right;">
										<?php
											echo $_SESSION['user_first_name'].' '.$_SESSION['user_last_name']
										?>
									</li>
								</ul>

								<span id="pull">&nbsp;</span>
							</nav>
						</div>
					</div>
					<?php
					}
					?>

				</div>
			</div>
		</div>

