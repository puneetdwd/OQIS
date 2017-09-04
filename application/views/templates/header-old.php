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
                <a id="index" class="page-logo" href="<?php echo base_url(); ?>">
                    <img src="<?php echo base_url(); ?>assets/images/medium_logo.png" alt="Logo" style="margin-top:-5px;"> &nbsp; &nbsp;
                    <span class="page-logo-text" style="font-size: 17px;">OQIS - Outgoing Quality Integrated System</span>
                </a>
                <!-- END LOGO -->
                
                <!-- BEGIN TOPBAR ACTIONS -->
                <div class="topbar-actions">
                    <!-- BEGIN USER PROFILE -->
                    <div class="btn-group-img btn-group">
                        <button type="button" class="btn btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <span style="font-size: 16px;">Hi, <?php echo $this->session->userdata('name'); ?>
                            <?php if($this->product_id) { ?>
                                <small> &nbsp; &nbsp; ( <?php echo $this->session->userdata('user_type'); ?> User, <?php echo $this->session->userdata('product_name'); ?> )</small>
                            <?php } else { ?>
                                <small> &nbsp; &nbsp; ( Super Admin )</small>
                            <?php } ?>
                            </span>
                        </button>
                        <ul class="dropdown-menu-v2" role="menu">
                            <li>
                                <a href="<?php echo base_url(); ?>users/change_password">
                                    <i class="fa fa-wrench"></i> Change Password
                                </a>
                            </li>
                            <li class="divider"> </li>
                            <li>
                                <a href="<?php echo base_url(); ?>logout">
                                    <i class="fa fa-key"></i> Log Out 
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- END USER PROFILE -->
                </div>
                <!-- END TOPBAR ACTIONS -->
            </div>
            <!-- BEGIN HEADER MENU -->
            <?php if(!isset($no_header_links)) { ?>
                <div class="nav-collapse collapse navbar-collapse navbar-responsive-collapse">
                    <ul class="nav navbar-nav">
                        <li class="<?php if($page == '') { ?>active selected<?php } ?>">
                            <a href="<?php echo base_url(); ?>" class="text-uppercase">
                                <i class="icon-home"></i> Dashboard 
                            </a>
                        </li>
                        
                        <?php if($this->session->userdata('user_type') == 'Admin') { ?>
                            <li class="dropdown dropdown-fw <?php if($page == 'masters') { ?>active selected open<?php } ?>">
                                <a href="javascript:;" class="text-uppercase">
                                    <i class="icon-layers"></i> Masters 
                                </a>
                                <ul class="dropdown-menu dropdown-menu-fw">
                                    <li>
                                        <a href="<?php echo base_url(); ?>users">
                                            <i class="icon-users"></i> Users 
                                        </a>
                                    </li>
                                    <?php if(!$this->product_id) { ?>
                                        <li>
                                            <a href="<?php echo base_url(); ?>products">
                                                <i class="icon-briefcase"></i> Products 
                                            </a>
                                        </li>
                                    <?php } else { ?>
                                        <li>
                                            <a href="<?php echo base_url(); ?>products/lines">
                                                <i class="icon-briefcase"></i> Product Lines
                                            </a>
                                        </li>
                                        
                                        <li>
                                            <a href="<?php echo base_url(); ?>products/model_suffixs">
                                                <i class="icon-briefcase"></i> Model.Suffixs
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <a href="<?php echo base_url(); ?>inspections">
                                            <i class="fa fa-search"></i> Inspections 
                                        </a>
                                    </li>
                                    
                                    <?php if($this->product_id) { ?>
                                        <li>
                                            <a href="<?php echo base_url(); ?>inspections/excluded_checkpoints">
                                                <i class="icon-ban"></i> Excluded Checkpoints 
                                            </a>
                                        </li>
                                        
                                        <li>
                                            <a href="<?php echo base_url(); ?>sampling">
                                                <i class="fa fa-list"></i> Production Plan 
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <li>
                                        <a href="<?php echo base_url(); ?>references">
                                            <i class="fa fa-list"></i> Reference Links 
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } else if($this->session->userdata('user_type') == 'Audit') { ?>
                            <li class="<?php if($page == 'inspections') { ?>active selected<?php } ?>">
                                <a href="<?php echo base_url(); ?>register_inspection" class="text-uppercase">
                                    <i class="icon-magnifier"></i> Product Inspection 
                                </a>
                            </li>
                        <?php } ?>
                        
                        <?php if($this->session->userdata('user_type') == 'Dashboard') { ?>
                            <li class="<?php if($page == 'realtime') { ?>active selected<?php } ?>">
                                <a href="<?php echo base_url(); ?>dashboard/realtime" class="text-uppercase">
                                    <i class="icon-layers"></i> Realtime
                                </a>
                            </li>
                        <?php } ?>
                        
                        <?php if($this->session->userdata('user_type') != 'Admin') { ?>
                            <li class="<?php if($page == 'reports') { ?>active selected<?php } ?>">
                                <a href="<?php echo base_url(); ?>reports" class="text-uppercase">
                                    <i class="icon-layers"></i> Reports
                                </a>
                            </li>
                        <?php } ?>
                        
                        <?php if(!empty($references)) { ?>
                            <li class="dropdown dropdown-fw active selected open">
                                <a href="javascript:;" class="text-uppercase">
                                    <i class="icon-layers"></i> Reference Links 
                                </a>
                                <ul class="dropdown-menu dropdown-menu-fw">
                                    <?php foreach($references as $reference) { ?>
                                        <li>
                                            <?php if(!empty($reference['reference_file'])) { ?>
                                                <a href="<?php echo base_url().$reference['reference_file']; ?>" target="_blank">
                                            <?php } else { ?>
                                                <a href="<?php echo $reference['reference_url']; ?>" target="_blank">
                                            <?php } ?>
                                                <i class="icon-users"></i> <?php echo $reference['name']; ?> 
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            
                        <?php } ?>
                        
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