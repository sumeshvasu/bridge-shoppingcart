<?php
/**
 * @project Bridge shoppingcart
 * Home page template
 *
 */
?>

<!--<div class="row">
    <div class="col-lg-12">
        <h1>User Front End <small></small></h1>

        <div class="alert alert-success alert-dismissable">
            This is the user home page
        </div>
    </div>-->

<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Products <?php echo (isset($cat_name) && $cat_name != '') ? '>>' . $cat_name : ''; ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2">
                    <div class="bs-example">
                        <ul class="list-group">
                            <li class="list-group-item">Categories</li>
                            <?php
                            if (isset($categories) && count($categories) > 0)
                            {
                                foreach ($categories as $category)
                                {
                                    ?>
                                    <li class="list-group-item">                                            
                                        <span class="badge"><?php echo $category['no_of_products']; ?></span>
                                        <a href="index.php?page=products-view&catId=<?php echo $category['id']; ?>"><?php echo $category['name']; ?></a>                                            
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-8" style="display: inline">
                    <div class="row">
                        <?php
                        if (isset($products) && count($products) > 0)
                        {
                            foreach ($products as $product)
                            {
                                ?>
                                <div class="col-lg-6">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"><?php echo $product['name']; ?></h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <img src="uploads/<?php echo $product['id'] . '_' . $product['imagePath']; ?>" width="100" height="100" />
                                                </div>                                                    
                                                <div class="col-lg-6">
                                                    <div>
                                                        <?php echo $product['description']; ?>
                                                    </div>
                                                    <p><?php echo $product['price']; ?></p>
                                                    <div class="row">
                                                        <a href="./index.php?page=buyitnow&productId=<?php echo $product['id']; ?>"><button type="button" class="btn btn-primary">Buy It Now</button></a>
                                                        <a href="./index.php?page=addtocart&productId=<?php echo $product['id']; ?>"><button type="button" class="btn btn-primary">Add to cart</button></a>
                                                    </div>
                                                </div>
                                            </div>                    
                                        </div>
                                    </div>
                                </div>

                                <?php
                            }
                        }
                        ?>
                    </div>               
                </div>
            </div>
        </div>
    </div>
</div>
<!--</div>-->
</div>