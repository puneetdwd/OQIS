<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            MPAT Inspections Status
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">MPAT Inspections Status</li>
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
            
            <div class="row">
                
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-reorder"></i>MPAT Inspections Running
                            </div>
                        </div>
                        <div class="portlet-body">
                            <?php if(empty($audits)) { ?>
                                <p class="text-center">No inspection running right now.</p>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-light">
                                        <thead>
                                            <tr>
                                                <th>Inspector</th>
                                                <th>Inspection Date</th>
                                                <th>Inspection</th>
                                                <th>Line</th>
                                                <th>Model.Suffix</th>
                                                <th>Tool</th>
                                                <th>Workorder</th>
                                                <th>Serial No.</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($audits as $audit) { ?>
                                                <tr>
                                                    <td><?php echo $audit['auditer']; ?></td>
                                                    <td nowrap><?php echo date('jS M, y', strtotime($audit['audit_date'])); ?></td>
                                                    <td><?php echo $audit['inspection_name']; ?></td>
                                                    <td><?php echo $audit['line_name']; ?></td>
                                                    <td><?php echo $audit['model_suffix']; ?></td>
                                                    <td><?php echo $audit['tool']; ?></td>
                                                    <td><?php echo $audit['workorder']; ?></td>
                                                    <td><?php echo $audit['serial_no']; ?></td>
                                                    <td class="text-center">
                                                        <?php if($audit['on_hold'] == 0) { ?>
                                                            <span class="label label-warning"> 
                                                                <i class="fa fa-spinner"></i> On Going - Iteration <?php echo $audit['current_iteration'];?>
                                                            </span>
                                                        <?php } else { ?>
                                                            <?php if(strtotime($audit['iteration_datetime']) <= strtotime('now')) { ?>
                                                                <span class="label label-danger"> 
                                                                    <i class="fa fa-ban"></i> On Hold
                                                                </span>
                                                                &nbsp;&nbsp;
                                                                <span class="label label-danger"> 
                                                                    <i class="fa fa-check"></i> Iteration <?php echo $audit['current_iteration']?> activated
                                                                </span>
                                                            <?php } else { ?>
                                                                <span class="label label-warning"> 
                                                                    <i class="fa fa-check"></i> Iteration <?php echo $audit['current_iteration']?> will start on <?php echo date('jS M h:ia', strtotime($audit['iteration_datetime'])); ?>
                                                                </span>
                                                            <?php } ?>
                                                        <?php } ?>
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
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>