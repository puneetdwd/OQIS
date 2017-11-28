<html>
<body>

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <table>
        <tr>
			<td colspan="8">
				<h1>MPAT Inspections Status</h1>
			</td>
			<td style='float:right'>
				<?php echo "<b>Date: </b>" . date("d M Y"); ?>
			</td>
		</tr>
		<tr>&nbsp;</tr>
    </table>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <div class="row">
                
                <div class="col-md-12">
                    <div class="portlet light bordered">
                       
                        <div class="portlet-body">
                            
                                <div class="table-responsive">
                                    <table class="table table-hover table-light"  border="1" style="width:100%;border-collapse:collapse">
                                        <thead>
                                            <tr  style='background-color:#C8C8C8;color:#000'>
                                                <th>Inspector</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Inspection</th>
                                                <th>Line</th>
                                                <th>Model.Suffix</th>
                                                <th>Tool</th>
                                                <th>Workorder</th>
                                                <th>Serial No.</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($audits as $audit) { ?>
                                                <tr>
                                                    <td><?php echo $audit['auditer']; ?></td>
                                                    <td nowrap><?php echo date('d-m-Y', strtotime($audit['audit_date'])); ?></td>
													<td nowrap>
													<?php if(!empty($audit['iteration_datetime']))
															echo date('jS M, y', strtotime($audit['iteration_datetime'])); 
														   else	echo '';
													?></td>
													<td><?php echo $audit['inspection_name']; ?></td>
                                                    <td><?php echo $audit['line_name']; ?></td>
                                                    <td><?php echo $audit['model_suffix']; ?></td>
                                                    <td><?php echo $audit['tool']; ?></td>
                                                    <td><?php echo $audit['workorder']; ?></td>
                                                    <td><?php echo $audit['serial_no']; ?></td>
                                                    <td class="text-center">
                                                        <?php if($audit['on_hold'] == 0) { ?>
                                                            <span class="label label-warning"> 
                                                                <i class="fa fa-spinner"></i> On Going - Iteration <?php echo $audit['current_iteration'];?>
                                                            </span>
                                                        <?php } else { ?>
                                                            <?php if(strtotime($audit['iteration_datetime']) <= strtotime('now')) { ?>
                                                                <span class="label label-danger"> 
                                                                    <i class="fa fa-ban"></i> On Hold
                                                                </span>
                                                                &nbsp;&nbsp;
                                                                <span class="label label-danger"> 
                                                                    <i class="fa fa-check"></i> Iteration <?php echo $audit['current_iteration']?> activated
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="label label-warning"> 
                                                                    <i class="fa fa-check"></i> Iteration <?php echo $audit['current_iteration']?> will start on <?php echo date('jS M h:ia', strtotime($audit['iteration_datetime'])); ?>
                                                                </span>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
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
    </div>
    <!-- END PAGE CONTENT-->
</div>
</body>
</html>