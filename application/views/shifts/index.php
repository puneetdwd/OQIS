<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Shifts
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Manage Shifts</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-offset-2 col-md-8">

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
                        <i class="fa fa-reorder"></i>
                        Shifts
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."shifts/add_shift"; ?>">
                            <i class="fa fa-plus"></i> Add Shift
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($shifts)) { ?>
                        <p class="text-center">No items exists yet.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($shifts as $shift) { ?>
                                    <tr>
                                        <td><?php echo $shift['name']; ?></td>
                                        <td><?php echo $shift['detail']; ?></td>
                                        <td nowrap>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."shifts/add_shift/".$shift['id'];?>">
                                                <i class="fa fa-edit"></i> Edit
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