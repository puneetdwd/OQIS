<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Checkpoints
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
            <li class="active">Manage Checkpoints</li>
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
                        <i class="fa fa-reorder"></i>Checkpoints for Inspection - <?php echo $inspection['name'];?>
                    </div>
                    <div class="actions">
                        <?php if($inspection['insp_type'] == 'interval') { ?>
                            <a class="button normals btn-circle" href="<?php echo base_url()."inspections/iterations/".$inspection['id']; ?>">
                                <i class="fa fa-plus"></i> Manage Iterations
                            </a>
                        <?php } ?>
                        <a class="button normals btn-circle" href="<?php echo base_url()."inspections/add_checkpoint/".$inspection['id']; ?>">
                            <i class="fa fa-plus"></i> Add New Checkpoint
                        </a>
                        <a class="button normals btn-circle" href="<?php echo base_url()."inspections/view_revision_history/".$inspection['id']; ?>">
                            <i class="fa fa-eye"></i> View Revisions
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($checkpoints)) { ?>
                        <p class="text-center">No Checkpoints.</p>
                    <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-light">
                                <thead>
                                    <tr>
                                        <th>Checkpoint No.</th>
                                        <th>GMES Code</th>
                                        <?php if(empty($inspection['full_auto'])) { ?>
                                            <th>Insp. Item</th>
                                            <?php if($inspection['checkpoint_format'] >= 3) { ?>
                                                <th>Insp. Item</th>
                                            <?php } ?>
                                            <?php if($inspection['checkpoint_format'] == 4) { ?>
                                                <th>Insp. Item</th>
                                            <?php } ?>
                                        <?php } ?>
                                        <th>Insp. Item</th>
                                        <th>Spec.</th>
                                        <th>LSL</th>
                                        <th>USL</th>
                                        <th>TGT</th>
                                        <th>Guideline Image</th>
                                        <?php if($inspection['automate_result'] == 1) { ?>
                                            <th>Automate</th>
                                        <?php } ?>
                                        <th class="no_sort" style="width:200px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($checkpoints as $checkpoint) { ?>
                                        <tr class="checkpoint-<?php echo $checkpoint['id']; ?>">
                                            <td><?php echo $checkpoint['checkpoint_no']; ?></td>
                                            <td><?php echo $checkpoint['gmes_code']; ?></td>
                                            <?php if(empty($inspection['full_auto'])) { ?>
                                                <td><?php echo $checkpoint['insp_item']; ?></td>
                                                
                                                <?php if($inspection['checkpoint_format'] >= 3) { ?>
                                                    <td><?php echo $checkpoint['insp_item2']; ?></td>
                                                <?php } ?>
                                                <?php if($inspection['checkpoint_format'] == 4) { ?>
                                                    <td><?php echo $checkpoint['insp_item4']; ?></td>
                                                <?php } ?>
                                            <?php } ?>
                                            
                                            <td><?php echo $checkpoint['insp_item3']; ?></td>
                                            <td><?php echo $checkpoint['spec']; ?></td>
                                            <?php if($checkpoint['has_multiple_specs']) { ?>
                                                <td colspan="3" class="text-center">Model wise Specs</td>
                                            <?php } else { ?>
                                                <td nowrap>
                                                    <?php echo ($checkpoint['lsl']) ? $checkpoint['lsl'].' '.$checkpoint['unit'] : ''; ?>
                                                </td>
                                                <td nowrap>
                                                    <?php echo ($checkpoint['usl']) ? $checkpoint['usl'].' '.$checkpoint['unit'] : ''; ?>
                                                </td>
                                                <td nowrap>
                                                    <?php echo ($checkpoint['tgt']) ? $checkpoint['tgt'].' '.$checkpoint['unit'] : ''; ?>
                                                </td>
                                            <?php } ?>   
                                            <td class="guideline-image-col text-center">
                                                <img src="<?php echo base_url(); ?>assets/global/img/loading-spinner-grey.gif" alt="" class="guideline-image-loading loading" style="display:none;">
                                                
                                                <?php if($checkpoint['guideline_image']) { ?>
                                                    <a href="<?php echo base_url().$checkpoint['guideline_image']; ?>" target="_blank" class="btn btn-icon-only btn-outline red-mint guideline-image-href">
                                                        <i class="fa fa-file-image-o"></i>
                                                    </a>
                                                <?php } else { ?>
                                                    <a href="" target="_blank" style="display:none;" class="btn btn-outline btn-icon-only red-mint guideline-image-href">
                                                        <i class="fa fa-file-image-o"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                            <?php if($inspection['automate_result'] == 1) { ?>
                                                <td>
                                                    <img src="<?php echo base_url(); ?>assets/global/img/loading-spinner-grey.gif" alt="" class="automate-setting-loading loading" style="display:none;">
                                                    
                                                    <span class="automate-setting-text">
                                                    <?php if($checkpoint['automate_result_row'] && $checkpoint['automate_result_col']) {
                                                        echo $checkpoint['automate_result_col'].$checkpoint['automate_result_row'];
                                                    ?>   
                                                        <a class="button small gray" href="<?php echo base_url()."inspections/clear_automate_settings/".$checkpoint['id'];?>">
                                                            <i class="fa fa-edit"></i> Clear
                                                        </a>
                                                    <?php } ?>
                                                    </span>
                                                </td>
                                            <?php } ?>
                                            <td nowrap class="text-center">
                                                <a class="button small gray" style="margin-bottom:3px;" href="<?php echo base_url().'inspections/attach_guideline/'.$checkpoint['id']; ?>" data-target="#ajax" data-toggle="modal"> Attach guideline Image </a>
                                                <br />
                                                <?php if(!empty($inspection['automate_result'])) { ?>
                                                    <?php if((!empty($checkpoint['usl']) || !empty($checkpoint['lsl'])) || $checkpoint['has_multiple_specs']) { ?>
                                                        <a class="button small gray" href="<?php echo base_url().'inspections/automate_settings/'.$checkpoint['id']; ?>" data-target="#ajax" data-toggle="modal">
                                                            Automate Settings
                                                        </a>
                                                        <br />
                                                    <?php } ?>
                                                <?php } ?>
                                                <a class="button small gray" href="<?php echo base_url()."inspections/add_checkpoint/".$inspection['id'].'/'.$checkpoint['id'];?>">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                
                                                <a class="btn btn-outline btn-xs sbold red-thunderbird" href="<?php echo base_url()."inspections/delete_checkpoint/".$inspection['id'].'/'.$checkpoint['id'];?>" data-confirm="Are you sure you want to delete this checkpoint?">
                                                    <i class="fa fa-trash-o"></i> Delete
                                                </a>
                                                
                                                <a class="button small gray" 
                                                    href="<?php echo base_url()."inspections/specs/".$inspection['id'].'/'.$checkpoint['id'];?>">
                                                    <i class="fa fa-eye"></i> Specs
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                    
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

<div class="modal fade" id="ajax" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img src="<?php echo base_url(); ?>assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;Loading... </span>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="automate-setting-modal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <form class="validate-form2" action="<?php echo base_url().'inspections/automate_setting'; ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Automate settings</h4>
                </div>
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="automate_result_row">Row:
                                <span class="required">*</span></label>
                                <input type="text" class="required form-control" name="automate_result_row">
                                <span class="help-block">
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="automate_result_col">Column:
                                <span class="required">*</span></label>
                                <input type="text" class="required form-control" name="automate_result_col">
                                <span class="help-block">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn green">Save</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>