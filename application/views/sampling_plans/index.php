<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Production Plans
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Manage Production Plans</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <?php if($this->session->flashdata('error')) {?>
                <div class="alert alert-danger">
                   <i class="fa fa-check"></i>
                   <?php echo $this->session->flashdata('error');?>
                </div>
            <?php } else if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                    <i class="fa fa-times"></i>
                   <?php echo $this->session->flashdata('success');?>
                </div>
            <?php } ?>

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Production Plans
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/fetch_production_plan"; ?>">
                            <i class="fa fa-refresh"></i> Fetch Today's Production Plan
                        </a>
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/upload_production_plan"; ?>">
                            <i class="fa fa-plus"></i> Upload New Production Plan
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($production_plans)) { ?>
                        <p class="text-center">No Sampling Plan.</p>
                    <?php } else { ?>
                        <div class="items">
                            <?php foreach($production_plans as $production_plan) { ?>
                            
                                <div class="sampling-item well well-sm">
                                    <h5 class="text-left" style="font-weight: bold; margin-top: 0px;">
                                        Production Plan for - <?php echo date('jS M, Y', strtotime($production_plan['plan_date'])); ?>
                                        
                                        <a class="button small gray pull-right" 
                                            href="<?php echo base_url()."sampling/view_production_plan/".$production_plan['plan_date'];?>">
                                            <i class="fa fa-eye"></i> View Plan
                                        </a>
                                    </h5>
                                </div>
                                
                            <?php } ?>
                        </div>
                    <?php } ?>
                    
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>