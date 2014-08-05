<?php
/**
 * Buyitnow view page
 * @param int
 */
include_once ("./paypal/paypal-config.php");
$base_url = $application->redirect ();
?>

<div class="col-lg-61">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h2 class="panel-title"><?php echo $productInfo['name']; ?></h2>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-3">
					<img src="uploads/<?php echo $productInfo['id'] . '_' . $productInfo['imagePath']; ?>" width="100" height="100" />
				</div>
				<div class="col-lg-6">
					<div>
                         <?php echo $productInfo['description']; ?>
                    </div>
					<p><?php echo $productInfo['price']; ?></p>
					<div class="row">
						<form method="post" action="<?php echo $base_url;?>paypal/process.php">
							<input type="hidden" name="itemname" value="<?php echo $productInfo['name']; ?>" />
							<input type="hidden" name="itemnumber" value="<?php echo $productInfo['id']; ?>" />
							<input type="hidden" name="itemdesc" value="<?php echo $productInfo['description']; ?>" />
							<input type="hidden" name="itemprice" value="<?php echo $productInfo['price']; ?>" />
							<input type="hidden" name="itemQty" value="1" />
							<button  type="submit" name="submitbutt" class="btn btn-primary">Proceed</button>
						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>




