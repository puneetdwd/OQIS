<div class="page-content">
    
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
    <!-- END PAGE CONTENT-->
    
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
    
    <div class="row" style="margin-top:50px;">
        <div class="col-md-10 col-md-offset-1">
            <div class="portlet light bordered well">
                <div class="portlet-body form">
                
                    <div class="text-center">
                        <span style="font-size:18px;">
                            You have completed 
                            <span style="font-size:24px;"><?php echo $audit['current_iteration']-1; ?></span>
                            iteration for this inspection, next iteration will start on 
                            <span style="font-size:24px;"><?php echo date('jS M h:ia', strtotime($audit['iteration_datetime'])); ?></span>
                        </span>
                    </div>
                    
                    <?php if(strtotime($audit['iteration_datetime']) <= strtotime('now')) { ?>
                        <hr />
                        
                        <div class="text-center">
                            <a class="button normals btn-circle" 
                                href="<?php echo base_url()."auditer/checkpoint_screen";?>">
                                Start
                            </a>
                            
                            <a href="<?php echo base_url().'auditer/on_hold';?>" class="btn btn-circle btn-outline btn-sm sbold red"
                            data-confirm="Are you sure you want to mark this inspection on hold?">
                                Put On Hold
                            </a> 
                            
                            <div class="clearfix"></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div> 
    </div>  
</div>