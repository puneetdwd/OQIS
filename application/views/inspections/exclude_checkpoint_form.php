<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($excluded_checkpoint) ? 'Edit': 'Add'); ?> Exclude Checkpoints
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."inspections/excluded_checkpoints"; ?>">
                    Manage Inspections
                </a>
            </li>
            <li class="active"><?php echo (isset($excluded_checkpoint) ? 'Edit': 'Add'); ?> Exclude Checkpoints</li>
        </ol>
        
    </div>
    
    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered excluded_checkpoint-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Exclude Checkpoint Form
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

                            <?php if(isset($excluded_checkpoint['id'])) { ?>
                                <input type="hidden" name="id" id="existing-id" value="<?php echo $excluded_checkpoint['id']; ?>" />
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="exclude-checkpoint-inspection-error">
                                        <label class="control-label">Select Inspection:
                                                <span class="required"> * </span>
                                        </label>
                                                
                                        <select name="inspection_id" id="exclude-form-insp-sel" class="form-control required select2me"
                                            data-placeholder="Select Inspection" data-error-container="#exclude-checkpoint-inspection-error">
                                            <option value=""></option>
                                            <?php $sel_inspection = (!empty($excluded_checkpoint['inspection_id']) ? $excluded_checkpoint['inspection_id'] : ''); ?>
                                            <?php foreach($inspections as $inspection) { ?>
                                                <option value="<?php echo $inspection['id']; ?>"
                                                <?php if($sel_inspection == $inspection['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $inspection['name']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    
                                    <div class="form-group" id="exclude-checkpoint-sel-model-error">
                                        <label class="control-label">Select Model.Suffix:
                                            <span class="required"> * </span>
                                        </label>
                                                
                                        <select name="model" class="form-control required select2me"
                                            data-placeholder="Select Model.Suffix" data-error-container="#exclude-checkpoint-sel-model-error">
                                            <option></option>
                                            <?php $sel_model_suffix = (!empty($excluded_checkpoint['model']) ? $excluded_checkpoint['model'] : ''); ?>
                                            <?php foreach($model_suffixs as $model_suffix) { ?>
                                                <option value="<?php echo $model_suffix['model_suffix']; ?>" <?php if($model_suffix['model_suffix'] == $sel_model_suffix) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $model_suffix['model_suffix']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!--
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="checkpoints_nos">Checkpoints Nos:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="checkpoints_nos"
                                        value="<?php echo isset($excluded_checkpoint['checkpoints_nos']) ? $excluded_checkpoint['checkpoints_nos'] : ''; ?>">
                                        <span class="help-block">You can add multiple checkpoint comma separated.</span>
                                    </div>
                                </div>
                                
                            </div>
                            -->
                            
                            <div class="row" id="exclude-checkpoints-section">
                                <?php if(isset($excluded_checkpoint['checkpoints_nos'])) {
                                    $v = array(
                                        'inspection' => $excluded_checkpoint,
                                        'checkpoints' => $checkpoints
                                    );
                                    $this->view('inspections/excluded_checkpoints_ajax', $v);
                                } ?>
                            </div>
                            
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'inspections/excluded_checkpoints'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>