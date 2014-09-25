<?php
/**
 * @project Bridge shoppingcart
 * User payment history page template
 */

?>
<div class="row">
    <div class="col-lg-12">
        <h1>Payment History <small></small></h1>

        <?php 
        if ($transactions)
        { 
            ?>
            <div class="table-responsive" id="category-list">
                <table class="table table-bordered table-hover table-striped tablesorter">
                    <thead>
                        <tr>
                            <th>Transaction ID <i class="fa fa-sort"></i></th>
                            <th>Price <i class="fa fa-sort"></i></th>
                            <th>Date <i class="fa fa-sort"></i></th>
                            <th>Status <i class="fa"></i></th>
                            <th>Expiry <i class="fa"></i></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($transactions as $payment)
                        {
                        ?>
                        <tr>
                            <td><?php echo $payment['transaction_id']; ?></td>
                            <td><?php echo '$ '. $payment['total_price']; ?></td>
                            <td><?php echo date("d M Y", strtotime($payment['date_time'])); ?></td>
                            <td><?php echo $payment['payment_status']; ?></td>
                            <td><?php echo date("d M Y", strtotime($payment['expires_on'])); ?></td>
                            <td>
                                <input type="button" name="view_prod" class="btn btn-primary" value="View Products" onclick="javascript:displayDetails('<?php echo $payment['transaction_id']; ?>')">
                                <?php
                                if( $payment['expires_on'] > date("Y-m-d h:i:s")){
//                                ?>
                                <input type="button" name="download_prod" class="btn btn-primary" value="Download" onclick="javascript:window.location.href='<?php echo get_base_url(). 'index.php?page=downloader&token=' . $payment['token'] ?>'">
                                <?php } ?>
                            </td>
                        </tr>
                        
                        <tr class="prod_details" id="<?php echo $payment['transaction_id']; ?>" style="display: none;">
                            <td colspan="6">
                                <div>
                                    <table class="table table-bordered table-hover table-striped tablesorter">
                                        <!--<thead>-->
                                            <tr>
                                                <th>Image</th>
                                                <th>Name </th>
                                                <th>Description </th>
                                                <th>Price </th>
                                            </tr>
                                        <!--</thead>-->
                                        <!--<tbody>-->
                                            
                                        
                        <?php
                        foreach ($payment['products'] as $row)
                        {
                            ?>
                                    <tr>
                                        <td><img src="uploads/<?php echo $row['id'] . '_' . $row['image_path']; ?>" width="100" height="100" /></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['description']; ?></td>
                                        <td><?php echo '$ '. $row['price']; ?></td>
                                    </tr>
                                
                                    <?php
                                }
                                ?>
                                    <!--</tbody>-->
                                    </table>
                                    </div>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<!-- Page Specific Plugins -->
<script src="js/tablesorter/jquery.tablesorter.js"></script>
<script src="js/tablesorter/tables.js"></script>
<script>
    function displayDetails(id){
        
        if($('#'+id).css('display') == 'none'){
            $('#'+id).css({'display':'table-row'});
        }
        else{
            $('#'+id).css({'display':'none'});
        }
    }
</script>