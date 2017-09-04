<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($line) ? 'Edit': 'Add'); ?> Product Line
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
                <a href="<?php echo base_url()."products/lines/".$product['id']; ?>">
                    Manage Product Lines
                </a>
            </li>
            <li class="active"><?php echo (isset($line) ? 'Edit': 'Add'); ?> Product Line</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
        
            <div class="portlet light bordered checkpoint-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Product Line Form - <?php echo $product['name']; ?>
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

                            <?php if(isset($line['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $line['id']; ?>" />
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Name:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="name"
                                        value="<?php echo isset($line['name']) ? $line['name'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>
                                
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'products/lines/'.$product['id']; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>