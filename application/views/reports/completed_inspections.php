<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Completed Inspections
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Completed Inspections</li>
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
                                <i class="fa fa-search"></i>Search
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
                                <i class="fa fa-reorder"></i>List of Completed Inspections
                            </div>
                        </div>
                        <div class="portlet-body">
                            <?php if(empty($audits)) { ?>
                                <p class="text-center">No Completed Inspections.</p>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Inspection Name</th>
                                                <th style="width:50px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($audits as $audit) { ?>
                                                <tr>
                                                    <td><?php echo date("jS M'y", strtotime($audit['sampling_date']))?></td>
                                                    <td><?php echo $audit['inspection_name']; ?></td>
                                                    <td>
                                                        <a class="button small gray" 
                                                            href="<?php echo base_url()."reports/completed_inspection_details?date=".$audit['sampling_date'].'&insp='.$audit['inspection_id'];?>">
                                                            <i class="fa fa-edit"></i> View Details
                                                        </a>
                                                    </td>
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