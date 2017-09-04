<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($product) ? 'Edit': 'Add'); ?> Product
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
            <li class="active"><?php echo (isset($product) ? 'Edit': 'Add'); ?> Product</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-offset-3 col-md-6">
        
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Product Form
                    </div>
                </div>

                <div class="portlet-body form">
                    <form role="form" class="validate-form" method="post">
                        <div class="form-body">
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                            </div>

                            <?php if(isset($error)) { ?>
                                <div class="alert alert-danger">
                                    <i class="fa fa-times"></i>
                                    <?php echo $error; ?>
                                </div>
                            <?php } ?>

                            <?php if(isset($product['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>" />
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Name:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="name"
                                        value="<?php echo isset($product['name']) ? $product['name'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="dir_path">Dir Path:</label>
                                        <input type="text" class="form-control" name="dir_path"
                                        value="<?php echo isset($product['dir_path']) ? $product['dir_path'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="add-product-checked-by-error">
                                        <label class="control-label" for="checked_by">Checked By:
                                        <span class="required">*</span></label>
                                        
                                        <select name="checked_by" class="form-control required select2me"
                                        data-placeholder="Select User" data-error-container="#add-product-checked-by-error">
                                            <option value=""></option>
                                            
                                            <?php $sel_checked_by = isset($product['checked_by']) ? $product['checked_by'] : ''; ?>
                                            <?php foreach($users as $user) { ?>
                                                <option value="<?php echo $user['id']; ?>" 
                                                <?php if($sel_checked_by == $user['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $user['first_name'].' '.$user['last_name'].' ( '.$user['user_type'].' )'; ?>
                                                </option>
                                            <?php } ?>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="add-product-approved-by-error">
                                        <label class="control-label" for="approved_by">Approved By:
                                        <span class="required">*</span></label>
                                        
                                        <select name="approved_by" class="form-control required select2me"
                                        data-placeholder="Select User" data-error-container="#add-product-approved-by-error">
                                            <option value=""></option>
                                            
                                            <?php $sel_approved_by = isset($product['approved_by']) ? $product['approved_by'] : ''; ?>
                                            <?php foreach($users as $user) { ?>
                                                <option value="<?php echo $user['id']; ?>" 
                                                <?php if($sel_approved_by == $user['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $user['first_name'].' '.$user['last_name'].' ( '.$user['user_type'].' )'; ?>
                                                </option>
                                            <?php } ?>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                                
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'products'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>