<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Edit Report
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Edit Report</li>
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
                                <i class="fa fa-search"></i>Search Report
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
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">Date Range</label>
                                                <div class="input-group date-picker input-daterange" data-date-format="yyyy-mm-dd">
                                                    <input type="text" class="form-control" name="start_range" 
                                                    value="<?php echo $this->input->post('start_range'); ?>">
                                                    <span class="input-group-addon">
                                                    to </span>
                                                    <input type="text" class="form-control" name="end_range"
                                                    value="<?php echo $this->input->post('end_range'); ?>">
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
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
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
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group" id="gmes-sel-workorder-error">
                                                <label class="control-label">Select Workorder:</label>
                                                        
                                                <select name="workorder" class="form-control select2me"
                                                    data-placeholder="Select Workorder" data-error-container="#gmes-sel-workorder-error">
                                                    <option></option>
                                                    <?php foreach($workorders as $workorder) { ?>
                                                        <option value="<?php echo $workorder['workorder']; ?>" <?php if($workorder['workorder'] == $this->input->post('workorder')) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $workorder['workorder']; ?>
                                                        </option>
                                                    <?php } ?>        
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group" id="gmes-sel-serial-no-error">
                                                <label class="control-label">Select Serial No:</label>
                                                        
                                                <select name="serial_no" class="form-control select2me"
                                                    data-placeholder="Select Serial No" data-error-container="#gmes-sel-serial-no-error">
                                                    <option></option>
                                                    <?php foreach($serial_nos as $serial_no) { ?>
                                                        <option value="<?php echo $serial_no['serial_no']; ?>" <?php if($serial_no['serial_no'] == $this->input->post('serial_no')) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $serial_no['serial_no']; ?>
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
                                <div class="pagination-sec pull-right"></div>
                                <div style="clear:both;"></div>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover table-light">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Inspection</th>
                                                <th>Workorder</th>
                                                <th>Line</th>
                                                <th>Model.Suffix</th>
                                                <th>Tool</th>
                                                <th>Serial No.</th>
                                                <th>Judgement</th>
                                                <th class="no_sort"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($audits as $audit) { ?>
                                                <tr>
                                                    <td nowrap><?php echo date('jS M, y', strtotime($audit['audit_date'])); ?></td>
                                                    <td><?php echo $audit['inspection_name']; ?></td>
                                                    <td><?php echo $audit['workorder']; ?></td>
                                                    <td><?php echo $audit['line']; ?></td>
                                                    <td><?php echo $audit['model_suffix']; ?></td>
                                                    <td><?php echo $audit['tool']; ?></td>
                                                    <td><?php echo $audit['serial_no']; ?></td>
                                                    <td class="judgement-col">
                                                        <img src="<?php echo base_url(); ?>assets/global/img/loading-spinner-grey.gif" alt="" class="loading" style="display:none;">
                                                    </td>
                                                    <td nowrap>
                                                        <a class="check-judgement-button button small gray" href="<?php echo base_url().'reports/check_judgement/'.$audit['id']; ?>" data-id="<?php echo $audit['id']; ?>" style="display:none;"> 
                                                            check
                                                        </a>
                                                        <a class="button small gray" 
                                                            href="<?php echo base_url()."auditer/finish_screen/".$audit['id'];?>">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                        
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="pagination-sec pull-right"></div>
                                <div style="clear:both;"></div>
                            <?php } ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

<?php if(!empty($audits)) { ?>
    <script>
        $(window).load(function() {
            $('.check-judgement-button:first').trigger('click');
            
            $('.pagination-sec').bootpag({
                total: <?php echo $total_page; ?>,
                page: <?php echo $page_no; ?>,
                maxVisible: 5,
                leaps: true,
                firstLastUse: true,
                first: '←',
                last: '→',
                wrapClass: 'pagination',
                activeClass: 'active',
                disabledClass: 'disabled',
                nextClass: 'next',
                prevClass: 'prev',
                lastClass: 'last',
                firstClass: 'first'
            }).on("page", function(event, num){
                show_page(num); // or some ajax content loading...
            }); 
        });
    </script>
<?php } ?>