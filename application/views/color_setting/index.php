<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Color         </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Manage Color </li>
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
        
            <div class="portlet light bordered user-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Color On/Off
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

                            <?php if(isset($user['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>" />
                            <?php } 
							// print_r($color_setting);exit;
							?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
										<input type="radio" <?php if($color_setting['is_color'] == 1) echo 'checked'; ?> name="on_off_color" value="1">
                                        <label class="control-label" for="name">Turn on color indication for OK/NG
                                       
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>

                                
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
										<input type="radio" <?php if($color_setting['is_color'] == 0) echo 'checked'; ?> name="on_off_color" value="0">
                                        <label class="control-label" for="name">Turn Off color indication for OK/NG
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'color_setting'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
      
    <!-- END PAGE CONTENT-->
</div>