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
            
    <div class="row">
        
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Sampling Plan
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/view_sampling_plan/".$plan_date; ?>">
                            <i class="fa fa-eye"></i> View Sampling Plan
                        </a>
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/view_production_plan/".$plan_date; ?>">
                            <i class="fa fa-eye"></i> View Production Plan
                        </a>
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
                                    <?php foreach(array_slice($sampling_plan, 1) as $key => $plan) { ?>
                                        <?php $id = $ids[$key]; ?>
                                        <tr>
                                            <?php foreach($plan as $k => $p) { ?>
                                                <?php if($p == 'skip') { continue; } ?>
                                                
                                                <?php if(0 === strpos($p, '<td')) { ?>
                                                    <?php if($k > 4) { ?>
                                                        <?php echo str_replace('</td>', '', $p); ?>
                                                            <small>
                                                                <a class="sampling-plan-<?php echo $id[$k-5]; ?>" href="<?php echo base_url()."sampling/sampling_plan/".$id[$k-5]; ?>" data-target="#adjust-sampling-modal" data-toggle="modal">
                                                                    ( Change )
                                                                </a>
                                                            </small>
                                                        </td>
                                                    <?php } else { ?>
                                                        <?php echo $p; ?>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <td>
                                                        <?php if($k > 4) { ?>
                                                            <?php echo $p == 'NA' ? '' :$p; ?>
                                                            <?php if($p !== null) { ?>
                                                                <small>
                                                                    <a class="sampling-plan-<?php echo $id[$k-5]; ?>" href="<?php echo base_url()."sampling/sampling_plan/".$id[$k-5]; ?>" data-target="#adjust-sampling-modal" data-toggle="modal">
                                                                        ( Change )
                                                                    </a>
                                                                </small>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <?php echo $p; ?>
                                                        <?php } ?>
                                                    </td>
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

<div class="modal fade" id="adjust-sampling-modal" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img src="<?php echo base_url(); ?>assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;Loading... </span>
            </div>
        </div>
    </div>
</div>