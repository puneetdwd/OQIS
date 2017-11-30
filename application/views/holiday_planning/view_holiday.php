<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            View Holiday
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."holiday_planning"; ?>">Manage Holiday</a>
            </li>
            <li class="active">View Holiday</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> View Holiday - <?php echo $holiday['name']; ?>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form class="form-horizontal" role="form">
                        <div class="form-body">
                            <div class="row">                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Name:</label>
                                        <div class="col-md-8">
                                            <p class="form-control-static">
                                                <?php echo $holiday['name']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <div class="row">
                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Holiday:</label>
                                        <div class="col-md-8">
                                            <p class="form-control-static">
                                                <?php echo $holiday['holiday_date']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                           
                        <div class="form-actions fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <a href="<?php echo base_url().'holiday_planning'; ?>" class="button white">
                                                <i class="m-icon-swapleft"></i> Back 
                                            </a>

                                            <a class="button" 
                                                href="<?php echo base_url()."holiday_planning/add/".$holiday['id'];?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- END FORM-->
                </div>
            </div>
            
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>