<?php
/**
 * @project Bridge shoppingcart
 * Payment response template
 *
 */
?> 
<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Payment: <?php
                if ($payment_status == 'error')
                {
                    echo 'Error Occured';
                }
                else
                {
                    echo 'Success';
                }
                ?>  </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-8" style="display: inline">
                    <div class="row">
                            <?php echo $message; ?>
                        <p>
<?php
echo isset($_SESSION['payment_error_detail']) ? $_SESSION['payment_error_detail'] : 'Empty details';
?>
                        </p>
                    </div>               
                </div>
            </div>
        </div>
    </div>
</div>
<!--</div>-->
</div>