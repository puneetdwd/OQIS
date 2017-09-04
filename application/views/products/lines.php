<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Product Lines
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."products"; ?>">
                    Manage Products
                </a>
            </li>
            <li class="active">Manage Product Lines</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-offset-2 col-md-8">

            <?php if($this->session->flashdata('error')) {?>
                <div class="alert alert-danger">
                   <i class="fa fa-times"></i>
                   <?php echo $this->session->flashdata('error');?>
                </div>
            <?php } else if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                    <i class="fa fa-check"></i>
                   <?php echo $this->session->flashdata('success');?>
                </div>
            <?php } ?>

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>
                        <?php if($this->product_id) { ?>
                            Product Lines
                        <?php } else { ?>
                            Lines for product - <?php echo $product['name'];?>
                        <?php } ?>
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."products/add_product_line/".$product['id']; ?>">
                            <i class="fa fa-plus"></i> Add New Line
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($lines)) { ?>
                        <p class="text-center">No Product Line exists yet.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="no_sort" style="width:100px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lines as $line) { ?>
                                    <tr>
                                        <td><?php echo $line['name']; ?></td>
                                        <td nowrap>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."products/add_product_line/".$product['id'].'/'.$line['id'];?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            
                                            <a class="btn btn-xs btn-outline sbold red-thunderbird" data-confirm="Are you sure you want to this line?"
                                                href="<?php echo base_url()."products/delete_product_line/".$product['id'].'/'.$line['id'];?>">
                                                <i class="fa fa-trash-o"></i> Delete
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