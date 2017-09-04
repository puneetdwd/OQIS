<style>
    .hiddenRow {
        padding:0px !important;
    }
    .hiddenRow .row {
        padding:8px !important;
    }
    .hiddenRow .form-group {
        margin-bottom:0px;
    }
</style>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    
    <div class="breadcrumbs">
        <h1>
            Inspection Sampling Configuration
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."inspections"; ?>">Manage Inspections</a>
            </li>
            <li class="active">Inspection Sampling Configuration</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Configuration for Inspection - <?php echo $inspection['name'];?>
                    </div>
                    
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/update_inspection_config"; ?>">
                            <i class="fa fa-plus"></i> Update Inspection Configuration
                        </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form class="form-horizontal" role="form">
                        <div class="form-body">
                            <table class="table table-hover table-light">
                                <tr>
                                    <th>Inspection Name</th>
                                    <th>Config Type</th>
                                    <th>Line</th>
                                    <th>Tool</th>
                                    <th>Model.Suffix</th>
                                    <th>Sampling Type</th>
                                    <th></th>
                                </tr>
                            
                                <?php foreach($configs as $key => $config) { ?>
                                    <tr>
                                        <td><?php echo $inspection['name']; ?></td>
                                        <td><?php echo $config['inspection_type']; ?></td>
                                        <td><?php echo empty($config['line_name']) ? 'All' : $config['line_name']; ?></td>
                                        <td><?php echo empty($config['tool']) ? 'All' : $config['tool']; ?></td>
                                        <td><?php echo empty($config['model_suffix']) ? 'All' : $config['model_suffix']; ?></td>
                                        <td><?php echo $config['sampling_type']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-default btn-xs accordion-toggle" data-toggle="collapse" data-target="#detail-<?php echo $key; ?>">
                                                <span class="glyphicon glyphicon-eye-open"></span>
                                            </button>
                                            <a class="btn btn-xs btn-outline sbold red-thunderbird" data-confirm="Are you sure you want to this Model.Suffix?"
                                                href="<?php echo base_url()."sampling/delete_config/".$inspection['id']."/".$config['id'];?>">
                                                <i class="fa fa-trash-o"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="hiddenRow">
                                            <div class="accordian-body collapse row" id="detail-<?php echo $key; ?>">
                                                <?php if($config['sampling_type'] == 'Auto') { ?>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Inspection Level:</label>
                                                            <div class="col-md-7">
                                                                <p class="form-control-static">
                                                                    <?php echo $config['inspection_level']; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">Acceptable Quality:</label>
                                                            <div class="col-md-7">
                                                                <p class="form-control-static">
                                                                    <?php echo $config['acceptable_quality']; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } else if($config['sampling_type'] == 'Interval') { ?>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5"># of Months:</label>
                                                            <div class="col-md-7">
                                                                <p class="form-control-static">
                                                                    <?php echo $config['no_of_months']; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--/span-->
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label col-md-5">No of times:</label>
                                                            <div class="col-md-7">
                                                                <p class="form-control-static">
                                                                    <?php echo $config['no_of_times']; ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            
                                            <?php if($config['sampling_type'] == 'User Defined' || $config['sampling_type'] == 'Interval') { ?>
                                                <div class="row" style="margin: 0px 8px;">
                                                    <div class="col-md-6">
                                                        <table class="table table-hover table-light">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="3" class="text-center">Lot Range</th>
                                                                    <th># of Samples</th>
                                                                </tr>
                                                            </thead>
                                                            
                                                            <tbody>
                                                                <?php foreach(array_slice($config['lots'], 0, ceil(count($config['lots'])/2)) as $lot) { ?>
                                                                    <tr>
                                                                        <td><?php echo $lot['lower_val']; ?></td>
                                                                        <td>to</td>
                                                                        <td><?php echo ($lot['higher_val']) ? $lot['higher_val'] : 'over'; ?></td>
                                                                        <td><?php echo $lot['no_of_samples']; ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <table class="table table-hover table-light">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="3" class="text-center">Lot Range</th>
                                                                    <th># of Samples</th>
                                                                </tr>
                                                            </thead>
                                                            
                                                            <tbody>
                                                                <?php foreach(array_slice($config['lots'], ceil(count($config['lots'])/2)) as $lot) { ?>
                                                                    <tr>
                                                                        <tr>
                                                                            <td><?php echo $lot['lower_val']; ?></td>
                                                                            <td>To</td>
                                                                            <td><?php echo ($lot['higher_val']) ? $lot['higher_val'] : 'over'; ?></td>
                                                                            <td><?php echo $lot['no_of_samples']; ?></td>
                                                                        </tr>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            
                            </table>
                        </div>
                        <div class="form-actions fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-offset-4 col-md-8">
                                            <a href="<?php echo base_url().'inspections'; ?>" class="button white">
                                                <i class="m-icon-swapleft"></i> Back 
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- END FORM-->
                </div>
            </div>
            
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>