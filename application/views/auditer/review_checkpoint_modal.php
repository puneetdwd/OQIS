<div class="modal-header">
    <h4 class="modal-title">Checkpoint Review form</h4>
</div>

<?php 
    $url = base_url().'auditer/review_checkpoint/'.$checkpoint['checkpoint_no'].'/'.($checkpoint['iteration_no'] ? $checkpoint['iteration_no'] : 0);
    if(isset($admin_edit_audit)) {
        $url .= "/".$admin_edit_audit;
    }
?>
<form role="form" class="confirmation-form validate-form" action="<?php echo $url; ?>" method="post">
    <div class="modal-body">
        <?php if(isset($error)) { ?>
            <div class="alert alert-danger">
                <i class="fa fa-times"></i>
                <?php echo $error; ?>
            </div>
        <?php } ?>
        
        <div class="row">
            <div class="col-md-8">
                <div class="mt-element-ribbon bg-grey-steel">
                    <div class="ribbon ribbon-clip ribbon-color-danger uppercase">
                        <div class="ribbon-sub ribbon-clip"></div> <b>Checkpoint #<?php echo $checkpoint['checkpoint_no']; ?></b>
                    </div>
                    <div class="ribbon-content">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label"><b>Insp Item:</b></label>
                                <p class="form-control-static">
                                    <?php echo $checkpoint['insp_item']; ?>
                                </p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="control-label"><b>Insp Item:</b></label>
                                <p class="form-control-static">
                                    <?php echo $checkpoint['insp_item2']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label"><b>Insp Item:</b></label><br />
                                <p class="form-control-static">
                                    <?php echo $checkpoint['insp_item3']; ?>
                                </p>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="control-label"><b>Spec:</b></label><br />
                                <p class="form-control-static">
                                    <?php echo $checkpoint['spec']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <img src="<?php echo base_url().$checkpoint['guideline_image']; ?>" style="width:100%;"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>LSL</th>
                            <th>USL</th>
                            <th>TGT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo ($checkpoint['lsl']) ? $checkpoint['lsl'].' '.$checkpoint['unit'] : '--'; ?></td>
                            <td><?php echo ($checkpoint['usl']) ? $checkpoint['usl'].' '.$checkpoint['unit'] : '--'; ?></td>
                            <td><?php echo ($checkpoint['tgt']) ? $checkpoint['tgt'].' '.$checkpoint['unit'] : '--'; ?></td>
                        </tr>
                    </tbody>
                </table>
                
                <?php if(!empty($checkpoint['lsl']) || !empty($checkpoint['usl'])) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="audit_value">Value:</label>
                                <input type="text" class="form-control" name="audit_value" value="<?php echo $checkpoint['audit_value']; ?>">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                <?php } else { ?> 
                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="checkpoint-screen-result-error">
                                <label class="control-label">Result</label>
                                <select name="result" class="form-control required select2me"
                                data-placeholder="Select result" data-error-container="#checkpoint-screen-result-error">
                                    <option value=""></option>
                                    <option value="OK" <?php if($checkpoint['result'] == 'OK') { ?> selected="selected" <?php } ?>>OK</option>
                                    <option value="NG" <?php if($checkpoint['result'] == 'NG') { ?> selected="selected" <?php } ?>>NG</option>
                                    <option value="NA" <?php if($checkpoint['result'] == 'NA') { ?> selected="selected" <?php } ?>>NA</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="checkpoints_excel" class="control-label">Remark: </label>
                            <textarea class="form-control" name="remark"><?php echo $checkpoint['remark']; ?></textarea>
                            <span class="help-block"></span>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions text-center">
                    <button type="submit" class="btn green-meadow">Submit</button>
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>