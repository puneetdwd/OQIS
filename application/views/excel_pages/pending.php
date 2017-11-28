
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <table>
        <tr>
			<td colspan="5">
				<h1>Product Inspection | Pending Reports</h1>
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
                                    <table class="table table-hover"  border="1" style="width:100%;border-collapse:collapse">
                                        <thead>
                                            <tr style='background-color:#C8C8C8;color:#000'>
                                                <th class="text-center"> Inspection </th>
                                                <?php foreach($days as $day => $v) { ?>
                                                    <th><?php echo $day; ?></th>
                                                <?php } ?> 
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($reports as $inspection_id => $report) { ?>
                                                <tr>

                                                    <?php $total = 0;?>
                                                    <?php foreach($report as $k => $r) { ?>
                                                        <?php $total += $r; ?>
                                                        <td>
                                                            <?php if($k !== 'inspection_name' && !isset($reports[$inspection_id]['Total'])) { ?>
                                                                <?php $date = date_create_from_format("jS M'y", $k)->format('Ymd'); ?>
                                                                 
                                                                    <?php echo $r; ?> 
                                                                
                                                            <?php } else { ?>
                                                                <?php echo $r; ?>
                                                            <?php } ?>
                                                        </td>
                                                    <?php } ?>
                                                    <td><?php echo $total; ?></td>
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
