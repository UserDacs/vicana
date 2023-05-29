<div class="form-group">
	<label><?php echo $this->lang->line('pictures_related_to_complaint'); ?></label>
	<br>
	<?php if ($this->db->get_where('complaint', array('complaint_id' => $param2))->row()->complaint_picture_1) : ?>
	<p><a href="<?php echo base_url('uploads/complaints/' . $this->db->get_where('complaint', array('complaint_id' => $param2))->row()->complaint_picture_1); ?>" target="_blank"><?php echo base_url('uploads/complaints/' . $this->db->get_where('complaint', array('complaint_id' => $param2))->row()->complaint_picture_1); ?></a></p>
	<?php else : ?>
	<p><?php echo $this->lang->line('no_preview_available'); ?></p>
	<?php endif; ?>
	<?php if ($this->db->get_where('complaint', array('complaint_id' => $param2))->row()->complaint_picture_2) : ?>
	<p><a href="<?php echo base_url('uploads/complaints/' . $this->db->get_where('complaint', array('complaint_id' => $param2))->row()->complaint_picture_2); ?>" target="_blank"><?php echo base_url('uploads/complaints/' . $this->db->get_where('complaint', array('complaint_id' => $param2))->row()->complaint_picture_2); ?></a></p>
	<?php else : ?>
	<p><?php echo $this->lang->line('no_preview_available'); ?></p>
	<?php endif; ?>
</div>

<div class="form-group">
	<label><?php echo $this->lang->line('video_related_to_complaint'); ?></label>
	<br>
	<?php if ($this->db->get_where('complaint', array('complaint_id' => $param2))->row()->complaint_video) : ?>
	<a href="<?php echo base_url('uploads/complaints/' . $this->db->get_where('complaint', array('complaint_id' => $param2))->row()->complaint_video); ?>" target="_blank"><?php echo base_url('uploads/complaints/' . $this->db->get_where('complaint', array('complaint_id' => $param2))->row()->complaint_video); ?></a>
	<?php else : ?>
	<p><?php echo $this->lang->line('no_preview_available'); ?></p>
	<?php endif; ?>
</div>

<script>
	$('.modal-dialog').css('max-width', '720px');
</script>