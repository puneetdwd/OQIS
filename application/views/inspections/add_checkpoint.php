<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($checkpoint) ? 'Edit': 'Add'); ?> Checkpoint
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
            <li class="active"><?php echo (isset($checkpoint) ? 'Edit': 'Add'); ?> Checkpoint</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered checkpoint-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Checkpoint Form - <?php echo $inspection['name']; ?>
                    </div>
                </div>

                <div class="portlet-body form">
                    <form role="form" id="add-checkpoint-form" class="validate-form" method="post">
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

                            <input type="hidden" id="existing_checkpoints" value="<?php echo $existing_checkpoints; ?>">
                                        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="checkpoint_no">Checkpoint No:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" id="add-checkpoint-no" name="checkpoint_no"
                                        value="<?php echo isset($checkpoint['checkpoint_no']) ? $checkpoint['checkpoint_no'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="gmes_code">GMES Code:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="gmes_code"
                                        value="<?php echo isset($checkpoint['gmes_code']) ? $checkpoint['gmes_code'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <?php if(empty($inspection['full_auto'])) { ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="insp_item">Insp Item:
                                            <span class="required">*</span></label>
                                            <input type="text" class="required form-control" name="insp_item"
                                            value="<?php echo isset($checkpoint['insp_item']) ? $checkpoint['insp_item'] : ''; ?>">
                                            <span class="help-block">
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <?php if($inspection['checkpoint_format'] >= 3) { ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="insp_item2">Insp Item:
                                                <span class="required">*</span></label>
                                                <input type="text" class="required form-control" name="insp_item2"
                                                value="<?php echo isset($checkpoint['insp_item2']) ? $checkpoint['insp_item2'] : ''; ?>">
                                                <span class="help-block">
                                                </span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    
                                    <?php if($inspection['checkpoint_format'] == 4) { ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label" for="insp_item4">Insp Item:
                                                <span class="required">*</span></label>
                                                <input type="text" class="required form-control" name="insp_item4"
                                                value="<?php echo isset($checkpoint['insp_item4']) ? $checkpoint['insp_item4'] : ''; ?>">
                                                <span class="help-block">
                                                </span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="insp_item3">Insp Item
                                        <span class="required">*</span></label>
                                        <textarea class="required form-control" name="insp_item3"><?php echo isset($checkpoint['insp_item3']) ? $checkpoint['insp_item3'] : ''; ?></textarea>
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="spec">Spec:
                                        <span class="required">*</span></label>
                                        <textarea class="required form-control" name="spec"><?php echo isset($checkpoint['spec']) ? $checkpoint['spec'] : ''; ?></textarea>
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="lsl">LSL:</label>
                                        <input type="text" class="form-control" name="lsl"
                                        value="<?php echo isset($checkpoint['lsl']) ? $checkpoint['lsl'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="usl">USL:</label>
                                        <input type="text" class="form-control" name="usl"
                                        value="<?php echo isset($checkpoint['usl']) ? $checkpoint['usl'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="tgt">TGT:</label>
                                        <input type="text" class="form-control" name="tgt"
                                        value="<?php echo isset($checkpoint['tgt']) ? $checkpoint['tgt'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="unit">Unit:</label>
                                        <input type="text" class="form-control" name="unit"
                                        value="<?php echo isset($checkpoint['unit']) ? $checkpoint['unit'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if(isset($checkpoint['id'])) { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="remark">Remark:
                                            <span class="required">*</span></label>
                                            <textarea class="form-control required" name="remark"></textarea>
                                            <span class="help-block">
                                                Add remark for updating this checkpoint.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'inspections/checkpoints/'.$inspection['id']; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>