<?php if(empty($checkpoints)) { ?>
    <p class="text-center">No Checkpoints.</p>
<?php } else { ?>
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-light table-checkable">
            <thead>
                <tr>
                    <th></th>
                    <th>Checkpoint No.</th>
                    <th>Insp. Item</th>
                    <?php if($inspection['checkpoint_format'] >= 3) { ?>
                        <th>Insp. Item</th>
                    <?php } ?>
                    <?php if($inspection['checkpoint_format'] == 4) { ?>
                        <th>Insp. Item</th>
                    <?php } ?>
                    <th>Insp. Item</th>
                    <th>Spec.</th>
                </tr>
            </thead>
            <tbody>
                <?php $existing = isset($inspection['checkpoints_nos']) ? explode(',', $inspection['checkpoints_nos']) : array(); ?>
                <?php foreach($checkpoints as $checkpoint) { ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="checkboxes" name="checkpoints_nos[]" value="<?php echo $checkpoint['id']; ?>" 
                            <?php if(in_array($checkpoint['id'], $existing)) { ?> checked="checked" <?php } ?>
                            />
                        </td>
                        <td><?php echo $checkpoint['checkpoint_no']; ?></td>
                        <td><?php echo $checkpoint['insp_item']; ?></td>
                            
                        <?php if($inspection['checkpoint_format'] >= 3) { ?>
                            <td><?php echo $checkpoint['insp_item2']; ?></td>
                        <?php } ?>
                        <?php if($inspection['checkpoint_format'] == 4) { ?>
                            <td><?php echo $checkpoint['insp_item4']; ?></td>
                        <?php } ?>
                    
                        <td><?php echo $checkpoint['insp_item3']; ?></td>
                        <td><?php echo $checkpoint['spec']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } ?>