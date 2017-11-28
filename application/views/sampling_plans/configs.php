<style>
    .hiddenRow {
        padding:0px !important;
    }
    .hiddenRow .row {
        padding:8px !important;
    }
    .hiddenRow .form-group {
        margin-bottom:0px;
    }
</style>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    
    <div class="breadcrumbs">
        <h1>
            Sampling Configuration
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Sampling Configuration</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    
    <div class="row">
        <div class="col-md-3">
        
            <div class="portlet light bordered sampling-dashboard-search-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Filters
                    </div>
                </div>

                <div class="portlet-body form">
                    <form role="form" class="validate-form" method="post">
                        <div class="form-body">
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                            </div>

                            <?php if(isset($error)) { ?>
                                <div class="alert alert-danger">
                                    <i class="fa fa-times"></i>
                                    <?php echo $error; ?>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="sampling-dashboard-inspection-error">
                                        <label class="control-label">Select Inspection:
                                            <span class="required"> * </span>
                                        </label>
                                                
                                        <select name="inspection_id" class="form-control required select2me"
                                            data-placeholder="Select Inspection" data-error-container="#sampling-dashboard-inspection-error">
                                            <option value="All">All</option>
                                            <?php $sel_inspection = $this->input->post('inspection_id'); ?>
                                            <?php foreach($inspections as $inspection) { ?>
                                                <option value="<?php echo $inspection['id']; ?>"
                                                <?php if($sel_inspection == $inspection['id']) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $inspection['name']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group" id="sampling-dashboard-line-error">
                                        <label class="control-label">Select Line:
                                        <span class="required"> * </span></label>
                                                
                                        <select name="line" class="required form-control select2me"
                                            data-placeholder="Select Line" data-error-container="#sampling-dashboard-line-error">
                                            <option value="All">All</option>
                                            <?php $sel_line = $this->input->post('line'); ?>
                                            <?php foreach($lines as $line) { ?>
                                                <option value="<?php echo $line['id']; ?>" <?php if($line['id'] == $sel_line) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $line['name']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group" id="sampling-dashboard-tool-error">
                                        <label class="control-label">Select Tool:
                                        <span class="required"> * </span></label>
                                                
                                        <select name="tool" id="tool-wise-model-sel" class="required form-control select2me"
                                            data-placeholder="Select Tool" data-error-container="#sampling-dashboard-tool-error">
                                            <option value="All">All</option>
                                            <?php $sel_tool = $this->input->post('tool'); ?>
                                            <?php foreach($tools as $tool) { ?>
                                                <option value="<?php echo $tool['tool']; ?>" <?php if($tool['tool'] == $sel_tool) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $tool['tool']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group" id="sampling-dashboard-model-error">
                                        <label class="control-label">Select Model.Suffix:
                                        <span class="required"> * </span></label>
                                        <?php //print_r();exit; ?>
                                        <?php	
												$sel_model_suffix = (!empty($selected_model) ? $selected_model : 'All'); 
												//print_r($selected_model);exit;
										?> 
										
										<select multiple name="model_suffix[]" id="model-sel-by-tool" class="required form-control select2me"
                                            data-placeholder="Select Model.Suffix" data-error-container="#sampling-dashboard-model-error">
                                            <?php if(empty($this->input->post())){ ?>
												<option value="All" >All</option>
											<?php } 
											if(!empty($this->input->post())){ ?>
												<option value="All" <?php if($sel_model_suffix[0] == 'All'){ ?> selected="selected" <?php } ?>>All</option>
											<?php } ?>
                                            <?php foreach($model_suffixs as $model_suffix) { ?>
                                                <option value="<?php echo $model_suffix['model']; ?>" 
													<?php 
													if(!empty($this->input->post())){	
															if(in_array($model_suffix['model'], $sel_model_suffix)) { 
																?> selected="selected" <?php 
														}
													}														
													?>
												>
                                                    <?php echo $model_suffix['model']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Configurations
                    </div>
                    
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/update_inspection_config"; ?>">
                            <i class="fa fa-plus"></i> Add Inspection Configuration
                        </a>
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/sort_inspections"; ?>">
                            <i class="fa fa-plus"></i> Sort Inspections
                        </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form class="form-horizontal" role="form">
                        <div class="form-body">
                            <?php if(!empty($configs)) { ?>
                                <table class="table table-hover table-light">
                                    <thead>
                                        <tr>
                                            <th>Inspection Name</th>
                                            <th>Config Type</th>
                                            <th>Line</th>
                                            <th>Tool</th>
                                            <th>Model.Suffix</th>
                                            <th>Sampling Type</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                
                                    <tbody>
                                        <?php foreach($configs as $key => $config) { ?>
                                            <tr>
                                                <td><?php echo $config['inspection_name']; ?></td>
                                                <td><?php echo $config['inspection_type']; ?></td>
                                                <td><?php echo empty($config['line_name']) ? 'All' : $config['line_name']; ?></td>
                                                <td><?php echo empty($config['tool']) ? 'All' : $config['tool']; ?></td>
                                                <td><?php echo empty($config['model_suffix']) ? 'All' : $config['model_suffix']; ?></td>
                                                <td><?php echo $config['sampling_type']; ?></td>
                                                <td nowrap>
                                                    <button type="button" class="btn btn-default btn-xs accordion-toggle" data-toggle="collapse" data-target="#detail-<?php echo $key; ?>">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                    <a class="button small gray" href="<?php echo base_url()."sampling/update_inspection_config/".$config['id'];?>">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <a class="btn btn-xs btn-outline sbold red-thunderbird" data-confirm="Are you sure you want to this Model.Suffix?"
                                                        href="<?php echo base_url()."sampling/delete_config/".$inspection['id']."/".$config['id'];?>">
                                                        <i class="fa fa-trash-o"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" class="hiddenRow">
                                                    <div class="accordian-body collapse row" id="detail-<?php echo $key; ?>">
                                                        <?php if($config['sampling_type'] == 'Auto') { ?>

                                                            <div class="col-md-3 col-md-offset-3">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-5"><b>Inspection Level:</b></label>
                                                                    <div class="col-md-7">
                                                                        <p class="form-control-static">
                                                                            <b><?php echo $config['inspection_level']; ?></b>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-5">Acceptable Quality:</label>
                                                                    <div class="col-md-7">
                                                                        <p class="form-control-static">
                                                                            <?php echo $config['acceptable_quality']; ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } else if($config['sampling_type'] == 'Interval') { ?>

                                                            <div class="col-md-3 col-md-offset-3">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-5"><b>Frequency:</b></label>
                                                                    <div class="col-md-7">
                                                                        <p class="form-control-static">
                                                                            <b><?php echo $config['no_of_months']; ?></b>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-5"><b># times:</b></label>
                                                                    <div class="col-md-7">
                                                                        <p class="form-control-static">
                                                                            <b><?php echo $config['no_of_times']; ?></b>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    
                                                    
                                                        <?php if($config['sampling_type'] == 'User Defined' || $config['sampling_type'] == 'Interval') { ?>
                                                            <div class="row" style="margin: 0px 8px;">
                                                                <div class="col-md-6">
                                                                    <table class="table table-hover table-light">
                                                                        <thead>
                                                                            <tr>
                                                                                <th colspan="3" class="text-center">Lot Range</th>
                                                                                <th># of Samples</th>
                                                                            </tr>
                                                                        </thead>
                                                                        
                                                                        <tbody>
                                                                            <?php foreach(array_slice($config['lots'], 0, ceil(count($config['lots'])/2)) as $lot) { ?>
                                                                                <tr>
                                                                                    <td><?php echo $lot['lower_val']; ?></td>
                                                                                    <td>to</td>
                                                                                    <td><?php echo ($lot['higher_val']) ? $lot['higher_val'] : 'over'; ?></td>
                                                                                    <td><?php echo $lot['no_of_samples']; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                
                                                                <div class="col-md-6">
                                                                    <table class="table table-hover table-light">
                                                                        <thead>
                                                                            <tr>
                                                                                <th colspan="3" class="text-center">Lot Range</th>
                                                                                <th># of Samples</th>
                                                                            </tr>
                                                                        </thead>
                                                                        
                                                                        <tbody>
                                                                            <?php foreach(array_slice($config['lots'], ceil(count($config['lots'])/2)) as $lot) { ?>
                                                                                <tr>
                                                                                    <tr>
                                                                                        <td><?php echo $lot['lower_val']; ?></td>
                                                                                        <td>To</td>
                                                                                        <td><?php echo ($lot['higher_val']) ? $lot['higher_val'] : 'over'; ?></td>
                                                                                        <td><?php echo $lot['no_of_samples']; ?></td>
                                                                                    </tr>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    
                                </table>
                            <?php } else { ?>
                                <p class="text-center">No Configs found.</p>
                            <?php } ?>
                        </div>
                    </form>
                    <!-- END FORM-->
                </div>
            </div>
            
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>