<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Product Inspection | View Reports
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">View Reports</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-reorder"></i>View Report
                            </div>
                        </div>
                        <div class="portlet-body">
                            <?php if(empty($top_headers)) { ?>
                                <p class="text-center">No inspection done yet.</p>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <?php foreach($top_headers as $top_header) { ?>
                                                    <th><?php echo $top_header; ?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                    
                                        <tbody>
                                            <tr>
                                                <?php foreach($top_row as $row) { ?>
                                                    <td><?php echo $row; ?></td>
                                                <?php } ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <?php foreach($headers as $k_header => $header) { ?>
                                                    <?php if($k_header <= 8 ) { ?>
                                                        <th rowspan="4" style="vertical-align: middle;">
                                                            <?php echo $sub_headers[$k_header]; ?>
                                                        </th>
                                                    <?php } else if($k_header % 3 === 0) { ?>
                                                        <th colspan="3" style="text-align:center;">
                                                            <?php echo $header; ?>
                                                        </th>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <?php foreach($wo_header as $k_wo => $wo) { ?>
                                                    <?php if($k_wo <= 8 ) { continue; } ?>
                                                    
                                                    <?php if($k_wo % 3 === 0) { ?>
                                                        <th colspan="3" style="text-align:center;">
                                                            <?php echo $wo; ?>
                                                        </th>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <?php foreach($result_header as $k_wo => $wo) { ?>
                                                    <?php if($k_wo <= 8 ) { continue; } ?>
                                                    
                                                    <?php if($k_wo % 3 === 0) { ?>
                                                        <th colspan="3" style="text-align:center;">
                                                            <?php echo $wo; ?>
                                                        </th>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <?php foreach($sub_headers as $sk_header => $sub_header) { ?>
                                                    <?php if($sk_header <= 8 ) { continue; } ?>
                                                    <th><?php echo $sub_header; ?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                    
                                        <tbody>
                                            <?php foreach($reports as $report) { ?>
                                                <tr>
                                                    <?php foreach($report as $r) { ?>
                                                        <td><?php echo $r; ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>