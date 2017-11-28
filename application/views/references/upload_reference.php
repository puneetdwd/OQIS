<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Upload Reference Links
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."products"; ?>">
                    Manage Reference 
                </a>
            </li>
            <li>
                <a href="<?php echo base_url()."references"; ?>">
                    Manage Reference Links
                </a>
            </li>
            <li class="active">Upload Reference Links</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
        
            <div class="portlet light bordered inspection-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Upload Reference Links Form
                    </div>
					<div class="actions">
                        <a class="button normals btn-circle" target="_blank" href="<?php echo base_url()."assets/excel_formats/Reference_Links.xlsx"; ?>">
                            <i class="fa fa-download"></i> Download Sample
                        </a>
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
                                        <label for="references_excel" class="control-label">Upload Reference Links:
                                            <span class="required">*</span>
                                        </label>
                                        <input type="file" name="references_excel" id="references_excel" class="required">
                                    </div>
                                </div>
                            </div>
                                
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'references'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>