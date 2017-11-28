<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    
	<table>
        <tr>
			<td colspan="5">
				<h1>Completed Inspections</h1>
			</td>
			<td>
				<?php echo "<b>Date: </b>" . date("d M Y"); ?>
			</td>
		</tr>
		<tr>&nbsp;</tr>
    </table>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

                    <div class="portlet light bordered">
                        
                        <div class="portlet-body">
                               <div class="table-responsive">
                                     <table class="table table-hover table-light" border="1" style="width:100%;border-collapse:collapse">
                                        <thead>
                                            <tr style="background-color:'#C0C0C0';color:'#000'">
                                                <th>Date</th>
                                                <th>Inspection Name</th>
                                                <!--th style="width:50px;"></th-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($audits as $audit) { ?>
                                                <tr>
                                                    <td><?php echo date('d-m-Y', strtotime($audit['sampling_date'])); ?></td>
                                                    <td><?php echo $audit['inspection_name']; ?></td>
                                                    <!--td>
                                                        <a class="button small gray" 
                                                            href="<?php echo base_url()."reports/completed_inspection_details?date=".$audit['sampling_date'].'&insp='.$audit['inspection_id'];?>">
                                                            <i class="fa fa-edit"></i> View Details
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
    <!-- END PAGE CONTENT-->
</div>