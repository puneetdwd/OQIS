<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($inspection) ? 'Edit': 'Add'); ?> Inspection
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."inspections"; ?>">
                    Manage Inspections
                </a>
            </li>
            <li class="active"><?php echo (isset($inspection) ? 'Edit': 'Add'); ?> Inspection</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered inspection-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Inspection Form
                    </div>
                    <div class="actions">
                        <a target="_blank" class="button normals btn-circle" href="<?php echo base_url()."assets/excel_formats/Checkpoint_2_level.xlsx"; ?>">
                            <i class="fa fa-download"></i> Format Checkpoint 2 Level
                        </a>
                        <a target="_blank" class="button normals btn-circle" href="<?php echo base_url()."assets/excel_formats/Checkpoint_3_level.xlsx"; ?>">
                            <i class="fa fa-download"></i> Format Checkpoint 3 Level
                        </a>
                        <a target="_blank" class="button normals btn-circle" href="<?php echo base_url()."assets/excel_formats/Checkpoint_4_level.xlsx"; ?>">
                            <i class="fa fa-download"></i> Format Checkpoint 4 Level
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

                            <?php $sel_format = (!empty($inspection['insp_type']) ? $inspection['insp_type'] : ''); ?>
                            <?php if(isset($error)) { ?>
                                <div class="alert alert-danger">
                                    <i class="fa fa-times"></i>
                                    <?php echo $error; ?>
                                </div>
                            <?php } ?>

                            <?php if(isset($inspection['id'])) { ?>
                                <input type="hidden" name="id" id="inspection-edit" value="<?php echo $inspection['id']; ?>" />
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="gmes_insp_id">GMES INSP ID:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="gmes_insp_id"
                                        value="<?php echo isset($inspection['gmes_insp_id']) ? $inspection['gmes_insp_id'] : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Inspection Name:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="name"
                                        value="<?php echo isset($inspection['name']) ? $inspection['name'] : ''; ?>">
                                        <div>
                                            <label class="automate-checkboxes checkbox-inline" style="color:#222;padding-left:0px;">
                                                <input type="checkbox" id="inspection-full-auto" name="full_auto" value="1"
                                                <?php if(!empty($inspection['full_auto'])) { ?>checked="checked"<?php } ?>
                                                > Full Automatic
                                            </label>
                                            
                                            <label class="automate-checkboxes checkbox-inline non-full-auto" style="color:#222;padding-left:0px;">
                                                <input type="checkbox" id="inspection-partial-auto" name="automate_result" value="1"
                                                <?php if(!empty($inspection['automate_result'])) { ?>checked="checked"<?php } ?>
                                                > Inspection includes checkpoints with automatic results. 
                                            </label>
                                            
                                            <label class="interval-type checkbox-inline" style="margin-left:0px;color:#222;padding-left:0px;<?php if($sel_format != 'interval') { ?>display:none;<?php } ?>">
                                                <input type="checkbox" id="interval-inspection-attach-report" name="attach_report" value="1"
                                                <?php if(!empty($inspection['attach_report'])) { ?>checked="checked"<?php } ?>
                                                > Attach Report
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if(!$this->product_id) { ?>
                                    <div class="col-md-6">
                                        <div class="form-group" id="add-inspection-product-error">
                                            <label class="control-label" for="product_id">Product:
                                            <span class="required">*</span></label>
                                            
                                            <select name="product_id" class="form-control required select2me"
                                            data-placeholder="Select Product" data-error-container="#add-inspection-product-error">
                                                <option value=""></option>
                                                
                                                <?php $sel_product = (!empty($inspection['product_id']) ? $inspection['product_id'] : ''); ?>
                                                <?php foreach($products as $product) { ?>
                                                    <option value="<?php echo $product['id']; ?>" 
                                                    <?php if($sel_product == $product['id']) { ?> selected="selected" <?php } ?>>
                                                        <?php echo $product['name']; ?>
                                                    </option>
                                                <?php } ?>
                                                
                                            </select>
                                        </div>
                                    </div>
                                <?php } ?>
                                
                                <div class="col-md-6">
                                    <div class="form-group" id="add-inspection-inspection-type-error">
                                        <label class="control-label" for="insp_type">Inspection type:
                                        <span class="required">*</span></label>
                                        
                                        <select id="add-inspection-insp-type" name="insp_type" class="form-control required select2me"
                                        data-placeholder="Select Level" data-error-container="#add-inspection-inspection-type-error">
                                            <option value=""></option>
                                            
                                            <option value="regular" <?php if($sel_format == 'regular') { ?> selected="selected" <?php } ?>>Regular Inspection</option>
                                            <option value="interval" <?php if($sel_format == 'interval') { ?> selected="selected" <?php } ?>>Interval Inspection</option>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!--
                            <div class="row interval-type" <?php if($sel_format == 'regular') { ?> style="display:none;" <?php } ?>>
                                <div class="col-md-6">
                                    <div class="form-group" id="add-inspection-interval-type-error">
                                        <label class="control-label" for="interval_type">Interval type:
                                        <span class="required">*</span></label>
                                        
                                        <select id="add-inspection-interval-type" name="interval_type" class="form-control required select2me"
                                        data-placeholder="Select Type" data-error-container="#add-inspection-interval-type-error">
                                            <option value=""></option>
                                            
                                            <?php $sel_type = (!empty($inspection['interval_type']) ? $inspection['interval_type'] : ''); ?>
                                            <option value="Hourly" <?php if($sel_type == 'Hourly') { ?> selected="selected" <?php } ?>>Hourly </option>
                                            <option value="Daily" <?php if($sel_type == 'Daily') { ?> selected="selected" <?php } ?>>Daily </option>
                                            <option value="Monthly" <?php if($sel_type == 'Monthly') { ?> selected="selected" <?php } ?>>Monthly </option>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            -->
                            
                            <div class="row non-full-auto checkpoint-upload-section">
                                <div class="col-md-6">
                                    <div class="form-group" id="add-inspection-excel-format-error">
                                        <label class="control-label" for="checkpoint_format">Insp Item Level:
                                        <span class="required">*</span></label>
                                        
                                        <select name="checkpoint_format" class="form-control required select2me"
                                        data-placeholder="Select Level" data-error-container="#add-inspection-excel-format-error">
                                            <option value=""></option>
                                            
                                            <?php $sel_format = (!empty($inspection['checkpoint_format']) ? $inspection['checkpoint_format'] : 3); ?>
                                            <option value="2" <?php if($sel_format == 2) { ?> selected="selected" <?php } ?>>2 Level</option>
                                            <option value="3" <?php if($sel_format == 3) { ?> selected="selected" <?php } ?>>3 Level</option>
                                            <option value="4" <?php if($sel_format == 4) { ?> selected="selected" <?php } ?>>4 Level</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="checkpoints_excel" class="control-label">Upload Checkpoints Excel:
                                            <?php if(!empty($inspection['checkpoints_excel'])) { ?>
                                                ( <a class="btn-link" target="_blank" href="<?php echo base_url().'inspections/download_checkpoint/'.$inspection['id']; ?>">
                                                    <i class="fa fa-download"></i> Download Excel
                                                </a> )
                                            <?php } else { ?>
                                                <span class="required">*</span>
                                            <?php } ?>
                                        </label>
                                        <input type="file" name="checkpoints_excel" <?php if(!isset($inspection['id'])) { ?> class="required" <?php } ?>>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row attach-report-div" style="display:none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="inspection_duration">Inspection Time:
                                        <span class="required">*</span></label>
                                        <div class="input-group" style="display:inherit;"> 
                                            <?php $duration = isset($inspection['inspection_duration']) ? $inspection['inspection_duration'] : ''; ?>
                                            <input type="text" class="form-control" name="inspection_duration" style="width:70%;" 
                                            value="<?php echo str_replace(array(' hours', ' days'), '', $duration); ?>">
                                            
                                            <select class="form-control" name="inspection_duration_type" style="width:30%;">
                                                <option value="hours" <?php if(strpos($duration, 'hours')) { ?>selected="selected"<?php } ?>>Hours</option>
                                                <option value="days" <?php if(strpos($duration, 'days')) { ?>selected="selected"<?php } ?>>Days</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row full-auto-section" <?php if(empty($inspection['full_auto'])) { ?>style="display:none;"<?php } ?>>
                                <div class="col-md-6">
                                    <div class="form-group" id="add-inspection-special-case-error">
                                        <label class="control-label" for="automate_case">Case:
                                        <span class="required">*</span></label>
                                        
                                        <select name="automate_case" id="automate-special-case" class="form-control required select2me"
                                        data-placeholder="Select Special Case" data-error-container="#add-inspection-special-case-error">
                                            <option value=""></option>
                                            
                                            <?php $sel_case = (!empty($inspection['automate_case']) ? $inspection['automate_case'] : ''); ?>
                                            <option value="VISION" <?php if($sel_case == 'VISION') { ?> selected="selected" <?php } ?>>VISION</option>
                                            <option value="With Checkpoints" <?php if($sel_case == 'With Checkpoints') { ?> selected="selected" <?php } ?>>With Checkpoints</option>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row automate-settings" <?php if(empty($inspection['full_auto']) || $sel_case != "With Checkpoints") { ?>style="display:none;"<?php } ?>>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="a_start_row">Data Row Starts from:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="a_start_row"
                                        value="<?php echo isset($inspection['a_start_row']) ? $inspection['a_start_row'] : ''; ?>">
                                        
                                        <span class="help-block">Specify the row from where the actual data for the checkpoints will start.</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="a_checkpoint_col">Checkpoint No. Column:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="a_checkpoint_col"
                                        value="<?php echo isset($inspection['a_checkpoint_col']) ? $inspection['a_checkpoint_col'] : ''; ?>">
                                        
                                        <span class="help-block">Specify the col which will have checkpoint nos.</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="a_reading_col">Reading Column:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="a_reading_col"
                                        value="<?php echo isset($inspection['a_reading_col']) ? $inspection['a_reading_col'] : ''; ?>">
                                        
                                        <span class="help-block">Specify the column which will have actual reading, if there are multiple serial no in excel specify all column , separated.</span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="a_judgement_col">Judgement Column:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="a_judgement_col"
                                        value="<?php echo isset($inspection['a_judgement_col']) ? $inspection['a_judgement_col'] : ''; ?>">
                                        
                                        <span class="help-block">Specify the column which will have column wise judgement.</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="insp_text">Inspection Text:</label>
                                        <textarea class="form-control" name="insp_text"><?php echo isset($inspection['insp_text']) ? $inspection['insp_text'] : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'inspections'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>