<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Upload Monthly Production Plan
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Upload Monthly Production Plan</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-offset-3 col-md-6">
        
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Upload Monthly Production Plan Form
                    </div>
                    <div class="actions">
                        <a target="_blank" class="button normals btn-circle" href="<?php echo base_url()."assets/excel_formats/Production_Plan_Monthly.xlsx"; ?>">
                            <i class="fa fa-download"></i> Format
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
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="production_plan_excel" class="control-label">
                                            Upload Production Plan Excel:                                            
                                        </label>
                                        <input type="file" name="production_plan_excel" class="required">
                                    </div>
                                </div>
                            </div>
                                
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" name="plan" value="true" type="submit">Submit</button>
                            <a href="<?php echo base_url().'inspections'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>