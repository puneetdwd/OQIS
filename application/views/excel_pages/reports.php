<html>
<body>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
	<table>
        <tr>
			<td colspan="5">
				<h1>Product Inspection | Reports</h1>
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

            <div class="row">
                
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        
                        <div class="portlet-body">
                            
                                <div class="table-responsive">
                                    <table class="table table-hover table-light" border="1" style="width:100%;border-collapse:collapse">
                                        <thead>
                                            <tr style="background-color:'#C0C0C0';color:'#000'">
                                                <th>Inspect Date</th>
                                                <th>Inspection</th>
                                                <?php if(!$this->product_id) { ?>
                                                    <th>Product</th>
                                                <?php } ?>
                                                <th>Model.Suffix/Tool</th>
                                                <th>Planned Samples</th>
                                                <th>Completed Samples</th>
                                                <th>Final Judgement</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($audits as $audit) { ?>
                                                <tr>
                                                    <td><?php echo date('d-m-Y', strtotime($audit['audit_date'])); ?></td>
                                                    <td><?php echo $audit['inspection_name']; ?></td>
                                                    <?php if(!$this->product_id) { ?>
                                                        <td><?php echo $audit['product_name']; ?></td>
                                                    <?php } ?>
                                                    <td><?php echo $audit['model_suffix']; ?></td>
                                                    <td><?php echo $audit['no_of_samples']; ?></td>
                                                    <td><?php echo $audit['total_audits']; ?></td>
                                                    <td>
                                                        <?php if(isset($audit['no_of_samples']) && $audit['total_audits'] >= $audit['no_of_samples']) {
                                                            echo ($audit['checkpoint_count'] === $audit['correct_count']) ? 'OK' : 'NG';
                                                        } else {
                                                            echo "Pending";
                                                        } ?>
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