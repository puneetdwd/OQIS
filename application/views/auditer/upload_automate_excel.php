<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Upload Automate Excel
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Upload Automate Excel</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
        
            <div class="portlet light bordered inspection-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Upload Automate Excel Form
                    </div>
                </div>

                <div class="portlet-body form">
                    <form role="form" class="validate-form" method="post" enctype="multipart/form-data">
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

                            <input type="hidden" name="post_value" value="1" />
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="serial_no">Serial No:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="serial_no"
                                        value="">
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="automate_excel" class="control-label">Upload Model Suffixs:
                                            <span class="required">*</span>
                                        </label>
                                        <input type="file" name="automate_excel" class="required">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="add-inspection-special-case-error">
                                        <label class="control-label" for="type">Type:
                                        <span class="required">*</span></label>
                                        
                                        <select name="type" class="form-control required select2me"
                                        data-placeholder="Select Special Case" data-error-container="#add-inspection-special-case-error">
                                            <option value="READINGS">READINGS</option>
                                            <option value="VISION">VISION</option>
                                            <option value="VISION">With Checkpoints</option>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                                
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url(); ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>