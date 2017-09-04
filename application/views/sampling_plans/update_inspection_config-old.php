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
                                        <div class="col-md-4">
                                            <div class="form-group" id="inspection-config-inspection-error">
                                                <label class="control-label">Select Inspection:
                                                    <span class="required"> * </span>
                                                </label>
                                                        
                                                <select name="inspection_id" class="form-control required select2me"
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
                                        
                                        <div class="col-md-4">
                                            
                                            <div class="form-group" id="inspection-config-sel-model-error">
                                                <label class="control-label">Select Model.Suffix:
                                                </label>
                                                        
                                                <select name="model_suffix" class="form-control select2me"
                                                    data-placeholder="Select Model.Suffix" data-error-container="#inspection-config-sel-model-error">
                                                    <option></option>
                                                    <?php $sel_model_suffix = (!empty($inspection_config['model']) ? $inspection_config['model'] : ''); ?>
                                                    <?php foreach($model_suffixs as $model_suffix) { ?>
                                                        <option value="<?php echo $model_suffix['model_suffix']; ?>" <?php if($model_suffix['model_suffix'] == $sel_model_suffix) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $model_suffix['model_suffix']; ?>
                                                        </option>
                                                    <?php } ?>        
                                                </select>
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-4">
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
                                        <label class="control-label"># of Months:
                                            <span class="required"> * </span>
                                        </label>
                                                
                                        <select name="no_of_months" class="form-control required select2me"
                                            data-placeholder="Select # of Months" data-error-container="#inspection-config-sel-no_of_months-error">
                                            <option></option>
                                            <?php $no_of_months = (!empty($inspection_config['no_of_months']) ? $inspection_config['no_of_months'] : ''); ?>
                                            
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
                            
                            <div class="row type-specific-div" id="lot-size-div" <?php if($sampling_type != 'User Defined' || $sampling_type == 'Interval') { ?> style="display:none;" <?php } ?>>
                                <div class="col-md-6">
                                    <table class="table table-hover table-light">
                                        <thead>
                                            <tr>
                                                <th colspan="3">Lot Range</th>
                                                <th># of Samples</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php foreach(array_slice($lots, 0, 8) as $lot) { ?>
                                                <tr>
                                                    <td><?php echo $lot['lower_val']; ?></td>
                                                    <td>To</td>
                                                    <td><?php echo ($lot['higher_val']) ? $lot['higher_val'] : 'over'; ?></td>
                                                    <td>
                                                        <div class="col-md-6">
                                                            <input type="text" class="input-sm  required form-control" name="lot_size[<?php echo $lot['id']; ?>]" value="<?php echo isset($excluded_checkpoint['checkpoints_nos']) ? $excluded_checkpoint['checkpoints_nos'] : ''; ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="col-md-6">
                                    <table class="table table-hover table-light">
                                        <thead>
                                            <tr>
                                                <th colspan="3">Lot Range</th>
                                                <th># of Samples</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php foreach(array_slice($lots, 8) as $lot) { ?>
                                                <tr>
                                                    <td><?php echo $lot['lower_val']; ?></td>
                                                    <td>To</td>
                                                    <td><?php echo ($lot['higher_val']) ? $lot['higher_val'] : 'over'; ?></td>
                                                    <td>
                                                        <div class="col-md-6">
                                                            <input type="text" class="input-sm  required form-control" name="lot_size[<?php echo $lot['id']; ?>]" value="<?php echo isset($excluded_checkpoint['checkpoints_nos']) ? $excluded_checkpoint['checkpoints_nos'] : ''; ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        
                            
                            <fieldset>
                                <legend style="font-size: 14px;">Specify Range</legend>
                                <div class="row items">
                                    
                                </div>
                            </fieldset>
                        </div>
                            
                            
                        <div class="form-actions">
                            <button id="add-lot-range" class="button gray" type="button">Add Range</button>
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'sampling'; ?>" class="button white">Cancel</a>
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
                    <input type="text" class="required form-control" name="lower_val[]" value="" placeholder="Lower">
                    <span class="help-block">
                    </span>
                </div>
            </div>
            <div class="col-md-1" style="padding-top: 5px; padding-bottom: 5px;">
                <span class="required">to</span>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="required form-control" name="higher_val[]" value="" placeholder="Higher">
                    <span class="help-block">
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="required form-control" name="no_of_samples[]" value="" placeholder="# of Samples">
                    <span class="help-block">
                    </span>
                </div>
            </div>
            <div class="col-md-1">
                <a class="btn btn-icon-only red remove-lot-range" href="javascript:;">
                    <i class="fa fa-trash-o"></i>
                </a>
            </div>
        </div>
    </div>
</div>