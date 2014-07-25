<?php
/**
 * @project Bridge shoppingcart
 * Admin product page template
 */
$per_page 		= 4;
$page_deatils 	= $paginator->setPagination($per_page, $products);
$page 			= $page_deatils['page'];
$show_page 		= $page_deatils['showPage'];
$total_pages 	= $page_deatils['totalPages'];
$start 			= $page_deatils['start'];
$end 			= $page_deatils['end'];

$total_results 	= count( $products );

?>
<div class="row">
    <div class="col-lg-12">
        <h1>Products <small></small></h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-dashboard"></i> Manage products</li>
        </ol>
        <div class="alert alert-success alert-dismissable">
            <a href="index.php?page=new-product" class="btn btn-primary" id="product-new">New product +</a>
        </div>

        <div>
            <ul class="pager">
                <?php
                $reload = $_SERVER['PHP_SELF'] . "?page=products&amp;tpages=" . $total_pages;
                if ($total_pages > 1)
				{
                    echo $paginator->paginate($reload, $show_page, $total_pages);
                }
                ?>
            </ul>
        </div>

        <?php
        if ($products)
		{
		?>
            <div class="table-responsive" id="product-list">
                <table class="table table-bordered table-hover table-striped tablesorter">
                    <thead>
                        <tr>
                            <th>Name <i class="fa fa-sort"></i></th>
                            <th>Description <i class="fa fa-sort"></i></th>
                            <th>Price <i class="fa fa-sort"></i></th>
                            <th>Validity <i class="fa fa-sort"></i></th>
                            <th>Image <i class="fa fa-sort"></i></th>
                            <th>Status <i class="fa fa-sort"></i></th>
                            <th>Edit <i class="fa"></i></th>
                            <th>Remove <i class="fa"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = $start; $i < $end; $i++)
						{

                            if ($i == $total_results)
							{
                                break;
                            }
                            $row = $products[$i];
                            ?>
                            <tr>
                                <td><?php echo $row['name'] ?></td>
                                <td><?php echo $row['description'] ?></td>
                                <td><?php echo $row['price'] ?></td>
                                <td><?php echo $row['validity'] ?></td>
                                <td><img src="uploads/<?php echo $row['imagePath'] ?>" width="100" height="100"></td>
                                <td><?php echo ($row['status'] == 1) ? 'Enabled' : 'Disabled'; ?></td>
                                <td><a class="btn btn-success"  id="product-edit" href="index.php?page=edit-product&id=<?php echo $row['id']; ?>">Edit</a></td>
                                <td><a class="btn btn-danger" id="product-delete" href="index.php?page=delete-product&id=<?php echo $row['id']; ?>">Delete</a></td>
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