<?php
/**
 * @project Bridge shoppingcart
 * Add new product
 */
?>
<div class="row">
    <div class="col-lg-12">
        <h1>Product <small></small></h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Manage product</li>
        </ol>

        <div class="table-responsive" id="product-list">
            <div class="col-lg-6">
                <form role="form" id="new-product-form" action="post-handler.php?action=PRODUCT" method="POST" enctype="multipart/form-data">
                    <?php
                    if ( isset( $product_info ) )
					{
					?>
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="hidden" name="product-id" value="<?php echo $product_info['id']; ?>" />
                            <input class="form-control" id="product-name" name="product-name" value="<?php echo $product_info['name']; ?>">
                        </div>
                        <div class="form-group">
                            <label>Description </label>
                            <textarea class="form-control" rows="3" name="product-desc"><?php echo $product_info['description']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input class="form-control" id="product-price" name="product-price" placeholder="Enter product price" value="<?php echo $product_info['price']; ?>"/>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" name="product-cat">
                                <?php
                                foreach ( $categories as $cat )
								{
									if ( $product_info['catId'] == $cat['id'] )
									{
									?>
                                        <option value="<?php echo $cat['id']; ?>" selected="selected"><?php echo $cat['name']; ?></option>
                                    <?php
									}
									else
									{
									?>
                                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                    <?php
									}
								}
								?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Download Link Validity</label>
                            <input class="form-control" id="product-validity" name="product-validity" placeholder="" value="<?php echo $product_info['validity']; ?>"/>
                        </div>
                        <div class="form-group">
                            <label>Download Link</label>
                            <input class="form-control" id="product-link" name="product-link" placeholder="Enter product link" value="<?php echo $product_info['downloadLink']; ?>"/>
                        </div>
                        <div class="form-group">
                            <img src="uploads/<?php echo $product_info['imagePath']; ?>" width="100" height="100" />
                            <input type="hidden" name="hid-product-image" value="<?php echo $product_info['imagePath']; ?>">
                            <label>Image</label>
                            <input type="file" id="product-image" name="product-image">
                        </div>

                    <?php
					}
					else
					{
					?>
                        <div class="form-group">
                            <label>Product Name</label>
                            <input class="form-control" id="product-name" name="product-name" placeholder="Enter product name" />
                        </div>
                        <div class="form-group">
                            <label>Description </label>
                            <textarea class="form-control" rows="3" id="product-desc" name="product-desc"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input class="form-control" id="product-price" name="product-price" placeholder="Enter product price" />
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" name="product-cat">
                                <?php
                                foreach ( $categories as $cat )
								{
								?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                <?php
								}
								?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Download Link Validity</label>
                            <input class="form-control" id="product-validity" name="product-validity" placeholder="" />
                        </div>
                        <div class="form-group">
                            <label>Download Link</label>
                            <input class="form-control" id="product-link" name="product-link" placeholder="Enter product link" />
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" id="product-image" name="product-image">
                        </div>

                    <?php
					}
					?>
                    <div class="form-group">
                        <label>Status</label>
                        <?php
                        if ( isset( $product_info ) )
                        {
                        ?>

                            <?php
                            if ( $product_info['status'] == 1 )
							{
							?>
                                <label class="radio-inline">
                                    <input name="product-status" id="product-status-enabled" value="1" type="radio" checked=""> Enabled
                                </label>
                                <label class="radio-inline">
                                    <input name="product-status" id="product-status-disabled" value="0" type="radio"> Disabled
                                </label>
                            <?php
							}
							else
							{
							?>
                                <label class="radio-inline">
                                    <input name="product-status" id="product-status-enabled" value="1" type="radio" > Enabled
                                </label>
                                <label class="radio-inline">
                                    <input name="product-status" id="product-status-disabled" value="0" type="radio" checked=""> Disabled
                                </label>
                            <?php
							}
							?>

                        <?php
                        }
                        else
						{
						?>
                            <label class="radio-inline">
                                <input name="product-status" id="product-status-enabled" value="1" type="radio" checked=""> Enabled
                            </label>
                            <label class="radio-inline">
                                <input name="product-status" id="product-status-disabled" value="0" type="radio"> Disabled
                            </label>
                        <?php
						}
						?>
                    </div>

                    <button type="submit" class="btn btn-primary" id="new-product-submit">Submit</button>
                    <button type="reset" class="btn btn-default" id="new-product-reset">Reset</button>

                </form>
            </div>
        </div>

    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $('#new-product-form').submit(function(e) {
            flag = true;
            productName = $('#product-name').val();
            $('#product-name').removeAttr('style');
            $('#product-desc').removeAttr('style');
            $('#product-price').removeAttr('style');
            $('#product-validity').removeAttr('style');

            flag1 = validate('name', 'Product name is required', 'required');
            flag2 = validate('desc', 'Description is required', 'required');
            flag3 = validate('price', 'Price should be a numeric field', 'numeric');
            flag4 = validate('validity', 'Validity should be a numeric field', 'numeric');

            flag = flag1 && flag2 && flag3 && flag4;

            if (flag) {
                return;
            } else {
                e.preventDefault();
            }
        });
    });

    function validate(field, message, filter)
    {
        valid = false;
        if(filter === 'required') {
            valid = ($('#product-' + field).val() != '') ? true : false;
        } else if(filter === 'numeric') {
            valid = $.isNumeric($('#product-' + field).val());
        } else {
            valid = true;
        }

        if (!valid) {
            $('#product-' + field).val('');
            $('#product-' + field).attr('placeholder', message);
            $('#product-' + field).css('border-color', 'red');
            return false;
        } else {
            return true;
        }
    }


</script>

