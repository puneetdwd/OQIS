<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Products
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Manage Products</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <?php if($this->session->flashdata('error')) {?>
                <div class="alert alert-danger">
                   <i class="fa fa-check"></i>
                   <?php echo $this->session->flashdata('error');?>
                </div>
            <?php } else if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                    <i class="fa fa-times"></i>
                   <?php echo $this->session->flashdata('success');?>
                </div>
            <?php } ?>

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Products
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."products/add_product"; ?>">
                            <i class="fa fa-plus"></i> Add New Products
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($products)) { ?>
                        <p class="text-center">No Products.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Directory Path</th>
                                    <th>Checked By</th>
                                    <th>Approved By</th>
                                    <th class="no_sort" style="width:100px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($products as $product) { ?>
                                    <tr>
                                        <td><?php echo $product['name']; ?></td>
                                        <td><?php echo $product['dir_path']; ?></td>
                                        <td><?php echo $product['checked_by'] ? $product['checked_by'].' ('.$product['checked_by_type'].')' : ''; ?></td>
                                        <td><?php echo $product['approved_by'] ? $product['approved_by'].' ('.$product['approved_by_type'].')' : ''; ?></td>
                                        <td nowrap>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."products/add_product/".$product['id'];?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."products/lines/".$product['id'];?>">
                                                <i class="fa fa-eye"></i> Lines
                                            </a>
                                            
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."products/model_suffixs/".$product['id'];?>">
                                                <i class="fa fa-eye"></i> Model Suffixs
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>