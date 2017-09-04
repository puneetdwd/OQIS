<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            View Plan - <?php echo date('jS M, Y', strtotime($plan_date)); ?>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."sampling"; ?>">
                    Manage Production Plans
                </a>
            </li>
            <li class="active">View Plan</li>
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
            <form role="form" class="form-inline" method="post" action="<?php echo base_url().'sampling/view_plan_date'; ?>">
                <div class="form-group">
                    <label class="control-label col-md-6" style="font-size: 15px; margin-top: 6px; text-align: right;">
                        Date <i class="fa fa-arrow-right"></i>
                    </label>
                    <div class="input-group date date-picker col-md-6" data-date-format="yyyy-mm-dd">
                        <input name="view_plan_date" type="text" class="required form-control" readonly
                        value="<?php echo $plan_date; ?>">
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
                        <i class="fa fa-reorder"></i>Sampling Plan
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/view_production_plan/".$plan_date; ?>">
                            <i class="fa fa-eye"></i> View Production Plan
                        </a>
                        <?php if(!empty($sampling_plan)) { ?>
                            <a class="button normals btn-circle" href="<?php echo base_url()."sampling/edit_sampling_plan/".$plan_date; ?>">
                                <i class="fa fa-plus"></i> Manage MPAT Sampling
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($sampling_plan)) { ?>
                        <p class="text-center">No Sampling Plan.</p>
                    <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <?php foreach(array_slice($sampling_plan, 0, 1) as $plan) { ?>
                                            <?php foreach($plan as $p) { ?>
                                                <th><?php echo $p; ?></th>
                                            <?php } ?>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach(array_slice($sampling_plan, 1) as $plan) { ?>
                                        <tr>
                                            <?php foreach($plan as $k => $p) { ?>
                                                <?php if($p == 'skip') { continue; } ?>
                                                
                                                <?php if(0 === strpos($p, '<td')) { ?>
                                                    <?php echo $p; ?>
                                                <?php } else { ?>
                                                    <td><?php echo $p == 'NA' ? '' :$p; ?></td>
                                                <?php } ?>
                                            <?php } ?>
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

<div class="modal fade" id="ajax" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img src="<?php echo base_url(); ?>assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;Loading... </span>
            </div>
        </div>
    </div>
</div>