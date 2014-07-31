<?php
/**
 * Buyitnow view page
 * @param int 
 */
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
                            <?php if (isset($productInfo) && !empty($productInfo))
                            {
                                ?>
                                <p><?php echo $productInfo['name']; ?></p>
                                <p><?php echo $productInfo['description']; ?></p>
                                <p><?php echo $productInfo['price']; ?></p>
                                <p><?php echo $productInfo['validity']; ?></p>
<?php } ?>
                            <script src="js/paypal-button.min.js?merchant=sobin87-facilitator@gmail.com"
                                    data-button="buynow"
                                    data-name="My product"
                                    data-amount="1.00"
                                    async
                            ></script>
                        </div>               
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

