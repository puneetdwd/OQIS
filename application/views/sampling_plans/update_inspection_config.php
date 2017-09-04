<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($inspection_config) ? 'Edit': 'Add'); ?> Inspection Configuration
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."sampling/configs"; ?>">
                    Manage Configs
                </a>
            </li>
            <li class="active"><?php echo (isset($inspection_config) ? 'Edit': 'Add'); ?> Inspection Configuration</li>
        </ol>
        
    </div>
    
    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered inspection_config-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Inspection Configuration Form
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

                            <?php if(isset($inspection_config['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $inspection_config['id']; ?>" />
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-12">
                                
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" id="inspection-config-inspection-error">
                                                <label class="control-label">Select Inspection:
                                                    <span class="required"> * </span>
                                                </label>
                                                        
                                                <select name="inspection_id" id="update-inspection-config-inspection" class="form-control required select2me"
                                                    data-placeholder="Select Inspection" data-error-container="#inspection-config-inspection-error">
                                                    <option value=""></option>
                                                    <?php $sel_inspection = (!empty($inspection_config['inspection_id']) ? $inspection_config['inspection_id'] : ''); ?>
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
                                            <div class="form-group">
                                                <label class="control-label">Inspection Type:
                                                <span class="required"> * </span></label>
                                                <div class="radio-list">
                                                    <?php $sel_type = (!empty($inspection_config['inspection_type']) ? $inspection_config['inspection_type'] : 'Model.Suffix'); ?>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="inspection_type" class="required inspection-type" value="Model.Suffix" <?php if($sel_type === 'Model.Suffix') { ?>checked="checked"<?php } ?>> Model.Suffix Wise
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="inspection_type" class="required inspection-type" value="Tool" <?php if($sel_type === 'Tool') { ?>checked="checked"<?php } ?>> Tool Wise
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" id="inspection-config-sel-line-error">
                                                <label class="control-label">Select Line:
                                                <span class="required"> * </span></label>
                                                        
                                                <select name="line" class="required form-control select2me"
                                                    data-placeholder="Select Line" data-error-container="#inspection-config-sel-line-error">
                                                    <option value="all">All</option>
                                                    <?php $sel_line = (!empty($inspection_config['line']) ? $inspection_config['line'] : ''); ?>
                                                    <?php foreach($lines as $line) { ?>
                                                        <option value="<?php echo $line['id']; ?>" <?php if($line['id'] == $sel_line) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $line['name']; ?>
                                                        </option>
                                                    <?php } ?>        
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 config-tool-sel-div" <?php if($sel_type !== 'Tool') { ?>style="display:none;"<?php } ?>>
                                            <div class="form-group" id="inspection-config-sel-tool-error">
                                                <label class="control-label">Select Tool:
                                                <span class="required"> * </span></label>
                                                        
                                                <select name="tool" class="required form-control select2me"
                                                    data-placeholder="Select Tool" data-error-container="#inspection-config-sel-tool-error">
                                                    <option value="all">All</option>
                                                    <?php $sel_tool = (!empty($inspection_config['tool']) ? $inspection_config['tool'] : ''); ?>
                                                    <?php foreach($tools as $tool) { ?>
                                                        <option value="<?php echo $tool['tool']; ?>" <?php if($tool['tool'] == $sel_tool) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $tool['tool']; ?>
                                                        </option>
                                                    <?php } ?>        
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 config-model-sel-div" <?php if($sel_type !== 'Model.Suffix') { ?>style="display:none;"<?php } ?>>
                                            <div class="form-group" id="inspection-config-sel-model-error">
                                                <label class="control-label">Select Model.Suffix:
                                                <span class="required"> * </span></label>
                                                        
                                                <select name="model_suffix" class="required form-control select2me"
                                                    data-placeholder="Select Model.Suffix" data-error-container="#inspection-config-sel-model-error">
                                                    <option value="all">All</option>
                                                    <?php $sel_model_suffix = (!empty($inspection_config['model']) ? $inspection_config['model'] : ''); ?>
                                                    <?php foreach($model_suffixs as $model_suffix) { ?>
                                                        <option value="<?php echo $model_suffix['model_suffix']; ?>" <?php if($model_suffix['model_suffix'] == $sel_model_suffix) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $model_suffix['model_suffix']; ?>
                                                        </option>
                                                    <?php } ?>        
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" id="inspection-config-sel-type-error">
                                                <label class="control-label">Select Type:
                                                    <span class="required"> * </span>
                                                </label>
                                                        
                                                <select id="inspection-config-sampling-type" name="sampling_type" class="form-control required select2me"
                                                    data-placeholder="Select Type" data-error-container="#inspection-config-sel-type-error">
                                                    <option></option>
                                                    <?php $sampling_type = (!empty($inspection_config['sampling_type']) ? $inspection_config['sampling_type'] : ''); ?>
                                                    
                                                    <option value="Auto" <?php if($sampling_type == 'Auto') { ?> selected="selected" <?php } ?>>Auto</option>
                                                    <option value="User Defined" <?php if($sampling_type == 'User Defined') { ?> selected="selected" <?php } ?>>User Defined</option>
                                                    <option value="Interval" <?php if($sampling_type == 'Interval') { ?> selected="selected" <?php } ?>>Interval</option>
                                                    <option value="No Inspection" <?php if($sampling_type == 'No Inspection') { ?> selected="selected" <?php } ?>>No Inspection</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                            </div>
                            
                            <div class="row type-specific-div" id="type-auto-div" <?php if($sampling_type != 'Auto') { ?> style="display:none;" <?php } ?>>
                                <div class="col-md-6">
                                    <div class="form-group" id="inspection-config-sel-inspection-level-error">
                                        <label class="control-label">Inspection Level:
                                            <span class="required"> * </span>
                                        </label>
                                                
                                        <select name="inspection_level" class="form-control required select2me"
                                            data-placeholder="Select Inspection Level" data-error-container="#inspection-config-sel-inspection-level-error">
                                            <option></option>
                                            <?php $inspection_level = (!empty($inspection_config['inspection_level']) ? $inspection_config['inspection_level'] : ''); ?>
                                            
                                            <option value="S-1" <?php if($inspection_level == 'S-1') { ?> selected="selected" <?php } ?>>S-1</option>
                                            <option value="S-2" <?php if($inspection_level == 'S-2') { ?> selected="selected" <?php } ?>>S-2</option>
                                            <option value="S-3" <?php if($inspection_level == 'S-3') { ?> selected="selected" <?php } ?>>S-3</option>
                                            <option value="S-4" <?php if($inspection_level == 'S-4') { ?> selected="selected" <?php } ?>>S-4</option>
                                            <option value="1" <?php if($inspection_level == '1') { ?> selected="selected" <?php } ?>>1</option>
                                            <option value="2" <?php if($inspection_level == '2') { ?> selected="selected" <?php } ?>>2</option>
                                            <option value="3" <?php if($inspection_level == '4') { ?> selected="selected" <?php } ?>>3</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group" id="inspection-config-sel-acceptable-quality-level-error">
                                        <label class="control-label">Acceptable Quality:
                                            <span class="required"> * </span>
                                        </label>
                                        
                                        <select name="acceptable_quality" class="form-control required select2me"
                                            data-placeholder="Select Acceptable Quality" data-error-container="#inspection-config-sel-acceptable-quality-error">
                                            <option></option>
                                            <?php $sel_acceptable_quality = (!empty($inspection_config['acceptable_quality']) ? $inspection_config['acceptable_quality'] : ''); ?>
                                            <?php foreach($acceptable_qualities as $acceptable_quality) { ?>
                                                <option value="<?php echo $acceptable_quality['quality']; ?>" <?php if($acceptable_quality['quality'] == $sel_acceptable_quality) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $acceptable_quality['quality']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                    
                                    
                                </div>
                            </div>
                            
                            <div class="row type-specific-div" id="type-interval-div" <?php if($sampling_type != 'Interval') { ?> style="display:none;" <?php } ?>>
                                <div class="col-md-6">
                                    <div class="form-group" id="inspection-config-sel-no_of_months-error">
                                        <label class="control-label">Frequency:
                                            <span class="required"> * </span>
                                        </label>
                                                
                                        <select name="no_of_months" class="form-control required select2me"
                                            data-placeholder="Select Frequency" data-error-container="#inspection-config-sel-no_of_months-error">
                                            <option></option>
                                            <?php $no_of_months = (!empty($inspection_config['no_of_months']) ? $inspection_config['no_of_months'] : ''); ?>
                                            
                                            <option value="Dialy" <?php if($no_of_months == 'Dialy') { ?> selected="selected" <?php } ?>>Dialy</option>
                                            <option value="Weekly" <?php if($no_of_months == 'Weekly') { ?> selected="selected" <?php } ?>>Weekly</option>
                                            <option value="Bi-Monthly" <?php if($no_of_months == 'Bi-Monthly') { ?> selected="selected" <?php } ?>>Bi-Monthly</option>
                                            <option value="1" <?php if($no_of_months == '1') { ?> selected="selected" <?php } ?>>1 month</option>
                                            <option value="2" <?php if($no_of_months == '2') { ?> selected="selected" <?php } ?>>2 months</option>
                                            <option value="3" <?php if($no_of_months == '3') { ?> selected="selected" <?php } ?>>3 months</option>
                                            <option value="4" <?php if($no_of_months == '4') { ?> selected="selected" <?php } ?>>4 months</option>
                                            <option value="5" <?php if($no_of_months == '5') { ?> selected="selected" <?php } ?>>5 months</option>
                                            <option value="6" <?php if($no_of_months == '6') { ?> selected="selected" <?php } ?>>6 months</option>
                                            <option value="7" <?php if($no_of_months == '7') { ?> selected="selected" <?php } ?>>7 months</option>
                                            <option value="8" <?php if($no_of_months == '8') { ?> selected="selected" <?php } ?>>8 months</option>
                                            <option value="9" <?php if($no_of_months == '9') { ?> selected="selected" <?php } ?>>9 months</option>
                                            <option value="10" <?php if($no_of_months == '10') { ?> selected="selected" <?php } ?>>10 months</option>
                                            <option value="11" <?php if($no_of_months == '11') { ?> selected="selected" <?php } ?>>11 months</option>
                                            <option value="12" <?php if($no_of_months == '12') { ?> selected="selected" <?php } ?>>12 months</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="no_of_times">No of Times:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="no_of_times"
                                        value="<?php echo isset($inspection_config['no_of_times']) ? $inspection_config['no_of_times'] : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row type-specific-div" id="lot-size-div" <?php if($sampling_type != 'User Defined' && $sampling_type != 'Interval') { ?> style="display:none;" <?php } ?>>
                            
                                <input type="hidden" id="lot-index" value="<?php echo (!empty($config_range) ? count($config_range)+1 : 3); ?>" />
                                <div class="col-md-12">
                                    <fieldset>
                                        <legend style="font-size: 14px;">Specify Range
                                            <button id="add-lot-range" class="button small gray pull-right" type="button">Add Range</button>
                                        </legend>
                                        
                                        <div class="row items">
                                            <?php if(empty($config_range)) { ?>
                                                <div class="lot-item lot-item-1">
                                                    <div class="col-md-6">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="text" class="required form-control" name="lower_val[1]" value="" placeholder="Lower">
                                                                <span class="help-block">
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1" style="padding-top: 5px; padding-bottom: 5px;">
                                                            <span class="">to</span>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="text" class="required form-control" name="higher_val[1]" value="" placeholder="Higher">
                                                                <span class="help-block">
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="text" class="required form-control" name="no_of_samples[1]" value="" placeholder="# of Samples">
                                                                <span class="help-block">
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <a class="btn btn-icon-only btn-outline red remove-lot-range" href="javascript:;">
                                                                <i class="fa fa-trash-o"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="lot-item lot-item-2">
                                                    <div class="col-md-6">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="text" class="required form-control" name="lower_val[2]" value="" placeholder="Lower">
                                                                <span class="help-block">
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1" style="padding-top: 5px; padding-bottom: 5px;">
                                                            <span class="">to</span>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="text" class="required form-control" name="higher_val[2]" value="" placeholder="Higher">
                                                                <span class="help-block">
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="text" class="required form-control" name="no_of_samples[2]" value="" placeholder="# of Samples">
                                                                <span class="help-block">
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <a class="btn btn-icon-only btn-outline red remove-lot-range" href="javascript:;">
                                                                <i class="fa fa-trash-o"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <?php foreach($config_range as $c_key => $c_range) { ?>
                                                    <div class="lot-item lot-item-<?php echo $c_key+1; ?>">
                                                        <div class="col-md-6">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="text" class="required form-control" name="lower_val[<?php echo $c_key+1; ?>]" value="<?php echo $c_range['lower_val']; ?>" placeholder="Lower">
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1" style="padding-top: 5px; padding-bottom: 5px;">
                                                                <span class="">to</span>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="text" class="required form-control" name="higher_val[<?php echo $c_key+1; ?>]" value="<?php echo ($c_range['higher_val']) ? $c_range['higher_val'] : 'over'; ?>" placeholder="Higher">
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <input type="text" class="required form-control" name="no_of_samples[<?php echo $c_key+1; ?>]" value="<?php echo $c_range['no_of_samples']; ?>" placeholder="# of Samples">
                                                                    <span class="help-block">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <a class="btn btn-icon-only btn-outline red remove-lot-range" href="javascript:;">
                                                                    <i class="fa fa-trash-o"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'sampling/configs'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

<div id="lot-item-clone" style="display:none;">
    <div class="lot-item">
        <div class="col-md-6">
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="required form-control lower-val-input" name="" value="" placeholder="Lower">
                    <span class="help-block">
                    </span>
                </div>
            </div>
            <div class="col-md-1" style="padding-top: 5px; padding-bottom: 5px;">
                <span class="">to</span>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="required form-control higher-val-input" name="" value="" placeholder="Higher">
                    <span class="help-block">
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="required form-control samples-val-input" name="" value="" placeholder="# of Samples">
                    <span class="help-block">
                    </span>
                </div>
            </div>
            <div class="col-md-1">
                <a class="btn btn-icon-only btn-outline red remove-lot-range" href="javascript:;">
                    <i class="fa fa-trash-o"></i>
                </a>
            </div>
        </div>
    </div>
</div>