<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($email) ? 'Edit': 'Add'); ?> Email ID
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."emails"; ?>">Manage Email IDs</a>
            </li>
            <li class="active"><?php echo (isset($email) ? 'Edit': 'Add'); ?> Email IDs</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered user-add-form-portlet" style="width:60%;margin:0 auto">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Email ID form - <?php echo $this->session->userdata('product_name'); ?>
                    </div>
                </div>

                <div class="portlet-body form">
                    <form role="form" class="validate-form" method="post">
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

                            <?php if(isset($email['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $email['id']; ?>" />
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Name:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="name"
                                        value="<?php echo isset($email['name']) ? $email['name'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>                               
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="email">Email
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="email_id"
                                        value="<?php echo isset($email['email_id']) ? $email['email_id'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'emails'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>