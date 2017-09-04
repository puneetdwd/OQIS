<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($iteration) ? 'Edit': 'Add'); ?> Iteration
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
            <li>
                <a href="<?php echo base_url()."inspections/checkpoints/".$inspection['id']; ?>">
                    Manage Checkpoints
                </a>
            </li>
            <li class="active"><?php echo (isset($iteration) ? 'Edit': 'Add'); ?> Iteration</li>
        </ol>
        
    </div>
    
    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered iteration-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Iteration Form
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

                            <?php if(isset($iteration['id'])) { ?>
                                <input type="hidden" name="id" id="existing-id" value="<?php echo $iteration['id']; ?>" />
                            <?php } ?>
                            
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="iteration_no">Iteration NO:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="iteration_no"
                                        value="<?php echo isset($iteration['iteration_no']) ? $iteration['iteration_no'] : ''; ?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="iteration_time">Iteration Time:
                                        <span class="required">*</span></label>
                                        <div class="input-group" style="display:inherit;"> 
                                            <input type="text" class="form-control" name="iteration_time" style="width:70%;" 
                                            value="<?php echo isset($iteration['iteration_time']) ? $iteration['iteration_time'] : ''; ?>">
                                            
                                            <?php $sel_iter_time_type = isset($iteration['iter_time_type']) ? $iteration['iter_time_type'] : '';?>
                                            <select class="form-control" name="iter_time_type" style="width:30%;">
                                                <option value="hours" <?php if($sel_iter_time_type == 'hours') { ?>selected="selected"<?php } ?>>Hours</option>
                                                <option value="days" <?php if($sel_iter_time_type == 'days') { ?>selected="selected"<?php } ?>>Days</option>
                                                <option value="weeks" <?php if($sel_iter_time_type == 'weeks') { ?>selected="selected"<?php } ?>>Weeks</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="row">
                                <?php if(empty($checkpoints)) { ?>
                                    <p class="text-center">No Checkpoints.</p>
                                <?php } else { ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered table-light table-checkable">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Checkpoint No.</th>
                                                    <th>Insp. Item</th>
                                                    <?php if($inspection['checkpoint_format'] >= 3) { ?>
                                                        <th>Insp. Item</th>
                                                    <?php } ?>
                                                    <?php if($inspection['checkpoint_format'] == 4) { ?>
                                                        <th>Insp. Item</th>
                                                    <?php } ?>
                                                    <th>Insp. Item</th>
                                                    <th>Spec.</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $existing = isset($iteration['checkpoints']) ? explode(',', $iteration['checkpoints']) : array(); ?>
                                                <?php foreach($checkpoints as $checkpoint) { ?>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="checkboxes" name="checkpoints[]" value="<?php echo $checkpoint['id']; ?>" 
                                                            <?php if(in_array($checkpoint['id'], $existing)) { ?> checked="checked" <?php } ?>
                                                            />
                                                        </td>
                                                        <td><?php echo $checkpoint['checkpoint_no']; ?></td>
                                                        <td><?php echo $checkpoint['insp_item']; ?></td>
                                                            
                                                        <?php if($inspection['checkpoint_format'] >= 3) { ?>
                                                            <td><?php echo $checkpoint['insp_item2']; ?></td>
                                                        <?php } ?>
                                                        <?php if($inspection['checkpoint_format'] == 4) { ?>
                                                            <td><?php echo $checkpoint['insp_item4']; ?></td>
                                                        <?php } ?>
                                                    
                                                        <td><?php echo $checkpoint['insp_item3']; ?></td>
                                                        <td><?php echo $checkpoint['spec']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>
                            </div>
                            
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'inspections/iterations'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>