<div class="page-content">
    <table>
        <tr>
			<td colspan="4">
				<h1>NA Checkpoints Dashboard</h1>
			</td>
			<td>
				<?php echo "<b>Date: </b>" . date("d M Y"); ?>
			</td>
		</tr>
		<tr>&nbsp;</tr>
    </table>
    <div class="row">
        <div class="col-md-12">
           <div class="portlet light bordered">
                <div class="portlet-body">
                    <div class="table-responsive">
                            <table class="table table-hover table-bordered" border="1" style="width:100%;border-collapse:collapse">
                                <thead>
                                    <tr style="background-color:'#C0C0C0';color:'#000'">
                                        <th>Inspection Date</th>
                                        <th>Inspection</th>
                                        <th>Model.Suffix</th>
                                        <th>Serial No</th>
                                        <th>Checkpoint No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                        <?php foreach($audit_checkpoints as $audit_checkpoint) { ?>
                                            <tr>
                                                <td><?php echo date('d-m-Y', strtotime($audit_checkpoint['audit_date'])); ?></td>
                                                <td><?php echo $audit_checkpoint['inspection_name']; ?></td>
                                                <td><?php echo $audit_checkpoint['model_suffix']; ?></td>
                                                <td><?php echo $audit_checkpoint['serial_no']; ?></td>
                                                <td><?php echo $audit_checkpoint['checkpoint_no']; ?></td>
                                                <!--td class="hide-till-load" style="display:none;">
                                                    <a class="button small notification-<?php echo $audit_checkpoint['id']; ?>" href="<?php echo base_url()."dashboard/view_notification/".$audit_checkpoint['id'].'/direct'; ?>" data-target="#notification-detail-modal" data-toggle="modal">
                                                        View
                                                    </a>
                                                </td-->
                                            </tr>
                                        <?php } ?>
                                    
                                </tbody>
                            </table>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!--div class="modal fade" id="notification-detail-modal" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
</script-->