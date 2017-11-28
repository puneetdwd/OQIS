<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Reference Links
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Manage Reference Links</li>
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
                        <i class="fa fa-reorder"></i>Reference Links
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."references/checkpoint_configs"; ?>">
                            <i class="fa fa-plus"></i> Manage Checkpoint wise Mandatory
                        </a>
                        <a class="button normals btn-circle" href="<?php echo base_url()."references/add_reference"; ?>">
                            <i class="fa fa-plus"></i> Add New Reference Link
                        </a>
						
                        <!--.$product['id']-->
						<a class="button normals btn-circle" href="<?php echo base_url()."references/upload_reference"; ?>">
                            <i class="fa fa-plus"></i> Upload Reference Links
                        </a>
						<a class="button normals btn-circle" href="<?php echo base_url()."references/download_references"; ?>">
                            <i class="fa fa-download"></i> Download
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($references)) { ?>
                        <p class="text-center">No References.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Reference Link</th>
                                    <th>Inspection</th>
                                    <th>Tool</th>
                                    <th>Model.Suffix</th>
                                    <th>Reference File</th>
                                    <th>Reference URL</th>
                                    <th>Mandatory</th>
                                    <th class="no_sort" style="width:200px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($references as $reference) { ?>
                                    <tr>
                                        <td><?php echo $reference['name']; ?></td>
                                        <td><?php echo !empty($reference['inspection_id']) ? $reference['inspection_name'] : 'All'; ?></td>
                                        <td><?php echo !empty($reference['tool']) ? $reference['tool'] : 'All'; ?></td>
                                        <td><?php echo !empty($reference['model_suffix']) ? $reference['model_suffix'] : 'All'; ?></td>
                                        <td class="text-center">
                                            <?php if(!empty($reference['reference_file'])) { ?>
                                                <a href="<?php echo base_url().$reference['reference_file']; ?>" target="_blank" class="btn btn-icon-only btn-outline red-mint">
                                                    <i class="fa fa-file-image-o"></i>
                                                </a>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $reference['reference_url']; ?></td>
                                        <td><?php echo ($reference['mandatory'] ? '<i class="fa fa-check"></i>': '<i class="fa fa-times"></i>'); ?></td>
                                        <td nowrap>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."references/add_reference/".$reference['id'];?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <a class="btn btn-outline btn-xs sbold red" href="<?php echo base_url()."references/delete_reference/".$reference['id'];?>" data-confirm="Are you sure you want to delete this reference link?">
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