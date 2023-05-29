<!-- begin #sidebar -->
<div id="sidebar" class="sidebar" data-disable-slide-animation="true">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">
        <!-- begin sidebar user -->
        <ul class="nav">
            <li class="nav-profile">
                <div class="text-center">
                    <div class="cover with-shadow"></div>
                    <div class="image">
                        <?php
                        $sidebar_user_type =  $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->user_type;
                        if ($sidebar_user_type == 1) {
                            echo '<img src="' . base_url() . 'uploads/website/' . $this->db->get_where('setting', array('name' => 'favicon'))->row()->content . '" alt="Mars Room Management System"' . '/>';
                        } else if ($sidebar_user_type == 2) {
                            echo '<img src="' . base_url() . 'uploads/website/' . $this->db->get_where('setting', array('name' => 'favicon'))->row()->content . '" alt="Mars Room Management System"' . '/>';
                        } else {
                            $sidebar_person_id = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->person_id;
                            $sidebar_tenant_image = $this->db->get_where('tenant', array('tenant_id' => $sidebar_person_id))->row()->image_link;

                            if ($header_tenant_image)
                                echo '<img src="' . base_url() . 'uploads/tenants/' . $header_tenant_image . '" alt="Mars Room Management System"' . '/>';
                            else
                                echo '<img src="' . base_url() . 'uploads/website/' . $this->db->get_where('setting', array('name' => 'favicon'))->row()->content . '" alt="Mars Room Management System"' . '/>';
                        }

                        ?>
                    </div>
                    <div class="info">
                        <?php
                        if ($sidebar_user_type == 1) {
                            echo $this->lang->line('admin');
                        } else if ($sidebar_user_type == 2) {
                            $sidebar_person_id = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->person_id;
                            echo html_escape($this->db->get_where('staff', array('staff_id' => $sidebar_person_id))->row()->name);
                        } else {
                            $sidebar_person_id = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->person_id;
                            echo html_escape($this->db->get_where('tenant', array('tenant_id' => $sidebar_person_id))->row()->name);
                        }
                        ?>
                        <small>
                            <?php
                            if ($sidebar_user_type == 1) {
                                echo $this->lang->line('admin');
                            } else if ($sidebar_user_type == 2) {
                                $sidebar_person_id = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->person_id;
                                if ($this->db->get_where('staff', array('staff_id' => $sidebar_person_id))->row()->role)
                                    echo html_escape($this->db->get_where('staff', array('staff_id' => $sidebar_person_id))->row()->role);
                                else
                                echo $this->lang->line('staff');
                            } else {
                                $sidebar_person_id = $this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->person_id;
                                $profession_id = $this->db->get_where('tenant', array('tenant_id' => $sidebar_person_id))->row()->profession_id;
                                if ($profession_id && $this->db->get_where('profession', array('profession_id' => $profession_id))->num_rows() > 0)
                                    echo html_escape($this->db->get_where('profession', array('profession_id' => $profession_id))->row()->name);
                                else
                                    echo $this->lang->line('tenant');
                            }
                            ?>
                        </small>
                    </div>
                </div>
            </li>
        </ul>
        <!-- end sidebar user -->
        <!-- begin sidebar nav -->
        <ul class="nav">
            <li class="nav-header"><?php echo $this->lang->line('navigation'); ?></li>
            <?php if (in_array($this->db->get_where('module', array('module_name' => 'dashboard'))->row()->module_id, $this->session->userdata('permissions'))) : ?>
                <li class="<?php if ($page_name == 'dashboard') echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>">
                        <i class="fa fa-home"></i>
                        <span><?php echo $this->lang->line('dashboard'); ?></span>
                    </a>
                </li>
            <?php endif; ?>
            <?php
            if (!in_array($this->db->get_where('module', array(
                'module_name' => 'generate_invoice'
            ))->row()->module_id, $this->session->userdata('permissions')) && !in_array($this->db->get_where('module', array(
                'module_name' => 'Invoices'
            ))->row()->module_id, $this->session->userdata('permissions'))) :
            ?>

            <?php else : ?>
                <li class="has-sub <?php if ($page_name == 'generate_invoice' || $page_name == 'monthly_invoices' || $page_name == 'single_month_invoices' || $page_name == 'tenant_invoices' || $page_name == 'service_invoice' || $page_name == 'room_invoice' || $page_name == 'paid_invoices' || $page_name == 'unpaid_invoices' || $page_name == 'invoice') echo 'active'; ?>">
                    <a href="javascript:;">
                        <b class="caret"></b>
                        <i class="far fa-credit-card"></i>
                        <span><?php echo $this->lang->line('invoices'); ?></span>
                    </a>
                    <ul class="sub-menu">
                        
                        <?php if (in_array($this->db->get_where('module', array('module_name' => 'invoices'))->row()->module_id, $this->session->userdata('permissions'))) : ?>
                            <li class="<?php if ($page_name == 'room_invoice' || $page_name == 'single_month_invoices') echo 'active'; ?>">
                                <a href="<?php echo base_url(); ?>room_invoices">Room invoice</a>
                            </li>
                            <?php if ($this->session->userdata('user_type') != 3 && (count($this->db->get('tenant')->result_array()) > 0)) : ?>
                                <li class="<?php if ($page_name == 'service_invoice') echo 'active'; ?>">
                                    <a href="<?php echo base_url(); ?>services_invoices">Service invoice</a>
                                </li>
                            <?php endif; ?>
                           
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if (in_array($this->db->get_where('module', array('module_name' => 'tenants'))->row()->module_id, $this->session->userdata('permissions'))) : ?>
                <li class="<?php if ($page_name == 'add_tenant' || $page_name == 'tenants' || $page_name == 'active_tenants' || $page_name == 'inactive_tenants') echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>tenants">
                        <i class="fa fa-users"></i>
                        <span><?php echo $this->lang->line('tenants'); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (in_array($this->db->get_where('module', array('module_name' => 'rooms'))->row()->module_id, $this->session->userdata('permissions'))) : ?>
                <li class="<?php if ($page_name == 'add_room' || $page_name == 'rooms' || $page_name == 'occupied_rooms' || $page_name == 'unoccupied_rooms') echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>rooms">
                        <i class="fa fa-building"></i>
                        <span><?php echo $this->lang->line('rooms'); ?></span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="<?php if ($page_name == 'services') echo 'active'; ?>">
                <a href="<?php echo base_url(); ?>service_settings">
                    <i class="fa fa-list-ol"></i>
                    <span><?php echo $this->lang->line('services'); ?></span>
                </a>
            </li>


           
            <!--  -->
            <li class="has-sub <?php if ($page_name == 'board_member_settings' || $page_name == 'payment_method_settings' || $page_name == 'service_settings' || $page_name == 'website_settings' || $page_name == 'profession_settings' || $page_name == 'id_type_settings' || $page_name == 'profile_settings') echo 'active'; ?>">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="fa fa-cog"></i>
                    <span><?php echo $this->lang->line('settings'); ?></span>
                </a>
                <ul class="sub-menu">
                    <?php if (in_array($this->db->get_where('module', array('module_name' => 'settings'))->row()->module_id, $this->session->userdata('permissions'))) : ?>
                        <li class="<?php if ($page_name == 'website_settings') echo 'active'; ?>">
                            <a href="<?php echo base_url(); ?>website_settings"><?php echo $this->lang->line('website'); ?></a>
                        </li>
                        
                       
                       
                    <?php endif; ?>
                    <li class="<?php if ($page_name == 'profile_settings') echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>profile_settings"><?php echo $this->lang->line('profile'); ?></a>
                    </li>
                </ul>
            </li>
          
        </ul>
        <!-- end sidebar nav -->
    </div>
    <!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg"></div>
<!-- end #sidebar -->