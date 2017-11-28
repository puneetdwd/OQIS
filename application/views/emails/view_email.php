<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            View Email ID
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."emails"; ?>">Manage Email ID</a>
            </li>
            <li class="active">View Email ID</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> View Email ID - <?php echo $this->session->userdata('product_name'); ?>  <?php //echo $email['name']; ?>
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
                                                <?php echo $email['name']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>
                            <div class="row">
                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Email ID:</label>
                                        <div class="col-md-8">
                                            <p class="form-control-static">
                                                <?php echo $email['email_id']; ?>
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
                                            <a href="<?php echo base_url().'emails'; ?>" class="button white">
                                                <i class="m-icon-swapleft"></i> Back 
                                            </a>

                                            <a class="button" 
                                                href="<?php echo base_url()."emails/add/".$email['id'];?>">
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