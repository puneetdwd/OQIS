<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Automate settings</h4>
</div>
<form role="form" class="validate-form automate-settings-form" action="<?php echo base_url().'inspections/automate_settings/'.$checkpoint_id; ?>" method="post" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            Please fill all the fields before submit.
        </div>

        <?php if(isset($error)) { ?>
            <div class="alert alert-danger">
                <i class="fa fa-times"></i>
                <?php echo $error; ?>
            </div>
        <?php } ?>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="automate_result_row">Row:
                    <span class="required">*</span></label>
                    <input type="text" class="required form-control" id="automate_result_row" name="automate_result_row">
                    <span class="help-block">
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="automate_result_col">Column:
                    <span class="required">*</span></label>
                    <input type="text" class="required form-control" id="automate_result_col" name="automate_result_col">
                    <span class="help-block">
                    </span>
                </div>
            </div>
        </div>
            
    </div>
        
    <div class="modal-footer">
        <button class="button" type="submit">Submit</button>
        <button type="button" class="automate-setting-modal-close button white" data-dismiss="modal">Close</button>
    </div>
</form>