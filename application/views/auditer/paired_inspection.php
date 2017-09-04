<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Product Inspection | Paired Inspection Screen
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Paired Inspection Screen</li>
        </ol>
        
    </div>

    <div id="ri-suggestion-box" class="row" style="display:none;">    
        <div class="col-md-12">
            <div class="portlet light bordered" style="padding-top: 5px; padding-bottom: 5px; margin-bottom: 10px;">

                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="">
                                <label class="control-label"><b>Production Lot:</b></label>
                                <p id="ri-production-lot" class="form-control-static">
                                    --
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="">
                                <label class="control-label"><b>Sampling Plan:</b></label>
                                <p id="ri-sampling-plan" class="form-control-static">
                                    --
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="">
                                <label class="control-label"><b>Completed:</b></label>
                                <p id="ri-completed" class="form-control-static">
                                   --
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="">
                                <label class="control-label"><b>In Progress:</b></label>
                                <p id="ri-in-progress" class="form-control-static">
                                    --
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
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
        <div class="col-md-12">
        
            <div class="portlet light bordered register-inspection-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Register Paired Inspection Form
                    </div>
                </div>

                <div class="portlet-body form">
                    <form role="form" id="register-inspection-form" class="validate-form" method="post">
                        <div class="form-body">
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
                                        <label class="control-label" for="barcode">Barcode:</label>
                                        <input type="text" id="barcode-scan" class="form-control" name="barcode">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="pair_more">&nbsp;</label>
                                        <div class="checkbox-list">
                                            <label class="checkbox-inline">
                                            <input type="checkbox" name="pair_more" value="true"> Add More Serial No. </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="register-inspection-product-line-error">
                                        <label class="control-label" for="product_id">Product Line:
                                        <span class="required">*</span></label>
                                        
                                        <select id="register-inspection-line" name="line_id" class="form-control required select2me"
                                        data-placeholder="Select Product" data-error-container="#register-inspection-product-line-error">
                                            <option value=""></option>
                                            <?php foreach($lines as $line) { ?>
                                                <option value="<?php echo $line['id']; ?>">
                                                    <?php echo $line['name']; ?>
                                                </option>
                                            <?php } ?>
                                            
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="model_suffix">Model.Suffix:
                                        <span class="required">*</span></label>
                                        <!--<input type="text" id="model-master" class="required form-control" name="model_suffix" value="" readonly>-->
                                        <input type="text" id="register-inspection-model-suffix" class="required form-control" name="model_suffix" value="">
                                        
                                        <!--
                                        <div class="input-group">
                                            <input type="text" id="register-inspection-model-suffix" class="required form-control" name="model_suffix" value="">
                                            <span class="input-group-btn">
                                                <button id="register-screen-fetch-plan" type="button" class="btn red">Fetch Plan</button>
                                            </span>
                                        </div>
                                        -->
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="workorder">Workorder:
                                            <span class="required"> * </span>
                                        </label>
                                        <input type="text" id="register-inspection-workorder" class="required form-control" name="workorder">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="serial_no">Serial No.:
                                        <span class="required">*</span></label>
                                        <input type="text" id="register-inspection-serial" class="required form-control" name="serial_no" value="">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>
                                
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Register Inspection</button>
                            <a href="<?php echo base_url(); ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>