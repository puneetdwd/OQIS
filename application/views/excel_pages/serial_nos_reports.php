<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
	<table>
        <tr>
			<td colspan="5">
				<h1>Product Inspection | Serial No. Reports</h1>
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

                <div class="col-md-9">
                    <div class="portlet light bordered">
                        <div class="portlet-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-light"  border="1" style="width:100%;border-collapse:collapse">
                                        <thead>
                                            <tr style='background-color:#C8C8C8;color:#000'>
                                                <?php if(!$this->product_id) { ?>
                                                    <th>Product</th>
                                                <?php } ?>
                                                <th>Line</th>
                                                <th>Model.Suffix</th>
                                                <th colspan=4>Serial NOs</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($serial_nos as $serial_no) { ?>
                                                <tr>
                                                    <?php if(!$this->product_id) { ?>
                                                        <td><?php echo $serial_no['product_name']; ?></td>
                                                    <?php } ?>
                                                    <td><?php echo $serial_no['line_name']; ?></td>
                                                    <td><?php echo $serial_no['model_suffix']; ?></td>
                                                    <td colspan=4><?php echo $serial_no['serial_no']; ?></td>
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