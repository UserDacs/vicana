<style>
	@page {
		size: A4
	}
</style>

<!-- begin #content -->
<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb hidden-print pull-right">
		<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('dashboard'); ?></a></li>
		<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>invoices"><?php echo $this->lang->line('all_rents'); ?></a></li>
		<li class="breadcrumb-item active"><?php echo $this->lang->line('invoice'); ?></li>
	</ol>
	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<h1 class="page-header hidden-print">
	<?php echo $this->lang->line('invoice'); ?>#<?php echo $invoice_number = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->invoice_number; ?>
	</h1>
	<!-- end page-header -->
	<?php $tenant_id = $this->db->get_where('tenant_rent', array('invoice_id' => $invoice_id))->row()->tenant_id; ?>
	<!-- begin invoice -->
	<div class="invoice print-body">
		<!-- begin invoice-company -->
		<div class="invoice-company text-inverse f-w-600">
			<span class="pull-right hidden-print">
				<a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-white m-b-10 p-l-5 hidden-print">
					<i class="fa fa-print t-plus-1 fa-fw fa-lg"></i> <?php echo $this->lang->line('print'); ?>
				</a>
			</span>
			<?php echo html_escape($this->db->get_where('setting', array('name' => 'tagline'))->row()->content); ?>
		</div>
		<!-- end invoice-company -->
		<!-- begin invoice-header -->
		<div class="invoice-header">
			<div class="invoice-from">
				<small><?php echo $this->lang->line('from'); ?></small>
				<address class="m-t-5 m-b-5">
					<strong class="text-inverse">
						<?php echo html_escape($this->db->get_where('setting', array('name' => 'system_name'))->row()->content); ?>
					</strong><br />
					<?php echo $this->db->get_where('setting', array('name' => 'address'))->row()->content; ?>
				</address>
			</div>
			<div class="invoice-to">
				<small><?php echo $this->lang->line('to'); ?></small>
				<address class="m-t-5 m-b-5">
					<strong class="text-inverse">
						<?php echo $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->tenant_name; ?>
					</strong><br />
					<?php 
						$address_query = $this->db->get_where('tenant', array('tenant_id' => $tenant_id));
						if ($address_query->num_rows() > 0) {
							if ($address_query->row()->work_address) {
								echo $address_query->row()->work_address;
							} else {
								echo 'Address not found.';
							}
						} else {
							echo 'Tenant not found.';
						}
					?>
				</address>
			</div>
			<div class="invoice-date">
				<small>
					<?php
						echo $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->status ? $this->lang->line('paid') : $this->lang->line('due');
						if ($this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->payment_method_id) {
							$payment_method_query  =   $this->db->get_where('payment_method', array('payment_method_id' => $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->payment_method_id));
							if ($payment_method_query->num_rows() > 0) {
								echo ' (' . $payment_method_query->row()->name . ')';
							}
						}
					?>
				</small>
				<div class="date text-inverse m-t-5">
					#<?php echo $invoice_number; ?>
				</div>
				<div class="invoice-detail">
					<?php echo $this->lang->line('due_date'); ?>: <b><?php echo date('d M, Y', $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->due_date); ?></b>
					<br />
					<?php echo $this->lang->line('late_fee'); ?>: <b><?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content . ' ' . number_format($this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->late_fee); ?></b>
					<?php $late_fee = $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->late_fee; ?>
				</div>
			</div>
		</div>
		<!-- end invoice-header -->
		<!-- begin invoice-content -->
		<div class="invoice-content">
			<!-- begin table-responsive -->
			<div class="table-responsive">
				<table class="table table-invoice">
					<thead>
						<tr>
							<th><?php echo strtoupper($this->lang->line('description')); ?></th>
							<?php if ($this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->invoice_type == 0) : ?>
							<th class="text-center" width="20%"><?php echo strtoupper($this->lang->line('starting_date')); ?></th>
							<th class="text-center" width="20%"><?php echo strtoupper($this->lang->line('ending_date')); ?></th>
							<?php else : ?>
							<th class="text-center" width="10%"><?php echo strtoupper($this->lang->line('month')); ?></th>
							<th class="text-center" width="10%"><?php echo strtoupper($this->lang->line('year')); ?></th>
							<?php endif; ?>
							<th class="text-right" width="20%"><?php echo strtoupper($this->lang->line('row_total')); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ($this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->invoice_type == 0): ?>
						<tr>
							<td><span class="text-inverse"><?php echo $this->lang->line('date_range_rent'); ?></span></td>
							<td class="text-center"><?php echo date('d M, Y', $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->start_date); ?></td>
							<td class="text-center"><?php echo date('d M, Y', $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->end_date); ?></td>
							<td class="text-right">
								<?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
								<?php
									$this->db->select_sum('amount');
									$this->db->from('tenant_rent');
									$this->db->where('invoice_id', $invoice_id);
									$query = $this->db->get();

									echo number_format($query->row()->amount);
									?>
							</td>
						</tr>
						<?php
							$invoice_services_total = 0;
							$invoice_services = $this->db->get_where('invoice_service', array('invoice_id' => $invoice_id))->result_array();
							foreach ($invoice_services as $invoice_service):
								if ($this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->num_rows() > 0):
						?>
						<tr>
							<td><span class="text-inverse"><?php echo $this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->row()->name; ?></span></td>
							<td class="text-center"><?php echo $invoice_service['month'] . ', ' . $invoice_service['year']; ?></td>
							<td class="text-center"><?php echo $invoice_service['month'] . ', ' . $invoice_service['year']; ?></td>
							<td class="text-right">
								<?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
								<?php echo number_format($this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->row()->cost); ?>
								<?php $invoice_services_total += $this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->row()->cost; ?>
							</td>
						</tr>
						<?php 
								endif;
							endforeach; 
						?>
						<?php
							else :
								$months_total = $this->db->get_where('tenant_rent', array('invoice_id' => $invoice_id))->result_array();
								foreach ($months_total as $month_total) :
						?>
						<tr>
							<td><span class="text-inverse"><?php echo $this->lang->line('monthly_rent'); ?></span></td>
							<td class="text-center"><?php echo $month_total['month']; ?></td>
							<td class="text-center"><?php echo $month_total['year']; ?></td>
							<td class="text-right">
								<?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
								<?php echo number_format($month_total['amount']); ?>
							</td>
						</tr>
						<?php endforeach; ?>
						<?php
							$invoice_services_total = 0; 
							$invoice_services = $this->db->get_where('invoice_service', array('invoice_id' => $invoice_id))->result_array();
							foreach ($invoice_services as $invoice_service):
								if ($this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->num_rows() > 0):
						?>
						<tr>
							<td><span class="text-inverse"><?php echo $this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->row()->name; ?></span></td>
							<td class="text-center"><?php echo $invoice_service['month']; ?></td>
							<td class="text-center"><?php echo $invoice_service['year']; ?></td>
							<td class="text-right">
								<?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
								<?php echo number_format($this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->row()->cost); ?>
								<?php $invoice_services_total += $this->db->get_where('service', array('service_id' => $invoice_service['service_id']))->row()->cost; ?>
							</td>
						</tr>
						<?php
								endif;
							endforeach; 
						?>
						<?php endif; ?>
						
						<?php if ($late_fee > 0) : ?>
						<tr>
							<td><span class="text-inverse"><?php echo $this->lang->line('late_fee'); ?></span></td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-right">
								<?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
								<?php echo number_format($late_fee); ?>
							</td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<!-- end table-responsive -->
			<!-- begin invoice-price -->
			<div class="invoice-price">
				<div class="invoice-price-left">
					<div class="invoice-price-row">
						<div class="sub-price">
							<small><?php echo strtoupper($this->lang->line('subtotal')); ?></small>
							<span class="text-inverse">
								<?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
								<?php
								$this->db->select_sum('amount');
								$this->db->from('tenant_rent');
								$this->db->where('invoice_id', $invoice_id);
								$query = $this->db->get();

								echo ($late_fee > 0) ? number_format($query->row()->amount + $invoice_services_total + $late_fee) : number_format($query->row()->amount + $invoice_services_total);
								?>
							</span>
						</div>
					</div>
				</div>
				<div class="invoice-price-right">
					<small><?php echo strtoupper($this->lang->line('total')); ?></small>
					<span class="f-w-600">
						<?php echo $this->db->get_where('setting', array('name' => 'currency'))->row()->content; ?>
						<?php
						$this->db->select_sum('amount');
						$this->db->from('tenant_rent');
						$this->db->where('invoice_id', $invoice_id);
						$query = $this->db->get();

						echo ($late_fee > 0) ? number_format($query->row()->amount + $invoice_services_total + $late_fee) : number_format($query->row()->amount + $invoice_services_total);
						?>
					</span>
				</div>
			</div>
			<!-- end invoice-price -->
		</div>
		<!-- end invoice-content -->
	</div>
	<!-- end invoice -->
</div>
<!-- end #content -->

<style>
	@media print {
		.hidden-print {
			display: none;
		}

		.invoice-header {
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
		}

		.invoice-to {
			margin-top: 0 !important;
			text-align: center !important;
		}

		.invoice-date {
			margin-top: 0 !important;
			text-align: right !important;
		}

		.invoice-price {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			grid-gap: 10px;
			grid-auto-rows: 100px;
			grid-template-areas:
				"a a a a b b b b"
				"c c c c d d d d";
			align-items: end;
		}

		.invoice-price-left {
			grid-area: b;
		}

		.invoice-price-right {
			grid-area: d;
		}
	}
</style>