<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($production_plan) ? 'Edit': 'Add'); ?> Monthly Production Plan Item
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."sampling/production_plan_monthly/"; ?>">
                    Manage Production Plan Monthly
                </a>
            </li>
            <li class="active"><?php echo (isset($production_plan) ? 'Edit': 'Add'); ?> Monthly Production Plan Item</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered production_plan-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Monthly Production Plan Item Form
                    </div>
                </div>

                <div class="portlet-body form">
                    <form role="form" class="validate-form" method="post" enctype="multipart/form-data">
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

                            <?php if(isset($production_plan['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $production_plan['id']; ?>" />
                            <?php } ?>

                            <div class="row">
                                
                                <div class="col-md-6">
                                     <div class="form-group">
                                        <label class="control-label">
                                            Select Month
                                        </label>
                                        <div class="input-group date month-picker" data-date-format="yyyy-mm-dd">
                                            <input name="plan_month" type="text" class="required form-control" readonly value="">
                                            <span class="input-group-btn">
                                                <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group" id="sampling-dashboard-model-error">
                                        <label class="control-label">Select Model.Suffix:
                                        <span class="required"> * </span></label>
                                                
                                        <select name="model_suffix" id="model-sel-by-tool" class="required form-control select2me"
                                            data-placeholder="Select Model.Suffix" data-error-container="#sampling-dashboard-model-error">
                                            <option value=""></option>
                                            <?php $sel_model_suffix = $this->input->post('model_suffix'); ?>
                                            <?php foreach($model_suffixs as $model_suffix) { ?>
                                                <option value="<?php echo $model_suffix['model']; ?>" <?php if($model_suffix['model'] == $sel_model_suffix) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $model_suffix['model']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="lot_size">Lot Size:</label>
                                        <input type="text" class=" form-control" name="lot_size" value="">
                                    </div>
                                </div>
                                
                            </div>
                                
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'sampling/production_plan_monthly/'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>