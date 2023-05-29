<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('room_invoices'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
    <?php echo $this->lang->line('room_invoices_header'); ?>
   
    </h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        <!-- begin col-9 -->
        <div class="col-lg-9">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <div class="panel-body">
                    <table id="data-table-buttons" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th class="text-nowrap"><?php echo $this->lang->line('invoice'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('tenant_name'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('tenant_mobile'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('status'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('room'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('amount'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('date_start'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('date_end'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('created_on'); ?></th>
                                <?php if ($this->session->userdata('user_type') != 3) : ?>
                                    <th class="text-nowrap"><?php echo $this->lang->line('options'); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                              $count = 1;
                              $this->db->select('*,room_invoice.lease_start as date_start, room_invoice.lease_end as date_end,room_invoice.created_on as date_created,room_invoice.status as in_status');
                              $this->db->join('tenant', 'tenant.tenant_id = room_invoice.tenant_id');
                              $this->db->join('room', 'room.room_id = room_invoice.room_id');
                              $query = $this->db->get('room_invoice');
                              $datas = $query->result_array();
                              foreach ($datas as $row):?>
                              <?php 

                                if(date('M d, Y',$row['date_start']) == date('M d, Y')){

                                    $data['status']	= 1;

                                    $datar['status'] =	1;
    
                                    $this->db->where('room_id', $row['room_id']);
                                    $this->db->update('room', $datar);
                                    $this->db->where('idx', $row['idx']);
                                    $this->db->update('room_invoice', $data);

                                }

                                if(date('M d, Y',$row['date_created']) == date('M d, Y')){

                                    $data['status']	= 0;

                                    $datar['status'] =	0;
    
                                    $this->db->where('room_id', $row['room_id']);
                                    $this->db->update('room', $datar);
                                    $this->db->where('idx', $row['idx']);
                                    $this->db->update('room_invoice', $data);

                                }
                               
                             ?>
                              <tr>
                                <td><?=$count++ ?></td>
                                <td><?= $row['invoice_number']?></td>
                                <td><?=$row['name']?></td>
                                <td><?=$row['mobile_number']?></td>
                                <td>
                                    <?php
                                    if ($row['in_status'] == 1)
                                        echo '<span class="badge badge-primary">' . $this->lang->line('occupied') . '</span>';
                                    elseif ($row['in_status'] == 0)
                                        echo '<span class="badge badge-warning">' . $this->lang->line('unoccupied') . '</span>';
                                    else
                                        echo '<span class="badge badge-danger"> Cancel</span>';
                                    ?>
                                </td>
                                <td><?=$row['room_number']?></td>
                                <td>P <?=$row['total_amount']?></td>
                                <td><?=date('M d, Y',$row['date_start'])?></td>
                                <td><?=date('M d, Y',$row['date_end'])?></td>
                                <td><?php echo date('M d, Y',$row['date_created']); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-white btn-xs"><?php echo $this->lang->line('action'); ?></button>
                                        <button type="button" class="btn btn-white btn-xs dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item edit" href="javascript:;" data-idx="<?= $row['idx']?>" data-room="<?= $row['room_id']?>" data-tenant="<?= $row['tenant_id']?>" data-status="<?= $row['in_status']?>" data-date_start="<?=date('m/d/Y',$row['date_start'])?>" data-date_end="<?=date('m/d/Y',$row['date_end'])?>">
                                                <?php echo $this->lang->line('edit'); ?>
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="javascript:;" onclick="confirm_cancel_modal('<?php echo base_url(); ?>room_invoices/cancel/<?php echo $row['idx'].'/'.$row['room_id']; ?>');">
                                            Cancel
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
        <!-- end col-9 -->
        <!-- begin col-3 -->
        <div class="col-lg-3">
            <!-- begin panel -->
            <div class="panel panel-inverse">
                <!-- begin panel-body -->
                <div class="panel-body">
                <?php echo form_open('room_invoices/add', array('method' => 'post', 'data-parsley-validate' => 'ture')); ?>
                    <input type="hidden" id="idx" name="idx" value="0"/>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('room'); ?> *</label>
                        <div>
                            <select style="width: 100%" class="form-control default-select2" name="room_id" data-parsley-required="true" id="room">
                                <option value=""><?php echo $this->lang->line('select_room'); ?></option>
                        <?php
                              $room_invoices = $this->db->get_where('room', array())->result_array();
                              foreach ($room_invoices as $room_invoice):?>
                              
                                <option value="<?=$room_invoice['room_id'] ?>"><?=$room_invoice['room_number'] ?></option>

                        <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('tenant'); ?> *</label>
                        <div>
                            <select style="width: 100%" class="form-control default-select2" name="tenant_id" data-parsley-required="true" id="tenant">
                                <option value=""><?php echo $this->lang->line('select_tenant'); ?></option>
                        <?php
                              $this->db->select('*');
                              $query = $this->db->get('tenant');
                              $room_invoices = $query->result_array();
                              foreach ($room_invoices as $room_invoice):?>
                              
                                <option value="<?=$room_invoice['tenant_id'] ?>"><?=$room_invoice['name'] ?></option>

                        <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" >
                        <label><?php echo $this->lang->line('lease_period'); ?> *</label>
                        <div class="input-group input-daterange">
                            <input type="text" class="form-control" id="lease_start" name="lease_start" placeholder="<?php echo $this->lang->line('date_start'); ?>" data-parsley-required="true"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="form-control" id="lease_end" name="lease_end" placeholder="<?php echo $this->lang->line('date_end'); ?>" data-parsley-required="true"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('status'); ?> *</label>
                        <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="status" id="in_status">
                            <option value=""><?php echo $this->lang->line('select_status'); ?></option>
                            <option value="1"><?php echo $this->lang->line('active'); ?></option>
                            <option value="0"><?php echo $this->lang->line('inactive'); ?></option>
                        </select>
                    </div>

                    <button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('submit'); ?></button>
                    <?php echo form_close(); ?>
                </div>
                <!-- end panel-body -->
            </div>
            <!-- end panel -->
           
        </div>
        <!-- end col-3 -->
    </div>
    <!-- end row -->
</div>
<!-- end #content -->
<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery-3.2.1.min.js"></script>
<script>
    function showInvoiceModal(invoice_id) {
        $.ajax({
            url: "<?php echo base_url(); ?>generate_invoice_pdf/" + invoice_id,
            success: function(result) {
                // console.log(result);
            }
        });

        showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_show_invoice_pdf/' + invoice_id);
    } 

    $('.edit').click(function(e){
        e.preventDefault();

        var idx = $(this).attr('data-idx');
        var in_status = $(this).attr('data-status');
        var room = $(this).attr('data-room');
        var tenant = $(this).attr('data-tenant');
        var lease_start =$(this).attr('data-date_start');
        var lease_end = $(this).attr('data-date_end');


        $("#idx").val(idx);
        $("#in_status").val(in_status).trigger('change');
        $("#room").val(room).trigger('change');
        $("#tenant").val(tenant).trigger('change');
        $("#lease_start").val(lease_start);
        $("#lease_end").val(lease_end);

    });

   
</script>