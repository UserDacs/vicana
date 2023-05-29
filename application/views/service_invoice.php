<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('services_invoices'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
    <?php echo $this->lang->line('services_invoices'); ?>
   
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
                                <th class="text-nowrap"><?php echo $this->lang->line('amount'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('date_start'); ?></th>
                                <th class="text-nowrap"><?php echo $this->lang->line('created_on'); ?></th>
                                <?php if ($this->session->userdata('user_type') != 3) : ?>
                                    <th class="text-nowrap"><?php echo $this->lang->line('service'); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                              $count = 1;
                              $this->db->select('*,service.name as sername ,tenant.name as tname,services_invoice.lease_start as date_start,services_invoice.created_on as date_created,services_invoice.status as in_status,SUM(`total_amount`) as SM ');
                              
                            
                              $this->db->join('services_invoice', 'service.service_id = services_invoice.service_id');
                              $this->db->join('tenant', 'tenant.tenant_id = services_invoice.tenant_id');
                              $this->db->group_by("services_invoice.lease_start");
                              $query = $this->db->get('service');
                              $datas = $query->result_array();
                              foreach ($datas as $row):?>
                             
                              <tr>
                                <td><?=$count++ ?></td>
                                <td><?= $row['invoice_number']?></td>
                                <td><?=$row['tname']?></td>
                              
                                <td>P <?=$row['SM']?></td>
                                <td><?=date('M d, Y',$row['date_start'])?></td>
                                <td><?php echo date('M d, Y',$row['date_created']); ?></td>
                                
                                </td>
                                <td width="20%">
                                    <?php 

                                    $this->db->select('*,services_invoice.status as in_status');
                                    $this->db->join('service', 'service.service_id = services_invoice.service_id');
                                    $query = $this->db->get('services_invoice');
                                    $sers = $query->result_array();
                                    foreach ($sers as $ser):?>

                                        <?php 
                                        if ($row['tenant_id'] == $ser['tenant_id']) {
                                            $st = "";
                                            $color = "";
                                            if ($ser['in_status'] == 1){
                                                $st =  $this->lang->line('occupied');
                                                $color = 'primary';
                                            }elseif ($ser['in_status'] == 0){
                                                $st = $this->lang->line('unoccupied');
                                                $color = 'warning';
                                            }else{
                                                $color = 'danger';
                                                $st = 'Cancel';

                                            }
                                                
                                            echo  $ser['name'] .' - ' .$ser['count'];
                                            if ($ser['count']==0) {
                                                $color = 'danger';
                                                $st = 'Cancel';
                                            }
                                            ?>

                                           &nbsp; &nbsp; <div class="btn-group" style="margin-top: 5px;">
                                                <button type="button" class="btn btn-<?php echo  $color; ?> btn-xs"><?php echo  $st; ?></button>
                                                <button type="button" class="btn btn-white btn-xs dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item edit" href="javascript:;" data-idx="<?= $ser['idx']?>"  data-tenant="<?= $ser['tenant_id']?>"  data-service="<?= $ser['service_id']?>"data-status="<?= $ser['in_status']?>" data-date_start="<?=date('m/d/Y',$ser['lease_start'])?>" data-count="<?= $ser['count']?>">
                                                        <?php echo $this->lang->line('edit'); ?>
                                                    </a>
                                                   
                                                
                                                </div>
                                            </div>
                                            <br>

                                            <?php

                                          
                                        }
                                           
                                        
                                        ?>
                                    <?php endforeach; ?>
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
                <?php echo form_open('services_invoices/add', array('method' => 'post', 'data-parsley-validate' => 'ture')); ?>
                    <input type="hidden" id="idx" name="idx" value="0"/>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('service'); ?> *</label>
                        <div>
                            <select style="width: 100%" class="form-control default-select2" name="service_id" data-parsley-required="true" id="service">
                                <option value=""><?php echo $this->lang->line('service'); ?></option>
                        <?php
                              $room_invoices = $this->db->get_where('service', array())->result_array();
                              foreach ($room_invoices as $room_invoice):?>
                              
                                <option value="<?=$room_invoice['service_id'] ?>"><?=$room_invoice['name'] ?></option>

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
                        <label>Count service *</label>
                        <input type="number" class="form-control" id="count_service" name="count_service" placeholder="" data-parsley-required="true"/>
                 
                    </div>
                    <div class="form-group" >
                        <label><?php echo $this->lang->line('lease_period'); ?> *</label>
                        <div class="input-group input-daterange">
                            <input type="text" class="form-control" id="lease_start" name="lease_start" value="<?=date('m/d/Y')?>"  placeholder="<?php echo $this->lang->line('date_start'); ?>" data-parsley-required="true"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('status'); ?> *</label>
                        <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="status" id="in_status">
                            <option value=""><?php echo $this->lang->line('select_status'); ?></option>
                            <option selected value="1"><?php echo $this->lang->line('active'); ?></option>
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
        var service = $(this).attr('data-service');
        var tenant = $(this).attr('data-tenant');
        var lease_start =$(this).attr('data-date_start');
        var count = $(this).attr('data-count');


        $("#idx").val(idx);
        $("#in_status").val(in_status).trigger('change');
        $("#service").val(service).trigger('change');
        $("#tenant").val(tenant).trigger('change');
        $("#lease_start").val(lease_start);
        $("#count_service").val(count);

    });

   
</script>