<style>
.form-group{
    margin-bottom: 0px;
}
.table > thead > tr > th,.table > tbody > tr > td{
    padding : 4px 8px;
}
.btn-block{
    display: inline;
}
.mt-element-ribbon .ribbon{
    top: 6px;
}
.portlet.light > .portlet-title{
    min-height: 30px;
}
.portlet > .portlet-title{
    margin-bottom: 2px;
}
.portlet.light > .portlet-title > .actions {
    padding: 0 0 8px;
}
textarea.form-control {
    overflow-y: unset;
}
.ref_float{
    position: fixed;
    right: 0;
    top: 6%;
    box-shadow: 2px 2px 2px 1px #dfdfdf;
    width: 170px;
}
.ref_link{
    background-color: #e73d4a;
    color: #fff;
    font-weight: bold;
    padding: 10px;
    text-decoration: none;
    display: block;
}
.ref_link:hover{
    color: #fff;
    text-decoration: none;
}
.ref_link:visited {
    color: #fff;
    text-decoration: none;
}
.ref_link:active {
    color: #fff;
    text-decoration: none;
}
.ref_link:link{
    color: #fff;
    text-decoration: none;
}
.ref_float > ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: 200px;
    background-color: #f9f9f9;
    /*display: none;*/
}

.ref_float > ul > li a {
    display: block;
    color: #6c7b88;
    padding: 8px 16px;
    text-decoration: none;
}

/* Change the link color on hover */
.ref_float > ul > li a:hover {
    color: #e73d4a;
}
.mt-element-ribbon .ribbon {
    padding: 0.2em 1em;
}
.form-control-static{
    min-height: 25px;
    padding-top: 0;
}
</style>

<div class="page-content" style="padding-top:60px;">

    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs" style="margin-bottom:5px;">
        <h1>
            Product Inspection | Checkpoint Screen | <?php echo $this->session->userdata('current_key')." of ".count($this->session->userdata('nos')); ?>
        </h1>
        
        <a href="<?php echo base_url().'auditer/on_hold';?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red"
        data-confirm="Are you sure you want to mark this inspection on hold?">
            On Hold
        </a> 
        
        <?php if($audit['insp_type'] != 'interval') { ?>
            <a href="<?php echo base_url().'auditer/mark_as_abort';?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red"
            data-confirm="Are you sure you want to cancel this inspection?">
                Abort
            </a>
        <?php } else { ?>
            <a href="<?php echo base_url().'auditer/abort_request';?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red"
            data-confirm="Are you sure you want to abort this inspection?">
                Request for Abort
            </a>
        <?php } ?>
        <a href="<?php echo base_url();?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red" target="_blank">
            Go To Home
        </a>
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
            <div class="portlet light bordered" style="padding-top: 5px; padding-bottom: 0px; margin-bottom: 2px;">
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
                        
                        
                    </div>
                    
                    <?php if(!empty($paired['model_suffix'])) { ?>
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            
                            <div class="col-md-3">
                                <div class="">
                                    <label class="control-label"><b>Model.Suffix:</b></label>
                                    <p class="form-control-static">
                                        <?php echo $paired['model_suffix']; ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="">
                                    <label class="control-label"><b>Serial No.:</b></label>
                                    <p class="form-control-static">
                                        <?php echo $paired['serial_no']; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
        
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered mt-element-ribbon" style="padding-top: 8px;">
                <div class="ribbon ribbon-clip ribbon-color-danger uppercase">
                    <div class="ribbon-sub ribbon-clip"></div> <b>Checkpoint #<?php echo $checkpoint['checkpoint_no']?></b> 
                </div>
                <div class="portlet-title">
                    <!--<div class="caption">
                        &nbsp;
                    </div>-->
                    <div class="actions">
                        <?php if(!empty($checkpoint['result'])) { ?>
                            <p class="font-red-mint" style="display:inline;"> Checkpoint already marked as <b><?php echo $checkpoint['result']; ?></b> </p>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php } ?>
                        <!--
                        <a href="#record-result-modal" data-toggle="modal" class="btn btn-circle green-meadow">    
                            Record Result
                        </a>
                        -->
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="form-body" style="padding-top: 0px; padding-bottom: 0px;">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <?php $class = ($audit['checkpoint_format'] == 4) ? 'col-md-4' : 'col-md-6'; ?>
                                    <div class="<?php echo $class; ?>">
                                        <div class="form-group">
                                            <label class="control-label"><b>Insp Item:</b></label>
                                            <p class="form-control-static">
                                                <?php echo $checkpoint['insp_item']; ?>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <?php if($audit['checkpoint_format'] >= 3) { ?>
                                        <div class="<?php echo $class; ?>">
                                            <div class="form-group">
                                                <label class="control-label"><b>Insp Item:</b></label>
                                                <p class="form-control-static">
                                                    <?php echo $checkpoint['insp_item2']; ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    
                                    <?php if($audit['checkpoint_format'] == 4) { ?>
                                        <div class="<?php echo $class; ?>">
                                            <div class="form-group">
                                                <label class="control-label"><b>Insp Item:</b></label>
                                                <p class="form-control-static">
                                                    <?php echo $checkpoint['insp_item4']; ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><b>Insp Item:</b></label>
                                            <p class="form-control-static">
                                                <?php echo $checkpoint['insp_item3']; ?>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label"><b>Spec:</b></label>
                                            <p class="form-control-static">
                                                <?php echo $checkpoint['spec']; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <img src="<?php echo base_url().$checkpoint['guideline_image'].'?'.time(); ?>" style="width:90%;"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3" style="padding-left: 0;">
                                
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>LSL</th>
                                            <th>USL</th>
                                            <th>TGT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo ($checkpoint['lsl']) ? $checkpoint['lsl'].' '.$checkpoint['unit'] : '--'; ?></td>
                                            <td><?php echo ($checkpoint['usl']) ? $checkpoint['usl'].' '.$checkpoint['unit'] : '--'; ?></td>
                                            <td><?php echo ($checkpoint['tgt']) ? $checkpoint['tgt'].' '.$checkpoint['unit'] : '--'; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <form role="form" class="validate-form" action="<?php echo base_url().'auditer/record_result/'.$checkpoint['id']; ?>" method="post" enctype="multipart/form-data">
                                    
                                    <?php if(!empty($checkpoint['automate_result_row']) || !empty($checkpoint['automate_result_col'])) { ?>
                                        <div class="col-md-8">
                                            <span style="margin-top: 6px; display: inline-block; font-weight: 700;">
                                            <?php if($checkpoint['audit_value']) {
                                                echo 'Reading captured : '.$checkpoint['audit_value']; 
                                            } ?>
                                            </span>
                                        </div>
                                        <div class="col-md-4" style="padding:0px;">
                                            <button type="submit" name="automate" value="true" class="btn btn-block green-meadow">Automatic</button>
                                        </div>
                                    <?php } else if(!empty($checkpoint['lsl']) || !empty($checkpoint['usl'])) { ?>
                                        <input type="hidden" value="<?php echo $checkpoint['lsl']; ?>"  id="register-inspection-checkpoint-lsl" />
                                        <input type="hidden" value="<?php echo $checkpoint['usl']; ?>"  id="register-inspection-checkpoint-usl" />

                                        <div class="row">
                                            <div class="col-md-9" style="padding-right:0;">
                                                <div class="form-group">
                                                    <label class="control-label" for="audit_value">Value: &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="text" class="required form-control" id="audit_value" name="audit_value" 
                                                               value="<?php echo $checkpoint['audit_value']; ?>" style="width: 65%; display: inline;">
                                                    <span class="help-block"></span>
                                                </lable>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2" style="padding-right: 0; padding-left: 0;">
                                                <div class="form-group" style="text-align:right">
                                                    <label class="control-label" for="">
                                                        <button type="button" id="na-confirm" class="btn btn-block yellow-gold">NA</button>
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <button type="submit" id="na-button" name="result" value="NA" class="btn yellow-gold" style="display:none;">NA</button>
                                        </div>
                                    <?php } else { ?>
                                        <!--
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group" id="checkpoint-screen-result-error">
                                                    <label class="control-label">Result</label>
                                                    <select name="result" class="form-control required select2me"
                                                    data-placeholder="Select result" data-error-container="#checkpoint-screen-result-error">
                                                        <option value=""></option>
                                                        <option value="OK" <?php if($checkpoint['result'] == 'OK') { ?> selected="selected" <?php } ?>>OK</option>
                                                        <option value="NG" <?php if($checkpoint['result'] == 'NG') { ?> selected="selected" <?php } ?>>NG</option>
                                                        <option value="NA" <?php if($checkpoint['result'] == 'NA') { ?> selected="selected" <?php } ?>>NA</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-6" style="padding:0px;">
                                                    <button type="submit" name="result" value="OK" class="btn btn-block green-meadow">OK</button>
                                                </div>
                                                <div class="col-md-3" style="padding:0px;">
                                                    
                                                    <button type="button" id="ng-confirm" class="btn btn-block red-sunglo">NG</button>
                                                </div>
                                                <div class="col-md-3" style="padding:0px;">
                                                    
                                                    <button type="button" id="na-confirm" class="btn btn-block yellow-gold">NA</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" id="na-button" name="result" value="NA" class="btn yellow-gold" style="display:none;">NA</button>
                                        <button type="submit" id="ng-button" name="result" value="NG" class="btn btn-block red-sunglo" style="display:none;">NG</button>
                                    <?php } ?>
                                    
                                    <div class="row">
                                        <div class="col-md-12" style="padding-right: 0;">
                                            <div class="form-group">
                                                <label for="checkpoints_excel" class="control-label">Remarks: 
                                                    <textarea class="form-control" id="register-inspection-remark" name="remark" placeholder="Remarks" rows="2" style="vertical-align: middle; width: 70%; display: inline;">
                                                        <?php echo $checkpoint['remark']; ?>
                                                    </textarea>
                                                    <span class="help-block"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12" style="padding-right: 0;">
                                            <div class="form-group">
                                                <label for="checkpoints_excel" class="control-label">Attach Defect Image: </label>
                                               
                                                    <input type="file" name="defect_image">
                                                
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-actions text-center">
                                        <?php $nos = $this->session->userdata('nos'); ?>
                                        <?php if($checkpoint['checkpoint_no'] != $nos[0]) { ?>
                                            <a href="<?php echo base_url().'auditer/navigate_checkpoint/prev'; ?>" class="btn btn-circle red-sunglo btn-outline pull-left"> << Previous</a>
                                        <?php } ?>
                                        
                                        <?php if(!empty($checkpoint['lsl']) || !empty($checkpoint['usl'])) { ?>
                                            <button type="submit" id="register-inspection-submit" class="btn btn-circle green-meadow">Submit</button>
                                        <?php } ?>
                                        
                                        <?php if(!empty($checkpoint['result'])) { ?>
                                            <?php if($checkpoint['checkpoint_no'] != $nos[count($nos)-1]) { ?>
                                                <a href="<?php echo base_url().'auditer/navigate_checkpoint/next'; ?>" class="btn btn-circle red-sunglo btn-outline pull-right"> Next >> </a>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

<?php if(!empty($references)) { ?>

<div class="ref_float">
    <a href="javascript:void();" class="ref_link" onclick="ref_menu();"> 
        Reference Link <!--&nbsp; <i class="fa fa-angle-double-down" aria-hidden="true"></i>-->
    </a>
    
    <ul id="ref_menu">
        
        <?php 
            $opened = $this->session->userdata('opened_link');
            if(empty($opened)) {
                $opened = array();
            }
        ?>
        <?php foreach($references as $reference) { ?>
            <?php 
                $link = base_url().'auditer/open_reference';
                $link .= '?name='.urlencode($reference['name']);

                if(!empty($reference['reference_file'])) {
                    $link .= '&url='.urlencode(base_url().$reference['reference_file']);
                } else {
                     $link .= '&url='.urlencode($reference['reference_url']);
                }

                $class = "";
                if(in_array($reference['name'], $mandatories)) {
                    if(in_array($reference['name'], $opened)) {
                        $class = "mandatory-reference-opened";
                    } else {
                        $class = "mandatory-reference";
                    }
                }
            ?>

            <li class="<?php echo $class; ?>">
                <a href="<?php echo $link; ?>" target="_blank">
                    <i class="fa fa-book"></i> <?php echo $reference['name']; ?> 
                </a>
            </li>
        <?php } ?>
    </ul>
</div>

<script>
function ref_menu(){
    $("#ref_menu").slideToggle();
}
$(document).ready(function(){
    $("html, body").animate({ scrollTop: 60 }, 1000);
    $("header").hide();
});
</script>
<?php } ?>

<?php if($this->session->userdata('mandatory_popup') && !empty($mandatories)) { ?>
    <script>
        $(document).ready(function() {
            mandatory_popup('<?php echo implode(', ', $mandatories);?>');
        });
        
    </script>
    <?php $this->session->set_userdata('mandatory_popup', 0)?>
<?php } ?>