<!-- BEGIN HEADER -->
<?php $page = isset($page) ? $page : ''; ?>
<header class="page-header">
    <nav class="navbar mega-menu" role="navigation">
        <div class="container-fluid">
            <div class="clearfix navbar-fixed-top">
                <!-- Brand and toggle get grouped for better mobile display -->
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="toggle-icon">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </span>
                </button>
                <!-- End Toggle Button -->
                <!-- BEGIN LOGO -->
                <a class="ir" href="<?php echo base_url(); ?>" id="logo" role="banner" title="Home" style="margin-top:0px;height:54px;">LG India</a>
                
                <!-- END LOGO -->
                
                <!-- BEGIN TOPBAR ACTIONS -->
                <div class="topbar-actions">
                    <div style="text-align: right; margin-right: 10px;">
                        <span id="user-info">Welcome, <?php echo $this->session->userdata('name'); ?>
                        <?php if($this->product_id) { ?>
                            <small> &nbsp; [ <?php echo $this->session->userdata('user_type'); ?> User - <?php echo $this->session->userdata('product_name'); ?> ]</small>
                        <?php } else { ?>
                            <small> &nbsp; [ Super Admin ]</small>
                        <?php } ?>
                        </span>
                    
                    </div>
                    <div>
                        
                        <ul class="user-info-links">
                            <?php $allowed_products = $this->session->userdata('products'); ?>
                            <?php if(count($allowed_products) > 1) { ?>
                                <li>
                                    <div class="btn-group">
                                        <a class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown" href="javascript:;"> 
                                            Switch Product
                                            <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <?php foreach($allowed_products as $ap) { ?>
                                                <li>
                                                    <a href="<?php echo base_url().'users/switch_product/'.$ap['id']; ?>"> 
                                                        <?php echo $ap['name']; ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </li>
                            <?php } ?>
                            <li>
                                <a href="<?php echo base_url(); ?>users/change_password" class="btn btn-link btn-sm">
                                    Change Password
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo base_url(); ?>logout" class="btn btn-link btn-sm">
                                    Log Out 
                                </a>
                            </li>
                        </ul>
                        <div style="clear:both;"></div>
                    </div>
                </div>
                <!-- END TOPBAR ACTIONS -->
                
                <div class="page-logo-text page-logo-text-new text-left">OQIS - Outgoing Quality Integrated System</div>
            </div>
            <!-- BEGIN HEADER MENU -->
            <?php if(!isset($no_header_links)) { ?>
                <div class="nav-collapse collapse navbar-collapse navbar-responsive-collapse header-nav-links">
                    <ul class="nav navbar-nav">
                        <li class="<?php if($page == '') { ?>active selected<?php } ?>">
                            <a href="<?php echo base_url(); ?>" target="_blank" class="text-uppercase">
                                <i class="icon-home"></i> Dashboard 
                            </a>
                        </li>
                        
                        <?php if($this->session->userdata('user_type') == 'Admin') { ?>
                            <li class="dropdown more-dropdown <?php if($page == 'masters') { ?>active selected<?php } ?>">
                                <a href="javascript:;" class="text-uppercase">
                                    <i class="icon-layers"></i> Masters 
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo base_url(); ?>users" target="_blank">
                                            <i class="icon-users"></i> Users 
                                        </a>
                                    </li>
                                    <?php if($this->session->userdata('is_super_admin')) { ?>
                                        <li>
                                            <a href="<?php echo base_url(); ?>products" target="_blank">
                                                <i class="icon-briefcase"></i> Products 
                                            </a>
                                        </li>
                                    <?php } else { ?>
                                        <li>
                                            <a href="<?php echo base_url(); ?>products/lines" target="_blank">
                                                <i class="icon-briefcase"></i> Product Lines
                                            </a>
                                        </li>
                                        
                                        <li>
                                            <a href="<?php echo base_url(); ?>products/model_suffixs" target="_blank">
                                                <i class="icon-briefcase"></i> Model.Suffixs
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <a href="<?php echo base_url(); ?>inspections" target="_blank">
                                            <i class="fa fa-search"></i> Inspections 
                                        </a>
                                    </li>
                                    
                                    <?php if($this->product_id) { ?>
                                        <li>
                                            <a href="<?php echo base_url(); ?>inspections/excluded_checkpoints" target="_blank">
                                                <i class="icon-ban"></i> Excluded Checkpoints 
                                            </a>
                                        </li>
                                        
                                        <li>
                                            <a href="<?php echo base_url(); ?>products/phone_numbers" target="_blank">
                                                <i class="fa fa-phone"></i> Phone Numbers
                                            </a>
                                        </li>
                                        
                                        <li>
                                            <a href="<?php echo base_url(); ?>checklist" target="_blank">
                                                <i class="fa fa-list"></i> Checklists
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <li>
                                        <a href="<?php echo base_url(); ?>references" target="_blank">
                                            <i class="fa fa-list"></i> Reference Links 
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>
                        
                        <?php if($this->session->userdata('user_type') == 'Admin') { ?>
                            <li class="dropdown more-dropdown <?php if($page == 'pp') { ?>active selected<?php } ?>">
                                <a href="javascript:;" class="text-uppercase" target="_blank">
                                    <i class="icon-layers"></i> Production Plan 
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo base_url()."sampling/view_production_plan/".date('Y-m-d'); ?>" target="_blank" 
                                           class="text-uppercase">
                                            <i class="icon-layers"></i> Daily
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url(); ?>sampling/production_plan_monthly" target="_blank" 
                                           class="text-uppercase">
                                            <i class="icon-layers"></i> Monthly
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        
                            <li class="<?php if($page == 'sampling_configs') { ?>active selected<?php } ?>">
                                <a href="<?php echo base_url(); ?>sampling/configs" target="_blank" class="text-uppercase">
                                    <i class="icon-wrench"></i> Sampling Configs
                                </a>
                            </li>
                        <?php } ?>
                        
                        <?php if($this->session->userdata('user_type') !== 'Dashboard' && $this->product_id) { ?>
                            <li class="<?php if($page == 'inspections') { ?>active selected<?php } ?>">
                                <a href="<?php echo base_url(); ?>register_inspection" target="_blank" class="text-uppercase">
                                    <i class="icon-magnifier"></i> Product Inspection 
                                </a>
                            </li>
                        <?php } ?>
                        
                        <?php if($this->session->userdata('user_type') == 'Dashboard') { ?>
                            <li class="<?php if($page == 'realtime') { ?>active selected<?php } ?>">
                                <a href="<?php echo base_url(); ?>dashboard/realtime" target="_blank" class="text-uppercase">
                                    <i class="icon-layers"></i> Realtime
                                </a>
                            </li>
                        <?php } ?>

                        <li class="dropdown more-dropdown <?php if($page == 'reports') { ?>active selected<?php } ?>">
                            <a href="javascript:;" class="text-uppercase">
                                <i class="icon-layers"></i> Reports 
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo base_url(); ?>reports" target="_blank" class="text-uppercase">
                                        <i class="icon-layers"></i> Audit Reports
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>reports/lar_report" target="_blank" class="text-uppercase">
                                        <i class="icon-layers"></i> LAR Report
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url(); ?>reports/sampling_ppm_report" target="_blank" class="text-uppercase">
                                        <i class="icon-layers"></i> Sampling PPM Report
                                    </a>
                                </li>
                                <?php if($this->session->userdata('user_type') == 'Admin') { ?>
                                    <li>
                                        <a href="<?php echo base_url(); ?>reports/serial_nos" target="_blank" class="text-uppercase">
                                            <i class="icon-layers"></i> Serial Nos
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url(); ?>reports/pending" target="_blank" class="text-uppercase">
                                            <i class="icon-layers"></i> Pending Inspections
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url(); ?>reports/mpat_status" target="_blank" class="text-uppercase">
                                            <i class="icon-layers"></i> MPAT Status
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url(); ?>reports/edit" target="_blank" class="text-uppercase">
                                            <i class="icon-layers"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo base_url(); ?>reports/completed_inspections" target="_blank" class="text-uppercase">
                                            <i class="icon-layers"></i> Completed Inspections
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="<?php echo base_url().'dashboard/na_checkpoints'; ?>" target="_blank" class="text-uppercase">
                                            <i class="icon-magnifier"></i> NA Checkpoint
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="<?php echo base_url().'reports/gmes'; ?>" target="_blank" class="text-uppercase">
                                            <i class="icon-magnifier"></i> GMES
                                        </a>
                                    </li>
                                <?php } ?>
                                
                            </ul>
                        </li>
                        
                        <?php if($this->session->userdata('is_super_admin')) { ?>
                            <li class="">
                                <a href="<?php echo base_url().'reports/product_status'; ?>" target="_blank" class="text-uppercase">
                                    <i class="icon-home"></i> Product Status
                                </a>
                            </li>
                        <?php } ?>
                        
                        <?php if($this->session->userdata('user_type') != 'Admin') { ?>    
                            <li class="">
                                <a href="<?php echo base_url().'auditer/upload_automate_excel'; ?>" target="_blank" class="text-uppercase">
                                    <i class="icon-home"></i> Upload Automate Excel 
                                </a>
                            </li>
                        <?php } else { ?>
                            
                            
                        <?php } ?>
                        
                        <?php /*if(!empty($references)) { ?>
                            <li class="dropdown dropdown-fw active selected open">
                                <a href="javascript:;" class="text-uppercase">
                                    <i class="icon-layers"></i> Reference Links 
                                </a>
                                <ul class="dropdown-menu dropdown-menu-fw">
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
                                                <i class="icon-users"></i> <?php echo $reference['name']; ?> 
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            
                        <?php }*/ ?>
                        
                        <!--
                        <li class="dropdown more-dropdown">
                            <a href="javascript:;" class="text-uppercase"> More </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#">Link 1</a>
                                </li>
                                <li>
                                    <a href="#">Link 2</a>
                                </li>
                                <li>
                                    <a href="#">Link 3</a>
                                </li>
                                <li>
                                    <a href="#">Link 4</a>
                                </li>
                                <li>
                                    <a href="#">Link 5</a>
                                </li>
                            </ul>
                        </li>
                        -->
                    </ul>
                </div>
            <?php } ?>
            <!-- END HEADER MENU -->
        </div>
        <!--/container-->
    </nav>
</header>
<!-- END HEADER -->