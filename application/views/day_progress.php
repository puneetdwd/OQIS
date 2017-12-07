<style type="text/css">
    
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 800px;
        margin: 0 auto;
    }
    .fix-height{
        height: 90px !important;
    }
    .fht-table{
        background: #e9edef;
    }
</style>
<div class="col-md-12">
    <div class="mt-element-ribbon bg-grey-steel">
        
        <?php if(!isset($export)) { ?>
            <div class="">
                <div class="col-md-1 col-md-offset-10" style="margin-top: -10px;">
                    <?php if(!empty($sampling_plan)) { ?>
                        <a class="button normals btn-circle" href="<?php echo base_url()."dashboard/export_excel"; ?>">
                            <i class="fa fa-download"></i> Excel
                        </a>
                    <?php } ?>
                </div>
                <div class="col-md-1" style="margin-top: -10px;">
                    <?php if($this->session->userdata('user_type') == 'Admin') { ?>
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/create_sampling_plan/".$this->session->userdata('dashboard_date').'/dashboard'; ?>">
                            <i class="fa fa-refresh"></i> Refresh
                        </a>
                    <?php } ?>
                </div>
            </div>


            <div class="ribbon ribbon-clip ribbon-color-danger">
                <div class="ribbon-sub ribbon-clip"></div> <?php echo date('jS M, Y', strtotime($this->session->userdata('dashboard_date'))); ?>'s PROGRESS 
            </div>
        <?php } ?>
        <div class="ribbon-content main">
            <?php if(empty($sampling_plan)) {?>
                No sampling plan added for <?php echo date('jS M, Y', strtotime($this->session->userdata('dashboard_date'))); ?>.
            <?php } else { ?>
                <div class="table-responsive ContenedorTabla">
                    <table class="table table-hover table-bordered fht-table" id="example"">
                        <thead>
                            <tr>
                                <?php foreach(array_slice($sampling_plan, 0, 1) as $plan) { ?>
                                    <?php foreach($plan as $k => $p) { ?>
                                        <?php if($k <= 5) { ?>
                                            <th rowspan="2" style="vertical-align: middle;" class="fix-height"><?php echo $p; ?></th>
                                        <?php } else { ?>
                                            <th colspan="3" class="text-center"><?php echo $p; ?></th>
                                        <?php } ?>
                                        
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                            <tr>
                                <?php foreach(array_slice($sampling_plan, 0, 1) as $plan) { ?>
                                    <?php foreach($plan as $k => $p) { ?>
                                        
                                        <?php if($k > 5) { ?>
                                            <th>Planned</th>
                                            <th>Completed</th>
                                            <th>In Progress</th>
                                        <?php } ?>
                                        
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(array_slice($sampling_plan, 1) as $plan) { ?>
                                <tr>
                                    <?php foreach($plan as $k => $p) { ?>
                                        <?php if($p == 'skip') { continue; } ?>
                                        
                                        <?php if(0 === strpos($p, '<td')) { ?>
                                            <?php echo $p; ?>
                                        <?php } else { ?>
                                            <td><?php echo $p; ?></td>
                                        <?php } ?>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        if($('.dashboard-progress-section tr').length > 0) {
            if($('i.fa-refresh').length == 0) {
                $('#dashboard-approve-decline').show();
            }
        }
    });
</script>