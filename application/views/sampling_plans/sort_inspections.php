<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Sort Inspections
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url().'sampling/configs'; ?>">Sampling Configuration</a>
            </li>
            <li class="active">Sort Inspections</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered sampling-sort-inspection-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Sort Inspections
                    </div>
                </div>

                <div class="portlet-body form">
                    <form role="form" class="validate-form" method="post">
                        <div class="form-body">
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                            </div>

                            <?php if(isset($error)) { ?>
                                <div class="alert alert-danger">
                                    <i class="fa fa-times"></i>
                                    <?php echo $error; ?>
                                </div>
                            <?php } ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Inspection</th>
                                                <th>Sort Index</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($inspection1 as $inspection) { ?>
                                                <tr>
                                                    <td><?php echo $inspection['name']; ?></td>
                                                    <td>
                                                        <input type="text" class="form-control input-sm" name="sort_index[<?php echo $inspection['id']; ?>]"
                                                        value="<?php echo isset($inspection['sort_index']) ? $inspection['sort_index'] : ''; ?>">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Inspection</th>
                                                <th>Sort Index</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($inspection2 as $inspection) { ?>
                                                <tr>
                                                    <td><?php echo $inspection['name']; ?></td>
                                                    <td>
                                                        <input type="text" class="form-control input-sm" name="sort_index[<?php echo $inspection['id']; ?>]"
                                                        value="<?php echo isset($inspection['sort_index']) ? $inspection['sort_index'] : ''; ?>">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'sampling/configs'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>