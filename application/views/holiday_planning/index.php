<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Holidays
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
                        <i class="fa fa-reorder"></i>List of Holidays
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."holiday_planning/add"; ?>">
                            <i class="fa fa-plus"></i> Add New Holiday
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($holidays)) { ?>
                        <p class="text-center">No Holiday exist yet.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Date</th>
                                   
                                    <th class="no_sort" style="width:150px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($holidays as $holiday) { ?>
                                    <tr>
                                        <td><?php echo $holiday['name']; ?></td>
                                        <td><?php echo $holiday['holiday_date']; ?></td>
                                        <td nowrap>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."holiday_planning/add/".$holiday['id'];?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."holiday_planning/view/".$holiday['id'];?>">
                                                <i class="fa fa-eye"></i> View
                                            </a>
											<a class="btn btn-outline btn-xs sbold red" 
                                                href="<?php echo base_url()."holiday_planning/delete_holiday/".$holiday['id'];?>">
                                                <i class="fa fa-trash-o"></i> Delete
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