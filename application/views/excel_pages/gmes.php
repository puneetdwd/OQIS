<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
   <table>
        <tr>
			<td colspan="5">
				<h1>GMES Integration</h1>
			</td>
			<td>
				<?php echo "<b>Date: </b>" . date("d M Y"); ?>
			</td>
		</tr>
		<tr>&nbsp;</tr>
    </table>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-reorder"></i>List of Inspections
                            </div>
                        </div>
                        <div class="portlet-body">
                          <div class="table-responsive">
                                   
                                    <table class="table table-hover table-light" border="1" style="width:100%;border-collapse:collapse">
                                        <thead>
                                            <tr style="background-color:'#C0C0C0';color:'#000'">
                                                <th>Date</th>
                                                <th>Inspection</th>
                                                <th>Workorder</th>
                                                <th>Line</th>
                                                <th>Model.Suffix</th>
                                                <th>Tool</th>
                                                <th>Serial No.</th>
                                                <th>Judgement</th>
                                                <th class="no_sort"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($audits as $audit) { ?>
                                                <tr>
                                                    <td nowrap><?php echo date('jS M, y', strtotime($audit['audit_date'])); ?></td>
                                                    <td><?php echo $audit['inspection_name']; ?></td>
                                                    <td><?php echo $audit['workorder']; ?></td>
                                                    <td><?php echo $audit['line']; ?></td>
                                                    <td><?php echo $audit['model_suffix']; ?></td>
                                                    <td><?php echo $audit['tool']; ?></td>
                                                    <td><?php echo $audit['serial_no']; ?></td>
                                                    <td class="judgement-col">
                                                        <img src="<?php echo base_url(); ?>assets/global/img/loading-spinner-grey.gif" alt="" class="loading" style="display:none;">
                                                    </td>
                                                    <td nowrap>
                                                        <!--
                                                        <a class="button small gray" 
                                                            href="<?php echo base_url()."reports/send_to_gmes/".$audit['id'];?>">
                                                            <i class="fa fa-edit"></i> GMES
                                                        </a>
                                                        -->
                                                        <a class="check-judgement-button button small gray" href="<?php echo base_url().'reports/check_judgement/'.$audit['id']; ?>" data-id="<?php echo $audit['id']; ?>" style="display:none;"> 
                                                            check
                                                        </a>
                                                        
                                                        <a class="button small gray" href="<?php echo base_url().'reports/send_to_gmes_view/'.$audit['id']; ?>" data-target="#ajax" data-toggle="modal"> 
                                                            Send
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                        </div>
                    </div>
               
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

<div class="modal fade" id="ajax" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img src="<?php echo base_url(); ?>assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;Loading... </span>
            </div>
        </div>
    </div>
</div>

<?php if(!empty($audits)) { ?>
    <script>
        $(window).load(function() {
            $('.check-judgement-button:first').trigger('click');
            
            $('.pagination-sec').bootpag({
                total: <?php echo $total_page; ?>,
                page: <?php echo $page_no; ?>,
                maxVisible: 5,
                leaps: true,
                firstLastUse: true,
                first: '←',
                last: '→',
                wrapClass: 'pagination',
                activeClass: 'active',
                disabledClass: 'disabled',
                nextClass: 'next',
                prevClass: 'prev',
                lastClass: 'last',
                firstClass: 'first'
            }).on("page", function(event, num){
                show_page(num); // or some ajax content loading...
            }); 
        });
    </script>
<?php } ?>