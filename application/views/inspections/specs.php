<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Specs - <?php echo $checkpoint['checkpoint_no']; ?>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."inspections"; ?>">
                    Manage Inspections
                </a>
            </li>
            <li>
                <a href="<?php echo base_url()."inspections/checkpoints/".$checkpoint['inspection_id']; ?>">
                    Manage Checkpoints
                </a>
            </li>
            <li class="active">Manage Specs</li>
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
                        <i class="fa fa-reorder"></i>Specs - (
                            LSL : <?php echo ($checkpoint['lsl']) ? $checkpoint['lsl'].' '.$checkpoint['unit'] : ''; ?>,
                            USL : <?php echo ($checkpoint['usl']) ? $checkpoint['usl'].' '.$checkpoint['unit'] : ''; ?>,
                            TGT : <?php echo ($checkpoint['tgt']) ? $checkpoint['tgt'].' '.$checkpoint['unit'] : ''; ?>
                        )
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."inspections/add_spec/".$checkpoint['id']; ?>">
                            <i class="fa fa-plus"></i> Add Spec
                        </a>
                        <a class="button normals btn-circle" href="<?php echo base_url()."inspections/upload_specs/".$checkpoint['inspection_id']."/".$checkpoint['id']; ?>">
                            <i class="fa fa-plus"></i> Upload Spec
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($specs)) { ?>
                        <p class="text-center">No Record found.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Model.Suffix</th>
                                    <th>LSL</th>
                                    <th>USL</th>
                                    <th>TGT</th>
                                    <th class="no_sort" style="width:200px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($specs as $spec) { ?>
                                    <tr>
                                        <td><?php echo $spec['model_suffix']; ?></td>
                                        <td nowrap>
                                            <?php echo ($spec['lsl']) ? $spec['lsl'].' '.$spec['unit'] : ''; ?>
                                        </td>
                                        <td nowrap>
                                            <?php echo ($spec['usl']) ? $spec['usl'].' '.$spec['unit'] : ''; ?>
                                        </td>
                                        <td nowrap>
                                            <?php echo ($spec['tgt']) ? $spec['tgt'].' '.$spec['unit'] : ''; ?>
                                        </td>
                                        <td nowrap>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."inspections/add_spec/".$checkpoint['id'].'/'.$spec['id'];?>">
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