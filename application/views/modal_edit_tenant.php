<?php echo form_open('tenants/update/' . $param2, array('id' => 'edit_tenant', 'method' => 'post', 'data-parsley-validate' => 'true')); ?>
<?php
$tenant_info = $this->db->get_where('tenant', array('tenant_id' => $param2))->result_array();
foreach ($tenant_info as $tenant) :
?>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label><?php echo $this->lang->line('name'); ?> *</label>
			<input value="<?php echo html_escape($tenant['name']); ?>" type="text" name="name" placeholder="<?php echo $this->lang->line('enter_name'); ?>" class="form-control" data-parsley-required="true">
		</div>
		<div class="form-group">
			<label><?php echo $this->lang->line('mobile'); ?> *</label>
			<input value="<?php echo html_escape($tenant['mobile_number']); ?>" type="text" name="mobile_number" placeholder="<?php echo $this->lang->line('enter_mobile_number'); ?>" class="form-control" data-parsley-required="true">
		</div>
		<div class="form-group">
			<label><?php echo $this->lang->line('id_type'); ?></label>
			<div>
				<select style="width: 100%" class="form-control default-select2" name="id_type_id">
					<option value=""><?php echo $this->lang->line('select_id_type'); ?></option>
					<?php
					$id_types = $this->db->get('id_type')->result_array();
					foreach ($id_types as $id_type) :
					?>
						<option <?php if ($id_type['id_type_id'] == $tenant['id_type_id']) echo 'selected'; ?> value="<?php echo html_escape($id_type['id_type_id']); ?>"><?php echo html_escape($id_type['name']); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label><?php echo $this->lang->line('id_number'); ?></label>
			<input value="<?php echo html_escape($tenant['id_number']); ?>" name="id_number" type="text" placeholder="<?php echo $this->lang->line('enter_id_number'); ?>" class="form-control">
		</div>
		<div class="form-group">
			<label><?php echo $this->lang->line('home_address'); ?></label>
			<input value="<?php echo html_escape(explode('<br>', $tenant['home_address'])[0]); ?>" name="home_address_line_1" type="text" placeholder="<?php echo $this->lang->line('enter_home_address_line_1'); ?>" class="form-control">
		</div>
		<div class="form-group">
			<input value="<?php echo html_escape(explode('<br>', $tenant['home_address'])[1]); ?>" name="home_address_line_2" type="text" placeholder="<?php echo $this->lang->line('enter_home_address_line_2'); ?>" class="form-control">
		</div>
		<div class="form-group">
			<label><?php echo $this->lang->line('profession'); ?></label>
			<div>
				<select style="width: 100%" class="form-control default-select2" name="profession_id">
					<option value=""><?php echo $this->lang->line('select_profession'); ?></option>
					<?php
					$professions = $this->db->get('profession')->result_array();
					foreach ($professions as $profession) :
					?>
						<option <?php if ($profession['profession_id'] == $tenant['profession_id']) echo 'selected'; ?> value="<?php echo html_escape($profession['profession_id']); ?>"><?php echo html_escape($profession['name']); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label><?php echo $this->lang->line('work_address'); ?></label>
			<input value="<?php echo html_escape(explode('<br>', $tenant['work_address'])[0]); ?>" name="work_address_line_1" type="text" placeholder="<?php echo $this->lang->line('enter_work_address_line_1'); ?>" class="form-control">
		</div>
		<div class="form-group">
			<input value="<?php echo html_escape(explode('<br>', $tenant['work_address'])[1]); ?>" name="work_address_line_2" type="text" placeholder="<?php echo $this->lang->line('enter_work_address_line_2'); ?>" class="form-control">
		</div>
	</div>

	<div class="col-md-6">
		
		<div class="form-group">
			<label><?php echo $this->lang->line('lease_period'); ?></label>
			<div class="input-group input-daterange">
				<input type="text" class="form-control" value="<?php echo $tenant['lease_start'] ? date('m/d/Y', $tenant['lease_start']) : ''; ?>" name="lease_start" placeholder="<?php echo $this->lang->line('date_start'); ?>" />
				<span class="input-group-addon">to</span>
				<input type="text" class="form-control" value="<?php echo $tenant['lease_end'] ? date('m/d/Y', $tenant['lease_end']) : ''; ?>" name="lease_end" placeholder="<?php echo $this->lang->line('date_end'); ?>" />
			</div>
		</div>
		<div class="form-group">
			<label><?php echo $this->lang->line('room'); ?></label>
			<div>
				<select style="width: 100%" class="form-control default-select2" name="room_id">
					<option value=""><?php echo $this->lang->line('select_room'); ?></option>
					<?php if ($tenant['room_id'] > 0) : ?>
						<option value="<?php echo html_escape($tenant['room_id']); ?>" selected><?php echo html_escape($this->db->get_where('room', array('room_id' => $tenant['room_id']))->row()->room_number); ?></option>
					<?php
					endif;
					$rooms = $this->db->get_where('room', array('status' => 0))->result_array();
					foreach ($rooms as $room) :
					?>
						<option value="<?php echo html_escape($room['room_id']); ?>"><?php echo html_escape($room['room_number']); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="note note-yellow m-b-15">
			<span><?php echo $this->lang->line('to_assign_room'); ?>.</span>
		</div>
		<div class="form-group">
			<label><?php echo $this->lang->line('status'); ?> *</label>
			<div>
				<select style="width: 100%" class="form-control default-select2" data-parsley-required="true" name="status">
					<option value=""><?php echo $this->lang->line('select_status'); ?></option>
					<option <?php if ($tenant['status'] == 1) echo 'selected'; ?> value="1"><?php echo $this->lang->line('active'); ?></option>
					<option <?php if ($tenant['status'] == 0) echo 'selected'; ?> value="0"><?php echo $this->lang->line('inactive'); ?></option>
				</select>
			</div>
		</div>
		<div class="note note-yellow m-b-15">
			<span><?php echo $this->lang->line('to_activate_tenant'); ?>.</span>
		</div>
		<div class="form-group">
			<label><?php echo $this->lang->line('emergency_person'); ?></label>
			<input value="<?php echo html_escape($tenant['emergency_person']); ?>" type="text" name="emergency_person" placeholder="<?php echo $this->lang->line('enter_emergency_person_name'); ?>" class="form-control">
		</div>
		<div class="form-group">
			<label><?php echo $this->lang->line('emergency_contact'); ?></label>
			<input value="<?php echo html_escape($tenant['emergency_contact']); ?>" type="text" name="emergency_contact" placeholder="<?php echo $this->lang->line('enter_emergency_person_mobile_number'); ?>" class="form-control">
		</div>
		<div class="form-group">
			<label><?php echo $this->lang->line('extra_note'); ?></label>
			<textarea style="resize: none" type="text" name="extra_note" placeholder="<?php echo $this->lang->line('enter_extra_note'); ?>" class="form-control"><?php echo html_escape($tenant['extra_note']); ?></textarea>
		</div>
	</div>
</div>

<button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('update'); ?></button>
<?php endforeach; ?>
<?php echo form_close(); ?>

<script>
	$('#edit_tenant').parsley();
	FormPlugins.init();

	$('.modal-dialog').css('max-width', '1080px');

	$('select:not(.normal)').each(function() {
		$(this).select2({
			dropdownParent: $(this).parent()
		});
	});
</script>