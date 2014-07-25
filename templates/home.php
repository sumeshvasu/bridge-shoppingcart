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
                <div class="col-lg-4" style="display: inline">
                    <?php for($i = 1;$i <= 10; $i++) { ?>
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">Product A</h3>                        
                        </div>
                        <div class="panel-body">
                            <div style="float:left">
                                <img src="uploads/Ubuntu-Unity-Logo-150x1502.png" width="100" height="100" />
                            </div>
                            <div style="float: right">

                                <div class="col-lg-100">
                                    <div>
                                        Sample product description text appears here...
                                    </div>
                                </div>
                                <p>$100</p>
                                <button type="button" class="btn btn-primary">Buy</button>

                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>