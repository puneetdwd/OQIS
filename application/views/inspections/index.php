<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Inspections
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Manage Inspections</li>
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
                        <i class="fa fa-reorder"></i>Inspections
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."inspections/add_inspection"; ?>">
                            <i class="fa fa-plus"></i> Add New Inspections
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($inspections)) { ?>
                        <p class="text-center">No Inspections.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <?php if(!$this->product_id) { ?>
                                        <th>Product</th>
                                    <?php } ?>
                                    <th>GMES INSP ID</th>
                                    <th>Inspection Name</th>
                                    <th>No. of Checkpoints</th>
                                    <th>Insp Item level</th>
                                    <th>Inspection Type</th>
                                    <th>Automate</th>
                                    <th class="no_sort" style="width:200px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($inspections as $inspection) { ?>
                                    <tr class="<?php if(!$inspection['is_active']) { ?>danger<?php } ?>">
                                        <?php if(!$this->product_id) { ?>
                                            <td><?php echo $inspection['product']; ?></td>
                                        <?php } ?>
                                        <td><?php echo $inspection['gmes_insp_id']; ?></td>
                                        <td><?php echo $inspection['name']; ?></td>
                                        <td><?php echo empty($inspection['full_auto']) ? $inspection['checkpoints_count'] : 'NA'; ?></td>
                                        <td><?php echo empty($inspection['full_auto']) ? $inspection['checkpoint_format'].' Level' : 'NA'; ?></td>
                                        <td><?php echo ucwords($inspection['insp_type']); ?></td>
                                        <td>
                                            <?php 
                                                if(!empty($inspection['full_auto'])) {
                                                    echo "FULL, ".$inspection['automate_case'];
                                                } else if(!empty($inspection['automate_result'])) {
                                                    echo "PARTIAL";
                                                } else if(!empty($inspection['attach_report'])) {
                                                    echo "Report Attach";
                                                }
                                            ?>
                                        </td>
                                        <td nowrap>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."inspections/add_inspection/".$inspection['id'];?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <?php if(empty($inspection['full_auto']) || $inspection['automate_case'] == 'With Checkpoints') { ?>
                                                <?php if(empty($inspection['attach_report'])) { ?>
                                                    <a class="button small gray" 
                                                        href="<?php echo base_url()."inspections/checkpoints/".$inspection['id'];?>">
                                                        <i class="fa fa-eye"></i> Checkpoints
                                                    </a>
                                                <?php } ?>
                                            <?php } ?>
                                            <br />
                                            <a style="margin-top:10px;" class="btn btn-outline btn-xs sbold red-thunderbird" data-confirm="Are you sure you want to this inspection?" href="<?php echo base_url()."inspections/delete_inspection/".$inspection['id'];?>">
                                                <i class="fa fa-trash-o"></i> Delete
                                            </a>
                                            <a style="margin-top:10px;" class="btn btn-outline btn-xs sbold red-thunderbird" data-confirm="Are you sure you want to mark this inspection as <?php echo $inspection['is_active'] ? 'inactive' : 'active';?>?" 
                                                href="<?php echo base_url()."inspections/status/".$inspection['id'].'/'.($inspection['is_active'] ? 'inactive' : 'active' );?>">
                                                <i class="fa fa-trash-o"></i> <?php echo $inspection['is_active'] ? 'Mark Inactive' : 'Mark Active';?>
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