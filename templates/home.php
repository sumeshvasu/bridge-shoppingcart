<?php
/**
 * @project Bridge shoppingcart
 * Home page template
 * 
 */
?>

<div class="row">
    <div class="col-lg-12">
        <h1>User Front End <small></small></h1>

        <div class="alert alert-success alert-dismissable">                
            This is the user home page
        </div>
    </div>        

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Products</h3>
            </div>
            <div class="panel-body">                
                <div class="col-lg-12" style="display: inline">
                    <div class="row">
                        <?php foreach ($products as $product) { ?>
                            <div class="col-lg-4">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><?php echo $product['name'];?></h3>                        
                                    </div>
                                    <div class="panel-body">
                                        <div style="float:left">
                                            <img src="uploads/<?php echo $product['imagePath'];?>" width="100" height="100" />
                                        </div>
                                        <div style="float: right">

                                            <div class="col-lg-100">
                                                <div>
                                                    <?php echo $product['description'];?>
                                                </div>
                                            </div>
                                            <p><?php echo $product['price'];?></p>
                                            <button type="button" class="btn btn-primary">Buy</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>