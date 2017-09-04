<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($spec) ? 'Edit': 'Add'); ?> Spec
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
                <a href="<?php echo base_url()."inspections/checkpoints/".$checkpoint['inspection_id']; ?>">
                    Manage Checkpoints
                </a>
            </li>
            <li>
                <a href="<?php echo base_url()."inspections/specs/".$checkpoint['inspection_id'].'/'.$checkpoint['id']; ?>">
                    Manage specs
                </a>
            </li>
            <li class="active"><?php echo (isset($spec) ? 'Edit': 'Add'); ?> Spec</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered spec-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Spec Form - for checkpoint no <?php echo $checkpoint['checkpoint_no']; ?> (
                            LSL : <?php echo ($checkpoint['lsl']) ? $checkpoint['lsl'].' '.$checkpoint['unit'] : ''; ?>,
                            USL : <?php echo ($checkpoint['usl']) ? $checkpoint['usl'].' '.$checkpoint['unit'] : ''; ?>,
                            TGT : <?php echo ($checkpoint['tgt']) ? $checkpoint['tgt'].' '.$checkpoint['unit'] : ''; ?>
                        )
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

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="app-spec-sel-tool-error">
                                        <label class="control-label">Select Tool:
                                        </label>
                                                
                                        <select name="tool[]" class="form-control select2me"
                                            data-placeholder="Select Tool" data-error-container="#app-spec-sel-tool-error" multiple>
                                            <option></option>
                                            <?php foreach($tools as $tool) { ?>
                                                <option value="<?php echo $tool['tool']; ?>">
                                                    <?php echo $tool['tool']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <?php if(!empty($spec['id'])) { ?>
                                        <div class="">
                                            <label class="control-label"><b>Model.Suffix:</b></label>
                                            <p class="form-control-static">
                                                <?php echo $spec['model_suffix']; ?>
                                            </p>
                                        </div>
                                    <?php } else { ?>
                                        <div class="form-group" id="app-spec-sel-model-error">
                                            <label class="control-label">Select Model.Suffix:
                                            </label>
                                                    
                                            <select name="model_suffix[]" class="form-control select2me"
                                                data-placeholder="Select Model.Suffix" data-error-container="#app-spec-sel-model-error" multiple>
                                                <option></option>
                                                <?php foreach($model_suffixs as $model_suffix) { ?>
                                                    <option value="<?php echo $model_suffix['model_suffix']; ?>">
                                                        <?php echo $model_suffix['model_suffix']; ?>
                                                    </option>
                                                <?php } ?>        
                                            </select>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="lsl">LSL:</label>
                                        <input type="text" class="form-control" name="lsl"
                                        value="<?php echo isset($spec['lsl']) ? $spec['lsl'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="usl">USL:</label>
                                        <input type="text" class="form-control" name="usl"
                                        value="<?php echo isset($spec['usl']) ? $spec['usl'] : ''; ?>">
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
                                        value="<?php echo isset($spec['tgt']) ? $spec['tgt'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="unit">Unit:</label>
                                        <input type="text" class="form-control" name="unit"
                                        value="<?php echo isset($spec['unit']) ? $spec['unit'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'inspections/specs/'.$checkpoint['id']; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>