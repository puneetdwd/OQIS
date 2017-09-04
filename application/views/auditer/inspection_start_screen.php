<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Product Inspection | Start Screen
        </h1>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
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
        
        <div class="col-md-3">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        Product Inspection Details
                    </div>
                </div>
                <div class="portlet-body form inspection-detail-sidebar">
                    <!-- BEGIN FORM-->
                    <form role="form">
                        <div class="form-body">
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><b>Product:</b></label><br />
                                        <p class="form-control-static">
                                            <?php echo $audit['product_name']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><b>Inspection:</b></label><br />
                                        <p class="form-control-static">
                                            <?php echo $audit['inspection_name']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        
                            <hr />
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><b>Model.Suffix:</b></label><br />
                                        <p class="form-control-static">
                                            <?php echo $audit['model_suffix']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><b>Serial No.:</b></label><br />
                                        <p class="form-control-static">
                                            <?php echo $audit['serial_no']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="paired-section"></div>
                            
                            <div class="paired-section-template" style="display:none;">
                                <hr />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label"><b>Model.Suffix:</b></label><br />
                                            <p class="form-control-static paired-model-suffix"></p>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label"><b>Serial No.:</b></label><br />
                                            <p class="form-control-static paired-serial-no"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            
            <?php if(!empty($audit['full_auto'])) { ?>
                <div class="portlet light bordered text-center">
                    <a href="<?php echo base_url().'auditer/automatic_inspection_result';?>" class="button normals btn-circle">    
                        Automate
                    </a>
                </div>
            <?php } else if($audit['insp_type'] == 'interval' && !empty($audit['attach_report'])) { ?>
                
                <?php if(strtotime($audit['iteration_datetime']) <= strtotime('now')) { ?>
                    <div class="portlet light bordered text-center">
                        <form role="form" class="validate-form form-inline" action="<?php echo base_url().'auditer/attach_report/'.$audit['id']; ?>" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <input type="hidden" value="1" name="post_verify" />
                                
                                <div class="form-group">
                                    <input type="file" class="required" name="attach_report">
                                </div>
                                <button type="submit" class="button">Attach</button>
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="portlet light bordered well" style="margin-top: 55px;">
                        <div class="text-center">
                            <span style="font-size:18px;">
                                Inspection has been successfully registered. Attach button will be activated on <br />
                                <span style="font-size:24px;"><?php echo date('jS M h:ia', strtotime($audit['iteration_datetime'])); ?></span>
                            </span>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-reorder"></i>Checkpoints | <small>Total No. of checkpoints <?php echo count($checkpoints)+$excluded_count; ?>, Applicable Checkpoints <?php echo count($checkpoints); ?></small>
                        </div>
                        <div class="actions">
                            <a href="<?php echo base_url().'auditer/start_inspection';?>" class="button normals btn-circle">    
                                Start Inspection
                            </a>
                            <?php if($audit['insp_type'] != 'interval') { ?>
                                <a href="<?php echo base_url().'auditer/mark_as_abort';?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red" data-confirm="Are you sure you want to cancel this inspection?">
                                    Abort
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo base_url().'auditer/abort_request';?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red" data-confirm="Are you sure you want to abort this inspection?">
                                    Request for Abort
                                </a>
                            <?php } ?>
                            <a href="<?php echo base_url().'auditer/on_hold';?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red"
                            data-confirm="Are you sure you want to mark this inspection on hold?">
                                On Hold
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <?php if(empty($checkpoints)) { ?>
                            <p class="text-center">No Checkpoints.</p>
                        <?php } else { ?>
                            <table class="table table-hover table-light">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Insp. Item</th>
                                        
                                        <?php if($audit['checkpoint_format'] >= 3) { ?>
                                            <th>Insp. Item</th>
                                        <?php } ?>
                                        <?php if($audit['checkpoint_format'] == 4) { ?>
                                            <th>Insp. Item</th>
                                        <?php } ?>
                                        
                                        <th>Insp. Item</th>
                                        <th>Spec.</th>
                                        <th>LSL</th>
                                        <th>USL</th>
                                        <th>TGT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($checkpoints as $checkpoint) { ?>
                                        <tr>
                                            <td><?php echo $checkpoint['checkpoint_no']; ?></td>
                                            <td><?php echo $checkpoint['insp_item']; ?></td>
                                            
                                            <?php if($audit['checkpoint_format'] >= 3) { ?>
                                                <td><?php echo $checkpoint['insp_item2']; ?></td>
                                            <?php } ?>
                                            <?php if($audit['checkpoint_format'] == 4) { ?>
                                                <td><?php echo $checkpoint['insp_item4']; ?></td>
                                            <?php } ?>
                                            
                                            <td><?php echo $checkpoint['insp_item3']; ?></td>
                                            <td><?php echo $checkpoint['spec']; ?></td>
                                            <td nowrap>
                                                <?php echo ($checkpoint['lsl']) ? $checkpoint['lsl'].' '.$checkpoint['unit'] : ''; ?>
                                            </td>
                                            <td nowrap>
                                                <?php echo ($checkpoint['usl']) ? $checkpoint['usl'].' '.$checkpoint['unit'] : ''; ?>
                                            </td>
                                            <td nowrap>
                                                <?php echo ($checkpoint['tgt']) ? $checkpoint['tgt'].' '.$checkpoint['unit'] : ''; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                        
                        <div class="form-actions right">
                            <a href="<?php echo base_url().'auditer/start_inspection';?>" class="button normals btn-circle">    
                                Start Inspection
                            </a>
                            <a href="<?php echo base_url().'auditer/mark_as_abort';?>" class="btn btn-circle btn-outline btn-xs sbold red"
                            data-confirm="Are you sure you want to cancel this inspection?">
                                Abort
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

<?php if(!empty($audit['paired'])) { ?>
    <script>
        $(window).load(function() {
            get_paired_insp(<?php echo $audit['id']; ?>);
        });
    </script>
<?php } ?>