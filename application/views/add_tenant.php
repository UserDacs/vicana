<!-- begin #content -->
<div id="content" class="content">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
        <li class="breadcrumb-item active"><?php echo $this->lang->line('add_tenant'); ?></li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">
    <?php echo $this->lang->line('add_tenant_header'); ?>
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
                    <?php echo form_open_multipart('tenants/add', array('method' => 'post', 'data-parsley-validate' => 'true')); ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('name'); ?> *</label>
                                <input type="text" name="name" placeholder="<?php echo $this->lang->line('enter_name'); ?>" class="form-control" data-parsley-required="true">
                            </div>
                            <div class="form-group">
                                <label><?php echo $this->lang->line('mobile'); ?> *</label>
                                <input type="text" name="mobile_number" placeholder="<?php echo $this->lang->line('enter_mobile_number'); ?>" class="form-control" data-parsley-required="true">
                            </div>
                            <div class="form-group" style="display:none">
                                <label><?php echo $this->lang->line('tenant_image'); ?></label>
                                <br>
                                <img id="image-preview" width="90px" src="<?php echo base_url('assets/img/tenant.png'); ?>" class="media-object" />
                                <br>
                                <br>
                                <span class="btn btn-primary fileinput-button">
                                    <i class="fa fa-plus"></i>
                                    <span><?php echo $this->lang->line('add_file'); ?></span>
                                    <input onchange="readImageURL(this);" class="form-control" type="file" name="image_link">
                                </span>
                            </div>
                            <div class="form-group " style="display:none">
                                <label><?php echo $this->lang->line('id_type'); ?></label>
                                <select style="width: 100%" class="form-control default-select2" name="id_type_id">
                                    <option value=""><?php echo $this->lang->line('select_id_type'); ?></option>
                                    <?php
                                    $id_types = $this->db->get('id_type')->result_array();
                                    foreach ($id_types as $id_type) :
                                    ?>
                                        <option value="<?php echo html_escape($id_type['id_type_id']); ?>"><?php echo html_escape($id_type['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group" style="display:none">
                                <label><?php echo $this->lang->line('id_number'); ?></label>
                                <input name="id_number" type="text" placeholder="<?php echo $this->lang->line('enter_id_number'); ?>" class="form-control">
                            </div>
                            <div class="form-group" style="display:none">
                                <label><?php echo $this->lang->line('tenant_id_image'); ?></label>
                                <br>
                                <img id="id-front-preview" width="90px" src="<?php echo base_url('assets/img/tenant.png'); ?>" class="media-object" />
                                <img id="id-back-preview" width="90px" src="<?php echo base_url('assets/img/tenant.png'); ?>" class="media-object" />
                                <br>
                                <br>
                                <span class="btn btn-primary fileinput-button">
                                    <i class="fa fa-plus"></i>
                                    <span><?php echo $this->lang->line('add_file_front'); ?></span>
                                    <input onchange="readIdFrontURL(this);" class="form-control" type="file" name="id_front_image_link">
                                </span>
                                <span class="btn btn-primary fileinput-button">
                                    <i class="fa fa-plus"></i>
                                    <span><?php echo $this->lang->line('add_file_back'); ?></span>
                                    <input onchange="readIdBackURL(this);" class="form-control" type="file" name="id_back_image_link">
                                </span>
                            </div>
                            <div class="form-group">
                                <label><?php echo $this->lang->line('home_address'); ?></label>
                                <input name="home_address_line_1" type="text" placeholder="<?php echo $this->lang->line('enter_home_address_line_1'); ?>" class="form-control">
                            </div>
                            <div class="form-group" style="display:none">
                                <input name="home_address_line_2" type="text" placeholder="<?php echo $this->lang->line('enter_home_address_line_2'); ?>" class="form-control">
                            </div>
                            <div class="form-group" style="display:none">
                                <label><?php echo $this->lang->line('profession'); ?></label>
                                <select style="width: 100%" class="form-control default-select2" name="profession_id">
                                    <option value=""><?php echo $this->lang->line('select_profession'); ?></option>
                                    <?php
                                    $professions = $this->db->get('profession')->result_array();
                                    foreach ($professions as $profession) :
                                    ?>
                                        <option value="<?php echo html_escape($profession['profession_id']); ?>"><?php echo html_escape($profession['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group" style="display:none">
                                <label><?php echo $this->lang->line('work_address'); ?></label>
                                <input name="work_address_line_1" type="text" placeholder="<?php echo $this->lang->line('enter_work_address_line_1'); ?>" class="form-control">
                            </div>
                            <div class="form-group" style="display:none">
                                <input name="work_address_line_2" type="text" placeholder="<?php echo $this->lang->line('enter_work_address_line_2'); ?>" class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12">
                            
                            
                            <div class="form-group" style="display:none">
                                <label><?php echo $this->lang->line('lease_period'); ?></label>
                                <div class="input-group input-daterange">
                                    <input type="text" class="form-control" name="lease_start" placeholder="<?php echo $this->lang->line('date_start'); ?>" />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="form-control" name="lease_end" placeholder="<?php echo $this->lang->line('date_end'); ?>" />
                                </div>
                            </div>
                            <input style="display:none" onchange="readLeaseURL(this);" class="form-control" type="file" name="lease_link">             
                            <div class="form-group" style="display:none">
                                <label><?php echo $this->lang->line('room'); ?></label>
                                <select style="width: 100%" class="form-control default-select2" name="room_id">
                                    <option value=""><?php echo $this->lang->line('select_room'); ?></option>
                                    <?php
                                    $rooms = $this->db->get_where('room', array('status' => 0))->result_array();
                                    foreach ($rooms as $room) :
                                    ?>
                                        <option value="<?php echo html_escape($room['room_id']); ?>"><?php echo html_escape($room['room_number']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                           
                            <div class="form-group">
                                <label><?php echo $this->lang->line('status'); ?> *</label>
                                <select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="status">
                                    <option value=""><?php echo $this->lang->line('select_status'); ?></option>
                                    <option value="1"><?php echo $this->lang->line('active'); ?></option>
                                    <option value="0"><?php echo $this->lang->line('inactive'); ?></option>
                                </select>
                            </div>
                           
                            <div class="form-group">
                                <label><?php echo $this->lang->line('emergency_person'); ?></label>
                                <input type="text" name="emergency_person" placeholder="<?php echo $this->lang->line('enter_emergency_person_name'); ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label><?php echo $this->lang->line('emergency_contact'); ?></label>
                                <input type="text" name="emergency_contact" placeholder="<?php echo $this->lang->line('enter_emergency_person_mobile_number'); ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label><?php echo $this->lang->line('extra_note'); ?></label>
                                <textarea style="resize: none" type="text" name="extra_note" placeholder="<?php echo $this->lang->line('enter_extra_note'); ?>" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('submit'); ?></button>
                    <?php echo form_close(); ?>
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

<script>
    function readImageURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#image-preview')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readIdFrontURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#id-front-preview')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readIdBackURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#id-back-preview')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readLeaseURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.fileName = input.files[0].name
            
            reader.onload = function (e) {
                $('#lease-preview').text(e.target.fileName);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>