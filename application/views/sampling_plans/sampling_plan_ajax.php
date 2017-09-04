<div class="modal-header">
    <h4 class="modal-title">Sampling Plan - <?php echo date('jS M, Y', strtotime($plan['sampling_date'])); ?></h4>
</div>
<form role="form" class="adjust-sampling-form validate-form form-horizontal" action="<?php echo base_url().'sampling/sampling_plan/'.$plan['id']; ?>" method="post">
    <div class="modal-body">
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            Please fill No of Samples or else SKIP.
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="control-label">
                    Line : 
                </label>
                <p class="form-control-static">
                    <?php echo $plan['line']; ?>
                </p>
            </div>
            
            <div class="col-md-6">
                <label class="control-label">
                    Tool :
                </label>
                <p class="form-control-static">
                    <?php echo ($plan['tool']) ? $plan['tool'] : 'NA'; ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="control-label">
                    Model.Suffix :
                </label>
                <p class="form-control-static">
                    <?php echo $plan['model_suffix']; ?>
                </p>
            </div>
            
            <div class="col-md-6">
                <label class="control-label">
                    No of Samples :
                </label>
                <p class="form-control-static">
                    <?php echo $plan['no_of_samples']; ?>
                </p>
            </div>
        </div>
        
        
        <?php 
            $planned = $stats['planned'] ? $stats['planned'] : 0;
            $produced = $stats['produced'] ? $stats['produced'] : 0;
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?php if($planned-$produced < 200) { ?>
                        <label class="control-label col-md-5" for="no_of_samples">Adjust No of Samples:</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="no_of_samples" value="">
                        </div>
                    <?php } else { ?>
                        <p class="text-danger text-center" style="margin-bottom:0px;">Can't adjust this Sampling Qty. </p>
                    <?php } ?>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="modal-footer">
        <button type="button" class="adjust-sampling-modal-close button white" data-dismiss="modal">Close</button>
        <?php if($planned-$produced < 200) { ?>
            <button type="submit" id="adjust-sampling-modal-save" class="button">Save</button>
            <button type="submit" id="adjust-sampling-modal-skip" name="skip" value="skip" class="button">Skip</button>
        <?php } ?>
    </div>
</form>