<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Emails
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Masters</li>
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

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>List of Emails
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."emails/add"; ?>">
                            <i class="fa fa-plus"></i> Add New Email IDs
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($emails)) { ?>
                        <p class="text-center">No Email exist yet.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email IDs</th>
                                   
                                    <th class="no_sort" style="width:150px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($emails as $email) { ?>
                                    <tr>
                                        <td><?php echo $email['name']; ?></td>
                                        <td><?php echo $email['email_id']; ?></td>
                                        <td nowrap>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."emails/add/".$email['id'];?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."emails/view/".$email['id'];?>">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                            
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>