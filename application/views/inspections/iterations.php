<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Iterations - <?php echo $inspection['name']; ?>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Manage Iterations</li>
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
                        <i class="fa fa-reorder"></i>Iterations
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."inspections/add_iteration/".$inspection['id']; ?>">
                            <i class="fa fa-plus"></i> Add Iteration
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($iterations)) { ?>
                        <p class="text-center">No Record found.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Iteration No</th>
                                    <th>Checkpoint NOs</th>
                                    <th>Iteration Duration</th>
                                    <th class="no_sort" style="width:200px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($iterations as $iteration) { ?>
                                    <tr>
                                        <td><?php echo $iteration['iteration_no']; ?></td>
                                        <td><?php echo $iteration['checkpoints_nos']; ?></td>
                                        <td><?php echo $iteration['iteration_time'].' '.$iteration['iter_time_type']; ?></td>
                                        <td nowrap>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."inspections/add_iteration/".$inspection['id'].'/'.$iteration['id'];?>">
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