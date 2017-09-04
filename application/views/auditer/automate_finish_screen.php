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
                        
                        <div class="col-md-3">
                            <div class="">
                                <label class="control-label"><b>Inspection:</b></label>
                                <p class="form-control-static">
                                    <?php echo $audit['inspection_name']; ?>
                                </p>
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
                        Inspection Result - <?php echo $audit['automate_result']; ?>
                    </div>
                    <div class="actions">
                        <a href="<?php echo base_url().'auditer/mark_as_complete'; ?>" data-confirm="Are you sure you want to mark this audit as complete. Once marked as complete the audit can't be changed." class="button normals btn-circle">    
                            Mark As Completed
                        </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <table class="table table-hover table-light">
                        <?php foreach($result as $r) { ?>
                            <tr>
                                <?php foreach($r as $c) { ?>
                                    <td><?php echo $c; ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </table>
                    
                    <div class="form-actions right">
                        <a href="<?php echo base_url().'auditer/mark_as_complete'; ?>" data-confirm="Are you sure you want to mark this audit as complete. Once marked as complete the audit can't be changed." class="button normals btn-circle">    
                            Mark As Completed
                        </a>
                    </div>
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