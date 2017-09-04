<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Model Suffixs
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
            <li class="active">Manage Model Suffixs</li>
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
                            Model Suffixs
                        <?php } else { ?>
                            Model Suffixs for product - <?php echo $product['name'];?>
                        <?php } ?>
                    </div>
                    <div class="actions">
                        <a target="_blank" class="button normals btn-circle" href="<?php echo base_url()."assets/excel_formats/Model_Suffix.xlsx"; ?>">
                            <i class="fa fa-download"></i> Format
                        </a>
                        <a class="button normals btn-circle" href="<?php echo base_url()."products/upload_model_suffixs/".$product['id']; ?>">
                            <i class="fa fa-plus"></i> Upload Model Suffix
                        </a>
                        <a class="button normals btn-circle" href="<?php echo base_url()."products/add_model_suffix/".$product['id']; ?>">
                            <i class="fa fa-plus"></i> Add Model Suffix
                        </a>
                        <a class="button normals btn-circle" href="<?php echo base_url()."products/download_model_suffixs"; ?>">
                            <i class="fa fa-download"></i> Download
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($model_suffixs)) { ?>
                        <p class="text-center">No Model Suffix exists yet.</p>
                    <?php } else { ?>
                    <form role="form" class="validate-form" method="post" action="<?php echo base_url().'products/delete_model_suffixs_multi'; ?>">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
                        <table class="table table-hover table-light table-checkable" id="checkable-data-table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="group-checkable" data-set="#checkable-data-table .checkboxes" /> 
                                    </th>
                                    <th>Model.Suffix</th>
                                    <th>Tool</th>
                                    <th class="no_sort" style="width:100px;">
                                        <button class="btn btn-xs btn-outline sbold red-thunderbird" type="button" id="delete-multiple">
                                            <i class="fa fa-trash-o"></i> Delete Selected
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($model_suffixs as $model_suffix) { ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="model_suffixs[]" class="checkboxes" value="<?php echo $model_suffix['id']; ?>" /> 
                                        </td>
                                        <td><?php echo $model_suffix['model_suffix']; ?></td>
                                        <td><?php echo $model_suffix['tool']; ?></td>
                                        <td nowrap>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."products/add_model_suffix/".$product['id'].'/'.$model_suffix['id'];?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <a class="btn btn-xs btn-outline sbold red-thunderbird" data-confirm="Are you sure you want to this Model.Suffix?"
                                                href="<?php echo base_url()."products/delete_model_suffix/".$product['id'].'/'.$model_suffix['id'];?>">
                                                <i class="fa fa-trash-o"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </form>
                    <?php } ?>
                    
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>