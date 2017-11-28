<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">ModelWise Pending - <?php echo $inspection['name'].', Date - '.date("jS M'y", strtotime($date)); ?></h4>
</div>
<div class="modal-body">
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th> Model.Suffix </th>
                    <th> Count </th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0;?>
                <?php foreach($reports as $report) { ?>
                    <?php 
                        $pending = $report['samples']-$report['total_audits'];
                        if($pending <= 0) { continue; }
                        $total += $pending;
                    ?>
                    <tr>
                        <td><?php echo $report['model_suffix']; ?></td>
                        <td><?php echo $pending; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>Total</td>
                    <td><?php echo $total; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
        
</div>
    
<div class="modal-footer">
    <button class="button" type="submit" name="image" value="true">Submit</button>
    <button type="button" class="attach-guideline-modal-close button white" data-dismiss="modal">Close</button>
</div>