<div class="page-content">
    <div class="breadcrumbs">
        <h1>
            NA Checkpoints Dashboard
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">NA Checkpoints Dashboard</li>
        </ol>
        
    </div>
        
    <?php if($this->session->flashdata('error')) {?>
        <div class="alert alert-danger">
           <i class="icon-remove"></i>
           <?php echo $this->session->flashdata('error');?>
        </div>
    <?php } else if($this->session->flashdata('success')) { ?>
        <div class="alert alert-success">
            <i class="icon-ok"></i>
           <?php echo $this->session->flashdata('success');?>
        </div>
    <?php } ?>
    
    <div class="row">
        <div class="col-md-12">
           <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>List of Pending NA Checkpoints
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-responsive">
                        <?php if(empty($audit_checkpoints)) { ?>
                            <p class="text-center">No pending checkpoint.</p>
                        <?php } else { ?>
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Inspection Date</th>
                                        <th>Inspection</th>
                                        <th>Model.Suffix</th>
                                        <th>Serial No</th>
                                        <th>Checkpoint No</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php foreach($audit_checkpoints as $audit_checkpoint) { ?>
                                            <tr>
                                                <td><?php echo date('jS M, Y', strtotime($audit_checkpoint['audit_date'])); ?></td>
                                                <td><?php echo $audit_checkpoint['inspection_name']; ?></td>
                                                <td><?php echo $audit_checkpoint['model_suffix']; ?></td>
                                                <td><?php echo $audit_checkpoint['serial_no']; ?></td>
                                                <td><?php echo $audit_checkpoint['checkpoint_no']; ?></td>
                                                <td class="hide-till-load" style="display:none;">
                                                    <a class="button small notification-<?php echo $audit_checkpoint['id']; ?>" href="<?php echo base_url()."dashboard/view_notification/".$audit_checkpoint['id'].'/direct'; ?>" data-target="#notification-detail-modal" data-toggle="modal">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tr>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<div class="modal fade" id="notification-detail-modal" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img src="<?php echo base_url(); ?>assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;Loading... </span>
            </div>
        </div>
    </div>
</div>

<script>
    $(window).load(function() {
        $('.hide-till-load').show();
    });
</script>