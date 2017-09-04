<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Production Plan - <?php echo date('M, Y', strtotime($plan_month)); ?>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Production Plan Monthly</li>
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
        </div>
    </div>
           
    <div class="row" style="margin-bottom:10px;">
        <div class="col-md-5 col-md-offset-7 text-right">
            <form role="form" class="form-inline" method="post">
                <div class="form-group">
                    <label class="control-label col-md-6" style="font-size: 15px; margin-top: 6px; text-align: right;">
                        Month <i class="fa fa-arrow-right"></i>
                    </label>
                    <div class="input-group date month-picker col-md-6" data-date-format="yyyy-mm-dd">
                        <input name="plan_month" type="text" class="required form-control" readonly
                        value="<?php echo $plan_month; ?>">
                        <span class="input-group-btn">
                            <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                
                <button class="button" type="submit">Search</button>
            </form>
        </div>    
    </div>
           
    <div class="row">
        
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Monthly Production Plan - <?php echo date('M, Y', strtotime($plan_month)); ?>
                    </div>
                    
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/upload_production_plan_monthly/"; ?>">
                            <i class="fa fa-plus"></i> Upload Production Plan Monthly
                        </a>
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/add_production_plan_monthly/"; ?>">
                            <i class="fa fa-plus"></i> Add another Model.Suffix
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($plans)) { ?>
                        <p class="text-center">No Production Plan.</p>
                    <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <td>Tool</td>
                                        <td>Model.Suffix</td>
                                        <td>Lot Size</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($plans as $plan) { ?>
                                        <tr>
                                            <td><?php echo $plan['tool']; ?></td>
                                            <td><?php echo $plan['model_suffix']; ?></td>
                                            <td><?php echo $plan['lot_size']; ?></td>
                                            <td>
                                                <a class="btn btn-outline btn-xs sbold red" href="<?php echo base_url()."sampling/delete_production_plan_monthly/".$plan['id'];?>" data-confirm="Are you sure you want to delete this line item?">
                                                    <i class="fa fa-trash-o"></i> Delete
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
    <!-- END PAGE CONTENT-->
</div>