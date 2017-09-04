<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($checkpoint_config) ? 'Edit': 'Add'); ?> Mandatory Checkpoint config
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."references/checkpoint_configs"; ?>">
                    Manage Mandatory Checkpoint config
                </a>
            </li>
            <li class="active"><?php echo (isset($checkpoint_config) ? 'Edit': 'Add'); ?> Mandatory Checkpoint config</li>
        </ol>
        
    </div>
    
    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered checkpoint_config-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Mandatory Checkpoint config Form
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

                            <?php if(isset($checkpoint_config['id'])) { ?>
                                <input type="hidden" name="id" id="existing-id" value="<?php echo $checkpoint_config['id']; ?>" />
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="exclude-checkpoint-sel-link-error">
                                        <label class="control-label">Select Reference Link:
                                            <span class="required"> * </span>
                                        </label>
                                                
                                        <select name="reference_link" class="form-control required select2me"
                                            data-placeholder="Select Reference Link" data-error-container="#exclude-checkpoint-sel-link-error">
                                            <option></option>
                                            <?php $sel_link = (!empty($checkpoint_config['reference_link']) ? $checkpoint_config['reference_link'] : ''); ?>
                                            <?php foreach($links as $link) { ?>
                                                <option value="<?php echo $link['name']; ?>" <?php if($link['name'] == $sel_link) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $link['name']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group" id="exclude-checkpoint-inspection-error">
                                        <label class="control-label">Select Inspection:
                                                <span class="required"> * </span>
                                        </label>
                                                
                                        <select name="inspection_id" id="ref-link-checkpoint-config-insp-sel" class="form-control required select2me"
                                            data-placeholder="Select Inspection" data-error-container="#exclude-checkpoint-inspection-error">
                                            <option value=""></option>
                                            <?php $sel_inspection = (!empty($checkpoint_config['inspection_id']) ? $checkpoint_config['inspection_id'] : ''); ?>
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
                            
                            <div class="row" id="ref-link-checkpoints-section">
                                <?php if(isset($checkpoint_config['checkpoints_nos'])) {
                                    $v = array(
                                        'inspection' => $checkpoint_config,
                                        'checkpoints' => $checkpoints
                                    );
                                    $this->view('references/checkpoints_config_ajax', $v);
                                } ?>
                            </div>
                            
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'references/checkpoint_configs'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>