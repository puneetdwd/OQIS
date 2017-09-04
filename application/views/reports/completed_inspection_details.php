<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Completed Inspection Details
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Completed Inspection Details</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <?php if($this->session->flashdata('error')) { ?>
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
                                                <label class="control-label">Date:</label>
                                                <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                                                    <input name="date" type="text" class="required form-control" readonly
                                                    value="<?php echo $date; ?>">
                                                    <span class="input-group-btn">
                                                        <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group" id="report-sel-inspection-error">
                                                <label class="control-label">Select Inspection:</label>
                                                        
                                                <select name="inspection_id" class="required form-control select2me"
                                                    data-placeholder="Select Inspection" data-error-container="#report-sel-inspection-error">
                                                    <option></option>
                                                    <?php foreach($inspections as $inspection) { ?>
                                                        <option value="<?php echo $inspection['id']; ?>" <?php if($inspection['id'] == $insp['id']) { ?> selected="selected" <?php } ?>>
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
                                <i class="fa fa-reorder"></i>Inspections Details - <?php echo date("jS M'y", strtotime($date)); ?>, <?php echo $insp['name']; ?>
                            </div>
                            
                            <?php if(!empty($audits)) { ?>
                                <div class="actions">
                                    <?php if($this->id == $product['checked_by']) { ?>
                                        <?php if(empty($status['checked_by'])) { ?>
                                            <a class="button small" 
                                                href="<?php echo base_url()."reports/status_progress?status=Check&date=".$date.'&insp='.$insp['id'];?>">
                                                <i class="fa fa-check"></i> Check
                                            </a>
                                        <?php } else { ?>
                                            <p class="font-red-mint" style="display:inline;"> <i class="fa fa-check"></i> Checked </p>
                                        <?php } ?>
                                    <?php } ?>
                                    &nbsp;&nbsp;
                                    
                                    <?php if($this->id == $product['approved_by']) { ?>
                                        <?php if(empty($status['approved_by'])) { ?>
                                            <a class="button small" 
                                                href="<?php echo base_url()."reports/status_progress?status=Approve&date=".$date.'&insp='.$insp['id'];?>">
                                                <i class="fa fa-thumbs-up"></i> Approve
                                            </a>
                                        <?php } else { ?>
                                            <p class="font-red-mint" style="display:inline;"> <i class="fa fa-thumbs-up"></i> Approved </p>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="portlet-body">
                            <?php if(empty($audits)) { ?>
                                <p class="text-center">No Completed Inspections.</p>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Line</th>
                                                <th>Model.Suffix</th>
                                                <th>Planned</th>
                                                <th>Completed</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($audits as $audit) { ?>
                                                <tr>
                                                    <td><?php echo $audit['line']; ?></td>
                                                    <td><?php echo $audit['model_suffix']; ?></td>
                                                    <td><?php echo $audit['no_of_samples']; ?></td>
                                                    <td><?php echo $audit['completed']; ?></td>
                                                    <td>
                                                        <a class="button small gray" target="_blank"
                                                            href="<?php echo base_url()."reports/download_report/".$audit['audit_id'].'?view=true';?>">
                                                            <i class="fa fa-edit"></i> View Report
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