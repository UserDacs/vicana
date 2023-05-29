<?php echo form_open_multipart('tenants/change_lease/' . $param2, array('id' => 'change_tenant_lease', 'method' => 'post', 'data-parsley-validate' => 'true')); ?>
<div class="form-group">
	<label><?php echo $this->lang->line('lease_period'); ?></label>
	<br>
	<?php if (html_escape($this->db->get_where('tenant', array('tenant_id' => $param2))->row()->lease_link)): ?>
	<a href="<?php echo base_url('uploads/tenants/' . $this->db->get_where('tenant', array('tenant_id' => $param2))->row()->lease_link); ?>" target="_blank"><?php echo base_url('uploads/tenants/' . $this->db->get_where('tenant', array('tenant_id' => $param2))->row()->lease_link); ?></a>
	<?php else : ?>
	<p><?php echo $this->lang->line('no_preview_available'); ?>.</p>
	<?php endif; ?>
</div>

<div class="form-group">
	<label><?php echo $this->lang->line('lease_period'); ?> *</label>
	<br>
	<img width="90px" src="<?php echo base_url('assets/img/pdf-placeholder.png'); ?>" class="media-object" />
    <p id="lease-preview"></p>
	<span class="btn btn-primary fileinput-button">
		<i class="fa fa-plus"></i>
		<span><?php echo $this->lang->line('add_file'); ?></span>
		<input onchange="readLeaseURL(this);" class="form-control" type="file" name="lease_link" data-parsley-required="true">
	</span>
</div>

<hr>

<button type="submit" class="mb-sm btn btn-primary"><?php echo $this->lang->line('change'); ?></button>
<?php echo form_close(); ?>

<script>
	$('#change_tenant_lease').parsley();

	function readLeaseURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#lease-preview').text(e.target.fileName);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>