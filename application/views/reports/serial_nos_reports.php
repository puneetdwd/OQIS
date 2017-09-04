<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Product Inspection | Reports
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Reports</li>
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
                <div class="col-md-3">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-search"></i>Search Reports
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
                                        <div class="col-md-12">
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
                                    </div>
                                    
                                    <?php if(!$this->product_id) { ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group" id="report-sel-product-error">
                                                    <label class="control-label">Select Product:</label>
                                                            
                                                    <select name="product_id" class="form-control select2me"
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
                                        </div>
                                    <?php } ?>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group" id="report-sel-model-error">
                                                <label class="control-label">Select Model.Suffix:</label>
                                                        
                                                <select name="model_suffix" class="form-control select2me"
                                                    data-placeholder="Select Model.Suffix" data-error-container="#report-sel-model-error">
                                                    <option></option>
                                                    <?php foreach($models as $model) { ?>
                                                        <option value="<?php echo $model['model_suffix']; ?>" <?php if($model['model_suffix'] == $this->input->post('model_suffix')) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $model['model_suffix']; ?>
                                                        </option>
                                                    <?php } ?>        
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group" id="report-sel-inspection-error">
                                                <label class="control-label">Select Inspection:</label>
                                                        
                                                <select name="inspection_id" class="form-control select2me"
                                                    data-placeholder="Select Inspection" data-error-container="#report-sel-inspection-error">
                                                    <option></option>
                                                    <?php foreach($inspections as $inspection) { ?>
                                                        <option value="<?php echo $inspection['id']; ?>" <?php if($inspection['id'] == $this->input->post('inspection_id')) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $inspection['name']; ?>
                                                        </option>
                                                    <?php } ?>        
                                                </select>
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
                
                <div class="col-md-9">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-reorder"></i>List of Serial NOs
                            </div>
                        </div>
                        <div class="portlet-body">
                            <?php if(empty($serial_nos)) { ?>
                                <p class="text-center">No inspection done yet.</p>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-light" id="make-data-table">
                                        <thead>
                                            <tr>
                                                <?php if(!$this->product_id) { ?>
                                                    <th>Product</th>
                                                <?php } ?>
                                                <th>Line</th>
                                                <th>Model.Suffix</th>
                                                <th>Serial NOs</th>
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
                                                    <td><?php echo $serial_no['serial_no']; ?></td>
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