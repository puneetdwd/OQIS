<div class="modal-header">
    <h4 class="modal-title">Notification</h4>
</div>
<form role="form" class="validate-form form-horizontal" action="<?php echo base_url().'dashboard/view_notification/'.$notification['id'].'/'.$audit_flag; ?>" method="post">
    <div class="modal-body">
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            Please fill No of Samples or else SKIP.
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="control-label">
                    Inspection Date  : 
                </label>
                <p class="form-control-static">
                    <?php echo date('jS M, Y', strtotime($notification['audit_date'])); ?>
                </p>
            </div>
            
            <div class="col-md-6">
                <label class="control-label">
                    Inspection :
                </label>
                <p class="form-control-static">
                    <?php echo $notification['inspection_name']; ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="control-label">
                    Model.Suffix :
                </label>
                <p class="form-control-static">
                    <?php echo $notification['model_suffix']; ?>
                </p>
            </div>
            
            <div class="col-md-6">
                <label class="control-label">
                    Serial No :
                </label>
                <p class="form-control-static">
                    <?php echo $notification['serial_no']; ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="control-label">
                    Checkpoint No :
                </label>
                <p class="form-control-static">
                    <?php echo $notification['checkpoint_no']; ?>
                </p>
            </div>
            
            <div class="col-md-6">
                <label class="control-label">
                    Insp Item :
                </label>
                <p class="form-control-static">
                    <?php echo ($notification['insp_item'] ? $notification['insp_item'] : '-'); ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="control-label">
                    Insp Item :
                </label>
                <p class="form-control-static">
                    <?php echo ($notification['insp_item2'] ? $notification['insp_item2'] : '-'); ?>
                </p>
            </div>
            
            <div class="col-md-6">
                <label class="control-label">
                    Insp Item :
                </label>
                <p class="form-control-static">
                    <?php echo ($notification['insp_item3'] ? $notification['insp_item3'] : '-'); ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="control-label">
                    Spec :
                </label>
                <p class="form-control-static">
                    <?php echo $notification['spec']; ?>
                </p>
            </div>
        </div>
        
    </div>
    
    <div class="modal-footer">
        <button type="button" class="button white" data-dismiss="modal">Close</button>
        <a class="button" data-toggle="modal" href="#approve-option-modal"> Approve </a>
        <a class="btn red btn-outline sbold" href="<?php echo base_url().'dashboard/notification_action/'.$notification['id'].'/'.$audit_flag.'?status=decline'; ?>">Decline</a>
    </div>
</form>

<div class="modal fade" id="approve-option-modal" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
                <h4 class="modal-title">Approve Option</h4>
            </div>
            <div class="modal-footer">
                <a class="button" href="<?php echo base_url().'dashboard/notification_action/'.$notification['id'].'/'.$audit_flag.'?status=approve_always'; ?>">Add in Exclude List</a>
                <a class="button gray" href="<?php echo base_url().'dashboard/notification_action/'.$notification['id'].'/'.$audit_flag.'?status=approve'; ?>">Approve for this lot</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>