<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($model_suffix) ? 'Edit': 'Add'); ?> Model Suffix
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
            <li>
                <a href="<?php echo base_url()."products/model_suffixs/".$product['id']; ?>">
                    Manage Model Suffixs
                </a>
            </li>
            <li class="active"><?php echo (isset($model_suffix) ? 'Edit': 'Add'); ?> Model Suffix</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
        
            <div class="portlet light bordered checkpoint-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Model Suffix Form - <?php echo $product['name']; ?>
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

                            <?php if(isset($model_suffix['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $model_suffix['id']; ?>" />
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="model_suffix">Model Suffix:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="model_suffix"
                                        value="<?php echo isset($model_suffix['model_suffix']) ? $model_suffix['model_suffix'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="tool">Tool:</label>
                                        <input type="text" id="tool-master" class="form-control" name="tool"
                                        value="<?php echo isset($model_suffix['tool']) ? $model_suffix['tool'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>
                                
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'products/model_suffixs/'.$product['id']; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>