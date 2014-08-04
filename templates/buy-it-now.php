<?php
/**
 * Buyitnow view page
 * @param int
 */
include_once("./paypal/paypal-config.php");
?>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">Confirm checkout</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-8" style="display: inline">
						<div class="row">
                            <?php
							if (isset ( $productInfo ) && ! empty ( $productInfo ))
							{
							?>
                            <p><?php echo $productInfo['name']; ?></p>
							<p><?php echo $productInfo['description']; ?></p>
							<p><?php echo $productInfo['price']; ?></p>
							<p><?php echo $productInfo['validity']; ?></p>
							<?php
							}
							?>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-lg-6">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $productInfo['name']; ?></h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-3">
					<img
						src="uploads/<?php echo $productInfo['id'] . '_' . $productInfo['imagePath']; ?>"
						width="100" height="100" />
				</div>
				<div class="col-lg-6">
					<div>
                         <?php echo $productInfo['description']; ?>
                    </div>
					<p><?php echo $productInfo['price']; ?></p>
					<div class="row">
						<a href="./index.php?page=checkout&productId=<?php echo $productInfo['id']; ?>&action=process">
							<button type="button" class="btn btn-primary">Proceed</button>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<form method="post" action="http://localhost/bridge-store/paypal/process.php">
	<input type="hidden" name="itemname" value="<?php echo $productInfo['name']; ?>" />
	<input type="hidden" name="itemnumber" value="<?php echo $productInfo['id']; ?>" />
    <input type="hidden" name="itemdesc" value="<?php echo $productInfo['description']; ?>" />
	<input type="hidden" name="itemprice" value="<?php echo $productInfo['price']; ?>" /> Quantity : <select name="itemQty"><option value="1">1</option><option value="2">2</option><option value="3">3</option></select>
    <input class="dw_button" type="submit" name="submitbutt" value="Buy (<?php echo $productInfo['price']; ?><?php echo $PayPalCurrencyCode; ?>)" />
</form>

