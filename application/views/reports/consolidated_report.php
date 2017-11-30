<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Regular & Interval Inspection Report
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Regular & Interval Inspection Report</li>
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
                                    
                                    <input type="hidden" id="page-no" name="page_no" value="1"/>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Date Range
												<span class='required'>*</span>
												</label>
                                                <div required class="input-group date-picker input-daterange" data-date-format="yyyy-mm-dd">
                                                    <input type="text" class="form-control" name="start_range" 
                                                    value="<?php echo $this->input->post('start_range'); ?>">
                                                    <span class="input-group-addon">
                                                    to </span>
                                                    <input type="text" class="form-control" name="end_range"
                                                    value="<?php echo $this->input->post('end_range'); ?>">
                                                </div>
                                            </div>
                                        </div>
										<div class="col-md-4">
                                            <div class="form-group" id="report-sel-model-error">
                                                <label class="control-label">Select Inspection Type:<span class='required'>*</span></label>
                                                <select required name="insp-type" id="insp-type" class="form-control select2me"
                                                    data-placeholder="Select Inspection Type" data-error-container="#report-sel-model-error">
                                                    <option></option>
													 <?php foreach($insp_type as $it) { ?>
													<option value="<?php echo $it['type']; ?>" <?php if($it['type'] == $this->input->post('insp-type')) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $it['type']; ?>
                                                    </option>
													 <?php } ?>
                                                </select>
                                            </div>
                                        </div>
										<div class="col-md-4">
                                            <div class="form-group" id="report-sel-inspection-error">
                                                <label class="control-label">Select Inspection:</label>
                                                        
                                                <select name="inspection_id" id="inspection_id" class="form-control select2me"
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
                                    
                                    
                                    <div class="row">
										<div class="col-md-4">
                                            <div class="form-group" id="report-sel-model-error">
                                                <label class="control-label">Select Model.Suffix:</label>
                                                <?php	
														$sel_model_suffix = (!empty($selected_model) ? $selected_model : array(0=>'All')); 
														//echo "1";print_r($sel_model_suffix);
												?>        
                                                <select multiple name="model_suffix[]" class="form-control select2me"
                                                    data-placeholder="Select Model.Suffix" data-error-container="#report-sel-model-error">
													
                                                     <?php if(empty($this->input->post())){ ?>
													<option></option>
													<?php } 
													else{ ?>
														<option value="All" <?php if($sel_model_suffix[0] == 'All'){ ?> selected="selected" <?php } ?>>All</option>
													<?php } ?> 
													
                                                    <?php foreach($models as $model) { ?>
                                                        <option value="<?php echo $model['model_suffix']; ?>" <?php 
														if(!empty($sel_model_suffix)){	
																if(in_array($model['model_suffix'], $sel_model_suffix)) { 
																	?> selected="selected" <?php 
															}
														}														
														?>
														>
                                                            <?php echo $model['model_suffix']; ?>
                                                        </option>
                                                    <?php } ?>        
                                                </select>
                                            </div>
                                        </div>
										<div class="col-md-4">
                                            <div class="form-group" id="gmes-sel-tool-error">
                                                <label class="control-label">Select Tool:</label>
                                                        
                                                <select name="tool" class="form-control select2me"
                                                    data-placeholder="Select Tool" data-error-container="#gmes-sel-tool-error">
                                                    <option></option>
                                                    <?php foreach($tools as $tool) { ?>
                                                        <option value="<?php echo $tool['tool']; ?>" <?php if($tool['tool'] == $this->input->post('tool')) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $tool['tool']; ?>
                                                        </option>
                                                    <?php } ?>        
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" id="gmes-sel-line-error">
                                                <label class="control-label">Select Line:</label>
                                                        
                                                <select name="line_id" class="form-control select2me"
                                                    data-placeholder="Select Line" data-error-container="#gmes-sel-line-error">
                                                    <option></option>
                                                    <?php foreach($lines as $line) { ?>
                                                        <option value="<?php echo $line['id']; ?>" <?php if($line['id'] == $this->input->post('line_id')) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $line['name']; ?>
                                                        </option>
                                                    <?php } ?>        
                                                </select>
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
                                <i class="fa fa-reorder"></i>Regular & Interval Inspections: 
									<?php if(!empty($audit))
										echo sizeof($audits)." Records";
									?> 
                            </div>
							
							<?php if(!empty($audits)) { ?>
								<div class="actions" style='margin: 5px;'>
									<a class="button normals btn-circle" href="<?php echo base_url()."reports/export_excel/consolidated_report"; ?>">
										<i class="fa fa-download"></i> Export Report
									</a>
								</div>					
							<?php } ?>
                        </div>
                        <div class="portlet-body">
                            <?php if(empty($audits)) { ?>
                                <p class="text-center">No inspection completed.</p>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-light" id='make-data-table'>
                                        <thead>
                                            <tr>
                                                <th>Inspector</th>
                                                <th>Start Date</th>
                                                <!--th>End Date</th-->
                                                <th>Inspection</th>
                                                <th>Line</th>
                                                <th>Model.Suffix</th>
                                                <th>Tool</th>
                                                <th>Workorder</th>
                                                <th>Serial No.</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
		       

                                            <?php foreach($audits as $audit) { ?>
                                                <tr>
                                                    <td><?php echo $audit['auditer']; ?></td>
                                                    <td nowrap><?php echo date('jS M, y', strtotime($audit['audit_date'])); ?></td>
													<!--td nowrap><?php echo date('jS M, y', strtotime($audit['audit_date'])); ?></td-->
                                                    <td><?php echo $audit['inspection_name']; ?></td>
                                                    <td><?php echo $audit['line']; ?></td>
                                                    <td><?php echo $audit['model_suffix']; ?></td>
                                                    <td><?php echo $audit['tool']; ?></td>
                                                    <td><?php echo $audit['workorder']; ?></td>
                                                    <td><?php echo $audit['serial_no']; ?></td>
                                                   
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