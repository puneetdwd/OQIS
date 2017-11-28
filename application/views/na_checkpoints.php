<div class="page-content">
    <div class="breadcrumbs">
        <h1>
            NA Checkpoints Dashboard
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">NA Checkpoints Dashboard</li>
        </ol>
        
    </div>
        
    <?php if($this->session->flashdata('error')) {?>
        <div class="alert alert-danger">
           <i class="icon-remove"></i>
           <?php echo $this->session->flashdata('error');?>
        </div>
    <?php } else if($this->session->flashdata('success')) { ?>
        <div class="alert alert-success">
            <i class="icon-ok"></i>
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
                                                <label class="control-label">Select Inspection Type:</label>
                                                <select name="insp-type" id="insp-type" class="form-control select2me"
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
                                                            <?php echo $inspection['name'];//ucfirst($inspection['insp_type']) ." - ". 
															?>
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
													<option ></option>
													<?php } 
													else{ ?>
														<option value="All" <?php if($sel_model_suffix[0] == 'All'){ ?> selected="selected" <?php } ?>>All</option>
													<?php } ?>
                                                    <?php foreach($models as $model) { ?>
                                                        <option value="<?php echo $model['model_suffix']; ?>" 
														<?php 
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
										<!--div class="col-md-4">
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
                                        </div-->
                                   
                                        
                                    </div>
                                
                                <div class="form-actions">
                                    <button class="button" type="submit">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
			</div>
			</div>
    <div class="row">
        <div class="col-md-12">
           <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>List of Pending NA Checkpoints
                    </div>
					<?php if(!empty($audit_checkpoints)) { ?>
							<div class="actions" style='margin: 5px;'>
								<a class="button normals btn-circle" href="<?php echo base_url()."reports/export_excel/audit_checkpoints"; ?>">
									<i class="fa fa-download"></i> Export Report
								</a>
							</div>					
						<?php } ?>
                </div>
                <div class="portlet-body">
                    <div class="table-responsive">
                        <?php if(empty($audit_checkpoints)) { ?>
                            <p class="text-center">No pending checkpoint.</p>
                        <?php } else { ?>
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Inspection Date</th>
                                        <th>Inspection</th>
                                        <th>Model.Suffix</th>
                                        <th>Serial No</th>
                                        <th>Checkpoint No</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php foreach($audit_checkpoints as $audit_checkpoint) { ?>
                                            <tr>
                                                <td><?php echo date('jS M, Y', strtotime($audit_checkpoint['audit_date'])); ?></td>
                                                <td><?php echo $audit_checkpoint['inspection_name']; ?></td>
                                                <td><?php echo $audit_checkpoint['model_suffix']; ?></td>
                                                <td><?php echo $audit_checkpoint['serial_no']; ?></td>
                                                <td><?php echo $audit_checkpoint['checkpoint_no']; ?></td>
                                                <td class="hide-till-load" style="display:none;">
                                                    <a class="button small notification-<?php echo $audit_checkpoint['id']; ?>" href="<?php echo base_url()."dashboard/view_notification/".$audit_checkpoint['id'].'/direct'; ?>" data-target="#notification-detail-modal" data-toggle="modal">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<div class="modal fade" id="notification-detail-modal" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img src="<?php echo base_url(); ?>assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;Loading... </span>
            </div>
        </div>
    </div>
</div>

<script>
    $(window).load(function() {
        $('.hide-till-load').show();
    });
</script>