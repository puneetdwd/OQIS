<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Product Status
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Product Status</li>
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
                                        <div class="col-md-5">
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
                                        
                                        <div class="col-md-5">
                                            <div class="form-group" id="report-sel-product-error">
                                                <label class="control-label">Select Product:</label>
                                                        
                                                <select name="product_id" class="required form-control select2me"
                                                    data-placeholder="Select Product" data-error-container="#report-sel-product-error">
                                                    <option></option>
                                                    <?php foreach($products as $product) { ?>
                                                        <option value="<?php echo $product['id']; ?>" <?php if($product['id'] == $this->input->post('product_id')) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $product['name']; ?>
                                                        </option>
                                                    <?php } ?>        
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">&nbsp;</label><br />
                                                <button class="button" type="submit">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                    
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
                                <i class="fa fa-reorder"></i>Product Status
                            </div>
                        </div>
                        <div class="portlet-body">
                            <?php if(empty($reports)) { ?>
                                <p class="text-center">No Status</p>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th class="text-center"> Inspection </th>
                                                <?php foreach($months as $month => $v) { ?>
                                                    <th><?php echo $month; ?></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($reports as $type => $report) { ?>
                                                <tr>
                                                    <td><?php echo $report['inspection_name']; ?></td>
                                                    
                                                    <?php foreach($report as $key => $stat) { ?>
                                                        <?php if($key == 'inspection_name') { continue; } ?>
                                                        
                                                        <?php
                                                            if($stat == 'CheckedApproved') {
                                                                $color = "#26C281";
                                                                $content = '<i class="fa fa-check"></i>&nbsp;&nbsp;<i class="fa fa-thumbs-up"></i>';
                                                            } else if($stat == 'Checked') {
                                                                $color = "#F3C200";
                                                                $content = '<i class="fa fa-check"></i>';
                                                            } else if($stat == 'Approved') {
                                                                $color = "#F3C200";
                                                                $content = '<i class="fa fa-thumbs-up"></i>';
                                                            } else {
                                                                $color = "#E35B5A";
                                                                $content = '<i class="fa fa-remove"></i>';
                                                            }
                                                        ?>
                                                        <td class="text-center merged-cell" style="background-color:<?php echo $color; ?>;color:#FFFFFF;">
                                                            <?php echo $content; ?>
                                                        </td>
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