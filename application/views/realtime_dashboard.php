<input type="hidden" id="page" value="realtime_dashboard" />
<div class="page-content">
    <div class="row">
    
        <div class="col-md-12">
            <!-- BEGIN REGIONAL STATS PORTLET-->
            <div class="portlet light bordered realtime-dashboard-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-share font-red-sunglo"></i>
                        <span class="caption-subject font-red-sunglo bold uppercase">Realtime Dashboard</span>
                    </div>
                    <div class="actions">
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;"> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-hover table-light">
                        <thead>
                            <tr>
                                <?php foreach(array_slice($sampling_plan, 0, 1) as $plan) { ?>
                                    <?php foreach($plan as $k => $p) { ?>
                                        <?php if($k === 0) { ?>
                                            <th rowspan="2" style="vertical-align: middle;"><?php echo $p; ?></th>
                                        <?php } else { ?>
                                            <th colspan="3" class="text-center"><?php echo $p; ?></th>
                                        <?php } ?>
                                        
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                            <tr>
                                <?php foreach(array_slice($sampling_plan, 0, 1) as $plan) { ?>
                                    <?php foreach($plan as $k => $p) { ?>
                                        <?php if($k !== 0) { ?>
                                            <th class="text-center">Planned</th>
                                            <th class="text-center">Completed</th>
                                            <th class="text-center">In Progress</th>
                                        <?php } ?>
                                        
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(array_slice($sampling_plan, 1) as $plan) { ?>
                                <tr>
                                    <td><?php echo $plan[0]; ?></td>
                                    <?php 
                                        $no_of_samples = 0;
                                        $completed = 0;
                                        $in_progess = 0;
                                    ?>
                                    <?php foreach($plan as $k => $p) { ?>
                                        <?php if($k === 0) { continue; } ?>
                                        <?php 
                                            if($k % 3 === 1) {
                                                $no_of_samples = $p;
                                            } else if($k % 3 === 2) {
                                                $completed = $p;
                                            } else {
                                                $in_progess = $p;
                                                
                                                $complete_perc = round(($completed/$no_of_samples)*100, 1);
                                                $progress_perc = round(($in_progess/$no_of_samples)*100, 1);
                                        ?>
                                                <td class="text-center font-red-sunglo"><?php echo $no_of_samples; ?></td>
                                                <td class="text-center font-green-jungle"><?php echo $completed; ?></td>
                                                <td class="text-center font-yellow-crusta"><?php echo $in_progess; ?></td>
                                        <?php } ?>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="row realtime-dash-header">
                        <div class="col-md-3">
                            <h4 class="realtime-dash-heading text-center">Model.Suffix</h4>
                        </div>
                        <div class="col-md-3">
                            <h4 class="realtime-dash-heading text-center">Inspection Name</h4>
                        </div>
                        <div class="col-md-2">
                            <h4 class="realtime-dash-heading text-center">Planned Samples</h4>
                        </div>
                        <div class="col-md-2">
                            <h4 class="realtime-dash-heading text-center">Samples Completed</h4>
                        </div>
                        <div class="col-md-2">
                            <h4 class="realtime-dash-heading text-center">Samples Pending</h4>
                        </div>
                    </div>
                    
                    <?php foreach($sampling_plan as $plan) { ?>
                        <?php 
                            $complete_perc = round(($plan['completed']/$plan['no_of_samples'])*100, 1);
                            $progress_perc = round(($plan['in_progess']/$plan['no_of_samples'])*100, 1);
                        ?>
                        <div class="row realtime-dash-row">
                            <div class="col-md-3">
                                <h4 class="realtime-dash-row-title text-center uppercase font-grey-mint" style="margin:20px 0px;"><?php echo $plan['model_suffix']; ?></h4>
                            </div>
                            <div class="col-md-3">
                                <h4 class="realtime-dash-row-title text-center uppercase font-grey-mint" style="margin:20px 0px;"><?php echo $plan['inspection_name']; ?></h4>
                            </div>
                            <div class="col-md-2">
                                <h4 class="realtime-dash-row-numbers text-center font-red-sunglo" style="margin:20px 0px;"><?php echo $plan['no_of_samples']; ?></h4>
                                
                            </div>
                            <div class="col-md-2">
                                <h4 class="realtime-dash-row-numbers text-center font-green-jungle"><?php echo $plan['completed']; ?></h4>

                                <div class="progress-info">
                                    <div class="progress">
                                        <span class="progress-bar progress-bar-success green-jungle" style="width: <?php echo $complete_perc;?>%;">
                                            <span class="sr-only"><?php echo $complete_perc;?>% progress</span>
                                        </span>
                                    </div>
                                    <div class="status">
                                        <div class="status-number pull-right font-red-sunglo"> <?php echo $complete_perc;?>% </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <h4 class="realtime-dash-row-numbers text-center font-yellow-crusta"><?php echo $plan['in_progess']; ?></h4>
                                <div class="progress-info">
                                    <div class="progress">
                                        <span class="progress-bar progress-bar-success yellow-crusta" style="width: <?php echo $progress_perc;?>%;">
                                            <span class="sr-only"><?php echo $progress_perc;?>% progress</span>
                                        </span>
                                    </div>
                                    <div class="status">
                                        <div class="status-number pull-right font-red-sunglo"> <?php echo $progress_perc;?>% </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    
                </div>
                            
            </div>
            <!-- END REGIONAL STATS PORTLET-->
        </div>
    
    </div>
</div>