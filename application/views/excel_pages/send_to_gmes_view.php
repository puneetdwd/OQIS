<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Attach OQC LOT ID</h4>
</div>
<form role="form" class="lot-id-form validate-form" action="<?php echo base_url().'reports/send_to_gmes/'.$id; ?>" method="post">
    <div class="modal-body">
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            Please Fill the OQC LOT ID before submit
        </div>

        <?php if(isset($error)) { ?>
            <div class="alert alert-danger">
                <i class="fa fa-times"></i>
                <?php echo $error; ?>
            </div>
        <?php } ?>
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="oqc_lot_id">OQC Lot ID:
                    <span class="required">*</span></label>
                    <input type="text" class="required form-control" id="oqc_lot_id_field" name="oqc_lot_id">
                </div>
            </div>
        </div>
            
    </div>
        
    <div class="modal-footer">
        <button class="button" type="submit" name="image" value="true">Submit</button>
        <button type="button" class="attach-guideline-modal-close button white" data-dismiss="modal">Close</button>
    </div>
</form>