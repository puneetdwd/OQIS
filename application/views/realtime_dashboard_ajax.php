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