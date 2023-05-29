<?php
	$count = 1;
	$tenant_details = $this->db->get_where('tenant', array('tenant_id' => $param2))->result_array();
	foreach ($tenant_details as $tenant):
?>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th><b><?php echo $this->lang->line('name'); ?></b></th>
					<th><b><?php echo $this->lang->line('content'); ?></b></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo $this->lang->line('email'); ?></td>
					<td><?php echo $tenant['email'] ? html_escape($tenant['email']) : 'N/A'; ?></td>
				</tr>
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo $this->lang->line('profession'); ?></td>
					<td>
						<?php 
							if ($tenant['profession_id'] && $this->db->get_where('profession', array('profession_id' => $tenant['profession_id']))->num_rows() > 0)
								echo $this->db->get_where('profession', array('profession_id' => $tenant['profession_id']))->row()->name;
							else 
								echo 'Profession not found';
						?>
					</td>
				</tr>
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo $this->lang->line('lease_period'); ?></td>
					<td><?php echo ($tenant['lease_start'] ? date('d M, Y', $tenant['lease_start']) : 'N/A') . ' to ' . ($tenant['lease_end'] ? date('d M, Y', $tenant['lease_end']) : 'N/A'); ?></td>
				</tr>
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo $this->lang->line('home_address'); ?></td>
					<td><?php echo $tenant['home_address'] == '<br>' ? 'N/A' : $tenant['home_address']; ?></td>
				</tr>
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo $this->lang->line('work_address'); ?></td>
					<td><?php echo $tenant['work_address'] == '<br>' ? 'N/A' : $tenant['work_address']; ?></td>
				</tr>
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo $this->lang->line('extra_note'); ?></td>
					<td><?php echo $tenant['extra_note']?  html_escape($tenant['extra_note']) : 'N/A'; ?></td>
				</tr>
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo $this->lang->line('created_on'); ?></td>
					<td><?php echo date('d M, Y', $tenant['created_on']); ?></td>
				</tr>
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo $this->lang->line('created_by'); ?></td>
					<td>
						<?php
							$user_type =  $this->db->get_where('user', array('user_id' => $tenant['created_by']))->row()->user_type;
							if ($user_type == 1) {
								echo 'Admin';
							} else {
								$person_id = $this->db->get_where('user', array('user_id' => $tenant['created_by']))->row()->person_id;
								echo html_escape($this->db->get_where('staff', array('staff_id' => $person_id))->row()->name);
							}
						?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
<?php endforeach; ?>
