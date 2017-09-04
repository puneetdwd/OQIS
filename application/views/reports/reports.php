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
                                                <label class="control-label">Inspect Date:</label>
                                                <div class="input-group date date-picker" data-date-format="yyyy-mm-dd">
                                                    <input name="audit_date" type="text" class="form-control" readonly
                                                    value="<?php echo $this->input->post('audit_date'); ?>">
                                                    <span class="input-group-btn">
                                                        <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
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
                                <i class="fa fa-reorder"></i>List of Inspections
                            </div>
                        </div>
                        <div class="portlet-body">
                            <?php if(empty($audits)) { ?>
                                <p class="text-center">No inspection done yet.</p>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-light">
                                        <thead>
                                            <tr>
                                                <th>Inspect Date</th>
                                                <th>Inspection</th>
                                                <?php if(!$this->product_id) { ?>
                                                    <th>Product</th>
                                                <?php } ?>
                                                <th>Model.Suffix/Tool</th>
                                                <th>Planned Samples</th>
                                                <th>Completed Samples</th>
                                                <th>Final Judgement</th>
                                                <th class="no_sort" style="width:150px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($audits as $audit) { ?>
                                                <tr>
                                                    <td><?php echo date('jS M, Y', strtotime($audit['audit_date'])); ?></td>
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
                                                    <td nowrap>
                                                        <?php if($this->product_id == 6 && $audit['automate_file'] != ''){ ?>
                                                            <br />
                                                            <a class="button small gray" 
                                                                href="<?php echo base_url().$audit['automate_file'];?>">
                                                                <i class="fa fa-edit"></i> Download Report
                                                            </a>
                                                        <?php }else{ ?>
                                                        <a class="button small gray" 
                                                            href="<?php echo base_url()."reports/download_report/".$audit['id'].'?view=true';?>"
                                                            target="_blank">
                                                            <i class="fa fa-edit"></i> View Report
                                                        </a>
                                                            
                                                        <?php if(isset($audit['no_of_samples']) && $audit['total_audits'] >= $audit['no_of_samples']) { ?>
                                                            <br />
                                                            <a class="button small gray" 
                                                                href="<?php echo base_url()."reports/download_report/".$audit['id'];?>">
                                                                <i class="fa fa-edit"></i> Download Report
                                                            </a>
                                                        <?php } ?>
                                                        <?php } ?>
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