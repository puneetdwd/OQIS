<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($reference) ? 'Edit': 'Add'); ?> Reference Link
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."references"; ?>">
                    Manage Reference Links
                </a>
            </li>
            <li class="active"><?php echo (isset($reference) ? 'Edit': 'Add'); ?> Reference Link</li>
        </ol>
        
    </div>
<?php
	/* $sel_model_suffix = (!empty($reference['model_suffix']) ? $reference['model_suffix'] : ''); 
	$sel_model_suffix = explode(',',$sel_model_suffix);
	print_r($sel_model_suffix);											
	foreach($model_suffixs as $model_suffix) 
	{
		if(in_array($model_suffix['model'], $sel_model_suffix))
			echo '12';
	}		
	exit; */										
?>
    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered reference-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Reference Link Form
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

                            <?php if(isset($reference['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $reference['id']; ?>" />
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Reference Name:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="name"
                                        value="<?php echo isset($reference['name']) ? $reference['name'] : ''; ?>">
                                        <label class="checkbox-inline" style="color:#222;padding-left:0px;">
                                            <input type="checkbox" id="inspection-full-auto" name="mandatory" value="1"
                                            <?php if(!empty($reference['mandatory'])) { ?>checked="checked"<?php } ?>
                                            > Mandatory
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group" id="add-reference-inspection-error">
                                        <label class="control-label">Select Inspection:
                                            <span class="required"> * </span>
                                        </label>
                                                
                                        <select name="inspection_id" class="form-control required select2me"
                                            data-placeholder="Select Inspection" data-error-container="#add-reference-inspection-error">
                                            <option value="All">All</option>
                                            <?php $sel_inspection = (!empty($reference['inspection_id']) ? $reference['inspection_id'] : ''); ?>
                                            <?php foreach($inspections as $inspection) { ?>
                                                <option value="<?php echo $inspection['id']; ?>"
                                                <?php if($sel_inspection == $inspection['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $inspection['name']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="add-reference-sel-tool-error">
                                        <label class="control-label">Select Tool:
                                        <span class="required"> * </span></label>
                                                
                                        <select name="tool" id="tool-wise-model-sel" class="required form-control select2me"
                                            data-placeholder="Select Tool" data-error-container="#add-reference-sel-tool-error">
                                            <option value="All">All</option>
                                            <?php $sel_tool = (!empty($reference['tool']) ? $reference['tool'] : ''); ?>
                                            <?php foreach($tools as $tool) { ?>
                                                <option value="<?php echo $tool['tool']; ?>" <?php if($tool['tool'] == $sel_tool) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $tool['tool']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group" id="add-reference-sel-model-error">
                                        <label class="control-label">Select Model.Suffix:
                                        <span class="required"> * </span></label>
                                                
                                        <select  multiple  name="model_suffix[]" id="model-sel-by-tool" class="required form-control select2me"
                                            data-placeholder="Select Model.Suffix" data-error-container="#add-reference-sel-model-error">
                                            <option value="All">All</option>
                                            <?php 
												$sel_model_suffix = (!empty($reference['model_suffix']) ? $reference['model_suffix'] : ''); 
												$sel_model_suffix = explode(',',$sel_model_suffix);
												
											?>
                                            <?php /* foreach($model_suffixs as $model_suffix) { ?>
                                                <option value="<?php echo $model_suffix['model']; ?>" <?php if($model_suffix['model'] == $sel_model_suffix) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $model_suffix['model']; ?>
                                                </option>
                                            <?php } */ ?>  
											<?php foreach($model_suffixs as $model_suffix) { ?>
                                                <option value="<?php echo $model_suffix['model']; ?>" <?php if(in_array($model_suffix['model'], $sel_model_suffix)) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $model_suffix['model']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="reference_url">URL:</label>
                                        <input type="text" class=" form-control" name="reference_url"
                                        value="<?php echo isset($reference['reference_url']) ? $reference['reference_url'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="reference_file" class="control-label">Upload Reference File:
                                            <?php if(!empty($reference['reference_file'])) { ?>
                                                ( <a class="btn-link" target="_blank" href="<?php echo base_url().$reference['reference_file']; ?>">
                                                    <i class="fa fa-eye"></i> View File
                                                </a> )
                                            <?php } ?>
                                        </label>
                                        <input type="file" name="reference_file">
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