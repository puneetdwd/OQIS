<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($user) ? 'Edit': 'Add'); ?> User
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."users"; ?>">Manage Users</a>
            </li>
            <li class="active"><?php echo (isset($user) ? 'Edit': 'Add'); ?> User</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered user-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> User form
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
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">First Name:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="first_name"
                                        value="<?php echo isset($user['first_name']) ? $user['first_name'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="last_name">Last Name:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="last_name"
                                        value="<?php echo isset($user['last_name']) ? $user['last_name'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="username">Username
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="username"
                                        value="<?php echo isset($user['username']) ? $user['username'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>

                                <?php if(isset($user['id'])) { ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="password">Password</label>
                                            <input type="password" class="form-control" name="password" value="">
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label" for="password">Password
                                            <span class="required">*</span></label>
                                            <input type="password" class="required form-control" name="password" value="">
                                            <span class="help-block">
                                            </span>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="user-admin-error">
                                        <label class="control-label">User Type</label>
                                        <select name="user_type" id="add-user-type-sel" class="form-control required select2me"
                                        data-placeholder="Select user type" data-error-container="#user-admin-error">
                                            <?php $user_type = isset($user['user_type']) ? $user['user_type'] : '';?>
                                            <option value=""></option>
                                            <option value="Admin" <?php if($user_type == 'Admin') { echo "selected='selected'"; } ?>>
                                                Admin User
                                            </option>
                                            <option value="Audit" <?php if($user_type == 'Audit') { echo "selected='selected'"; } ?>>
                                                Audit User
                                            </option>
                                            <!--
                                            <option value="Dashboard" <?php if($user_type == 'Dashboard') { echo "selected='selected'"; } ?>>
                                                Dashboard User
                                            </option>
                                            -->
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group" id="add-user-product-error">
                                        <label class="control-label" for="product_id">Product:
                                        <span class="required">*</span></label>
                                        
                                        <select name="product_id[]" id="add-user-product-sel" class="form-control required select2me"
                                        data-placeholder="Select Product" data-error-container="#add-user-product-error" multiple>
                                            <option value=""></option>
                                            
                                            <?php $sel_product = (!empty($user['product_id']) ? explode(',', $user['product_id']) : array()); ?>
                                            <?php foreach($products as $product) { ?>
                                                <option value="<?php echo $product['id']; ?>" 
                                                <?php if(in_array($product['id'], $sel_product)) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $product['name']; ?>
                                                </option>
                                            <?php } ?>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="email">Email
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="email"
                                        value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'users'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>