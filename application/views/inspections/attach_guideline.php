<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Attach guideline Image</h4>
</div>
<form role="form" class="attach-guideline-form validate-form" action="<?php echo base_url().'inspections/attach_guideline/'.$checkpoint_id; ?>" method="post" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            Please choose a guideline image before submit.
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
                    <label for="checkpoints_excel" class="control-label">Upload Guideline Image:
                    </label>
                    <input type="hidden" name="trigger_submit" value="1" />
                    <input type="file" name="guideline" class="guideline-image-field required">
                </div>
            </div>
        </div>
            
    </div>
        
    <div class="modal-footer">
        <button class="button" type="submit" name="image" value="true">Submit</button>
        <button type="button" class="attach-guideline-modal-close button white" data-dismiss="modal">Close</button>
    </div>
</form>