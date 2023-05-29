<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Guest</a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('tenants'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
        <a href="<?php echo base_url(); ?>add_tenant">
            <button type="button" class="btn btn-inverse"><i class="fa fa-plus"></i> Guest</button>
        </a>
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-12 -->
        <div class="col-lg-12">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <div class="panel-body">
                    <table id="data-table-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th class="text-nowrap"><?php echo $this->lang->line('name'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('mobile'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('address'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('emergency_person'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('emergency_contact'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('updated_on'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('updated_by'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('status'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            $this->db->order_by('timestamp', 'desc');
                            $tenants = $this->db->get('tenant')->result_array();
                            foreach ($tenants as $tenant) :
                            ?>
                                <tr>
                                    <td width="1%"><?php echo $count++; ?></td>
                                   
                                    <td><?php echo html_escape($tenant['name']); ?></td>
                                   
                                    <td><?php echo $tenant['mobile_number'] ? html_escape($tenant['mobile_number']) : 'N/A'; ?></td>
                   
                                    <td><?php echo $tenant['home_address'] ? html_escape($tenant['home_address']) : 'N/A'; ?></td>
                                    <td><?php echo $tenant['emergency_person'] ? html_escape($tenant['emergency_person']) : 'N/A'; ?></td>
                                    <td><?php echo $tenant['emergency_contact'] ? html_escape($tenant['emergency_contact']) : 'N/A'; ?></td>
                                    <td><?php echo date('d M, Y', $tenant['timestamp']); ?></td>
                                    <td>
                                        <?php
                                        $user_type =  $this->db->get_where('user', array('user_id' => $tenant['updated_by']))->row()->user_type;
                                        if ($user_type == 1) {
                                            echo 'Admin';
                                        } else {
                                            $person_id = $this->db->get_where('user', array('user_id' => $tenant['updated_by']))->row()->person_id;
                                            echo html_escape($this->db->get_where('staff', array('staff_id' => $person_id))->row()->name);
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($tenant['status'])
                                            echo '<span class="badge badge-primary">' . $this->lang->line('active') . '</span>';
                                        else
                                            echo '<span class="badge badge-warning">' . $this->lang->line('inactive') . '</span>';
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-white btn-xs"><?php echo $this->lang->line('action'); ?></button>
                                            <button type="button" class="btn btn-white btn-xs dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <!-- <a class="dropdown-item" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_show_tenant_details/<?php echo $tenant['tenant_id']; ?>');">
                                                    <?php echo $this->lang->line('details'); ?>
                                                </a> -->
                                               
                                               
                                            
                                                <a class="dropdown-item" href="javascript:;" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_edit_tenant/<?php echo $tenant['tenant_id']; ?>');">
                                                    <?php echo $this->lang->line('edit'); ?>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <?php if ($tenant['status']) : ?>
                                                    <a class="dropdown-item" href="javascript:;" onclick="deactivate_modal('<?php echo base_url(); ?>tenants/deactivate/<?php echo $tenant['tenant_id']; ?>');">
                                                    <?php echo $this->lang->line('deactivate'); ?>
                                                    </a>
                                                <?php endif; ?>
                                                <a class="dropdown-item" href="javascript:;" onclick="confirm_modal('<?php echo base_url(); ?>tenants/remove/<?php echo $tenant['tenant_id']; ?>');">
                                                <?php echo $this->lang->line('remove'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
        </div>
        <!-- end col-12 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->

<style>
    .hover_img a {
        position: relative;
    }

    .hover_img a span {
        position: absolute;
        display: none;
        z-index: 99;
    }

    .hover_img a:hover span {
        display: block;
    }
</style>