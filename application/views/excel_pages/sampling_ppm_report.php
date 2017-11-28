<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Sampling PPM Reports
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Sampling PPM Reports</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <?php if($this->session->flashdata('error')) {?>
                <div class="alert alert-danger">
                   <i class="fa fa-times"></i>
                   <?php echo $this->session->flashdata('error');?>
                </div>
            <?php } else if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                    <i class="fa fa-check"></i>
                   <?php echo $this->session->flashdata('success');?>
                </div>
            <?php } ?>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-search"></i>Report Filters
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <form role="form" class="validate-form" method="post">
                                <div class="form-body" style="padding:0px;">
                                    <div class="alert alert-danger display-hide">
                                        <button class="close" data-close="alert"></button>
                                        You have some form errors. Please check below.
                                    </div>
                                
                                
                                    <?php if(isset($error)) { ?>
                                        <div class="alert alert-danger">
                                            <?php echo $error; ?>
                                        </div>
                                    <?php } ?>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Date Range</label>
                                                <div class="input-group date-picker input-daterange" data-date-format="yyyy-mm-dd">
                                                    <input type="text" class="required form-control" name="start_range" 
                                                    value="<?php echo $this->input->post('start_range'); ?>">
                                                    <span class="input-group-addon">
                                                    to </span>
                                                    <input type="text" class="required form-control" name="end_range"
                                                    value="<?php echo $this->input->post('end_range'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Report Type:
                                                <span class="required"> * </span></label>
                                                <div class="radio-list">
                                                    <?php $sel_type = $this->input->post('type'); ?>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="type" class="required" value="Monthly" <?php if($sel_type === 'Monthly') { ?>checked="checked"<?php } ?>> Monthly
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="type" class="required " value="Dialy" <?php if($sel_type === 'Dialy') { ?>checked="checked"<?php } ?>> Dialy
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="form-actions">
                                    <button class="button" type="submit">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-reorder"></i>Sampling PPM Report
                            </div>
                            
                            <div class="actions" style="margin-top: 0px;">
                                <form action="<?php echo base_url()."reports/sampling_ppm_report"?>" method="post" role="form">
                                    <input type="hidden" name="start_range" value="<?php echo $this->input->post('start_range'); ?>" />
                                    <input type="hidden" name="end_range" value="<?php echo $this->input->post('end_range'); ?>" />
                                    <input type="hidden" name="type" value="<?php echo $this->input->post('type'); ?>" />

                                    <button class="btn grey-cascade btn-sm" type="submit" name="download" value="true">
                                        <i class="fa fa-download"></i> Download
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <?php if(empty($reports)) { ?>
                                <p class="text-center">No inspection done yet.</p>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th colspan="2" class="text-center"> OQA Sample PPM </th>
                                                <?php foreach($months as $month => $v) { ?>
                                                    <th><?php echo $month; ?></th>
                                                <?php } ?>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($reports as $type => $report) { ?>
                                                <tr>
                                                    <td class="report-product-cell" rowspan="3"><?php echo $report['product_name']; ?></td>

                                                    <?php $defect_ttl = 0; ?>
                                                    <?php foreach($report['defect'] as $k => $r) { ?>
                                                        <td><?php echo $r; ?></td>
                                                        
                                                        <?php 
                                                            if($k != 'Defect QTY') { 
                                                                $defect_ttl += $r;
                                                            }
                                                        ?>
                                                    <?php } ?>
                                                    
                                                    <td><?php echo $defect_ttl; ?></td>
                                                </tr>
                                                
                                                <tr>
                                                    <?php $qty_ttl = 0; ?>
                                                    <?php foreach($report['qty'] as $k => $r) { ?>
                                                        <td><?php echo $r; ?></td>
                                                        
                                                        <?php 
                                                            if($k != 'Lot QTY') { 
                                                                $qty_ttl += $r;
                                                            }
                                                        ?>
                                                    <?php } ?>
                                                    <td><?php echo $qty_ttl; ?></td>
                                                </tr>
                                                
                                                <tr class="warning">
                                                    <?php foreach($report['perc'] as $k => $r) { ?>
                                                        <td><?php echo $r; ?></td>
                                                    <?php } ?>
                                                    <td><?php echo round((($qty_ttl-$defect_ttl)/$qty_ttl)*100, 1); ?></td>
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