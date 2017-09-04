<div class="page-content">

    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Product Inspection | Review Screen
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
    </div>
        
    <div class="row">    
        <div class="col-md-12">
            <div class="portlet light bordered">
                <!--
                <div class="portlet-title">
                    <div class="caption">
                        Product Inspection Details
                    </div>
                </div>
                -->
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="">
                                <label class="control-label"><b>Product:</b></label>
                                <p class="form-control-static">
                                    <?php echo $audit['product_name']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="">
                                <label class="control-label"><b>Inspection:</b></label>
                                <p class="form-control-static">
                                    <?php echo $audit['inspection_name']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="">
                                <label class="control-label"><b>Model.Suffix:</b></label>
                                <p class="form-control-static">
                                    <?php echo $audit['model_suffix']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="">
                                <label class="control-label"><b>Serial No.:</b></label>
                                <p class="form-control-static">
                                    <?php echo $audit['serial_no']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="paired-section"></div>
                            
                        <div class="paired-section-template" style="display:none;">
                            <div class="col-md-6">
                            </div>
                            
                            <div class="col-md-3">
                                <div class="">
                                    <label class="control-label"><b>Model.Suffix:</b></label>
                                    <p class="form-control-static paired-model-suffix"></p>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="">
                                    <label class="control-label"><b>Serial No.:</b></label>
                                    <p class="form-control-static paired-serial-no"></p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                
                <div class="portlet-title">
                    <div class="caption">
                        Checkpoints | Total <?php echo count($checkpoints); ?>
                        <small><?php echo ' (OK - '.$checkpoints_OK.', NG - '.$checkpoints_NG.' Others - '.$checkpoints_PD.')'; ?></small>
                    </div>
                    
                    <?php if(!isset($admin_edit_audit)) { ?>
                        <div class="actions">
                            <a href="<?php echo base_url().'auditer/mark_as_complete'; ?>" data-confirm="Are you sure you want to mark this audit as complete. Once marked as complete the audit can't be changed." class="button normals btn-circle">    
                                Mark As Completed
                            </a>
                            <a href="<?php echo base_url().'auditer/on_hold';?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red"
                            data-confirm="Are you sure you want to mark this inspection on hold?">
                                On Hold
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <div class="portlet-body form">
                    <table class="table table-hover table-light">
                        <thead>
                            <tr>
                                <th>Checkpoint No.</th>
                                <th>Insp. Item</th>
                                <th>Insp. Item</th>
                                <th>Insp. Item</th>
                                <th>Spec.</th>
                                <th>LSL</th>
                                <th>USL</th>
                                <th>TGT</th>
                                <th>Remark</th>
                                <th>Value</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($checkpoints as $checkpoint) { ?>
                                <?php 
                                    $class = '';
                                    if(empty($checkpoint['result']) || $checkpoint['result'] == 'NA') {
                                        $class = "danger";
                                    } else if($checkpoint['result'] == 'NG') {
                                        $class = 'warning';
                                    }
                                    
                                    $url = base_url().'auditer/review_checkpoint/'.$checkpoint['checkpoint_no'].'/'.($checkpoint['iteration_no'] ? $checkpoint['iteration_no'] : 0) ;
                                    if(isset($admin_edit_audit)) {
                                        $url .= "/".$admin_edit_audit;
                                    }
                                ?>
                                <tr class="<?php echo $class; ?>" href="<?php echo $url; ?>" 
                                data-target="#change-checkpoint-modal" data-toggle="modal">
                                    <td><?php echo $checkpoint['checkpoint_no']; ?></td>
                                    <td><?php echo $checkpoint['insp_item']; ?></td>
                                    <td><?php echo $checkpoint['insp_item2']; ?></td>
                                    <td><?php echo $checkpoint['insp_item3']; ?></td>
                                    <td><?php echo $checkpoint['spec']; ?></td>
                                    <td nowrap>
                                        <?php echo ($checkpoint['lsl'] || $checkpoint['lsl'] === '0') ? $checkpoint['lsl'].' '.$checkpoint['unit'] : ''; ?>
                                    </td>
                                    <td nowrap>
                                        <?php echo ($checkpoint['usl'] || $checkpoint['usl'] === '0') ? $checkpoint['usl'].' '.$checkpoint['unit'] : ''; ?>
                                    </td>
                                    <td nowrap>
                                        <?php echo ($checkpoint['tgt'] || $checkpoint['tgt'] === '0') ? $checkpoint['tgt'].' '.$checkpoint['unit'] : ''; ?>
                                    </td>
                                    <td><?php echo $checkpoint['remark']; ?></td>
                                    <td nowrap>
                                        <?php echo ($checkpoint['audit_value'] || $checkpoint['audit_value'] === '0') ? $checkpoint['audit_value'].' '.$checkpoint['unit'] : ''; ?>
                                    </td>
                                    <td><?php echo $checkpoint['result']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    
                    <?php if(!isset($admin_edit_audit)) { ?>
                        <div class="form-actions right">
                            <a href="<?php echo base_url().'auditer/mark_as_complete'; ?>" data-confirm="Are you sure you want to mark this inspection as complete. Once marked as complete the inspection can't be changed." class="button normals btn-circle">    
                                Mark As Completed
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

<div class="modal fade bs-modal-lg modal-scroll" id="change-checkpoint-modal" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <img src="../assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;Loading... </span>
            </div>
        </div>
    </div>
</div>

<?php if(!empty($audit['paired'])) { ?>
    <script>
        $(window).load(function() {
            get_paired_insp(<?php echo $audit['id']; ?>);
        });
    </script>
<?php } ?>