<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Reference Link Checkpoints Configs
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Manage Reference Link Checkpoints Configs</li>
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
                        <i class="fa fa-reorder"></i>Reference Link Checkpoints Configs
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."references/checkpoint_config_form"; ?>">
                            <i class="fa fa-plus"></i> Add New Mandatory Checkpoint Config
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($checkpoint_configs)) { ?>
                        <p class="text-center">No Record found.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Reference Link</th>
                                    <th>Inspection Name</th>
                                    <th>Mandatory Checkpoint NOs</th>
                                    <th class="no_sort" style="width:200px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($checkpoint_configs as $checkpoint_config) { ?>
                                    <tr>
                                        
                                        <td><?php echo $checkpoint_config['reference_link']; ?></td>
                                        <td><?php echo $checkpoint_config['inspection_name']; ?></td>
                                        <td><?php echo $checkpoint_config['checkpoints_nos']; ?></td>
                                        <td nowrap>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."references/checkpoint_config_form/".$checkpoint_config['id'];?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <a class="btn btn-outline btn-xs sbold red-thunderbird" data-confirm="Are you sure you want to this record?" 
                                                href="<?php echo base_url()."references/delete_checkpoint_config/".$checkpoint_config['id'];?>">
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