<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($holiday) ? 'Edit': 'Add'); ?> Holiday
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."holiday_planning"; ?>">Manage Holiday</a>
            </li>
            <li class="active"><?php echo (isset($holiday) ? 'Edit': 'Add'); ?> Holiday</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered user-add-form-portlet" style="width:60%;margin:0 auto">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Holiday form 
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

                            <?php if(isset($holiday['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $holiday['id']; ?>" />
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Name:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="name"
                                        value="<?php echo isset($holiday['name']) ? $holiday['name'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>                               
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
									<label class="control-label" for="name">Holiday Date:
                                        <span class="required">*</span></label>
										<div id="holiday-date" class="input-group date date-picker col-md-6" data-date-format="yyyy-mm-dd" data-date-end-date="+0d">
											<input id="holiday_date" name="holiday_date" type="text" class="required form-control" <?php echo isset($holiday['holiday_date']) ? "readonly" : ''; ?>
											value="<?php echo isset($holiday['holiday_date']) ? $holiday['holiday_date'] : ''; ?>">
											<span class="input-group-btn">
												<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'holiday_planning'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>