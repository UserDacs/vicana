<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model extends CI_Model
{
	function add_room()
	{
		$rooms 							= 	$this->db->get('room')->result_array();
		foreach ($rooms as $room) {
			if ($room['room_number'] == $this->input->post('room_number') && $room['floor'] == $this->input->post('floor')) {
				$this->session->set_flashdata('warning', $this->lang->line('room_already_exists'));

				redirect(base_url() . 'add_room', 'refresh');
			}
		}

		$data['room_number']			=	$this->input->post('room_number');
		$data['daily_rent']				=	$this->input->post('daily_rent');
		$data['monthly_rent']			=	$this->input->post('monthly_rent');
		$data['status']					=	0;
		$data['floor']					=	$this->input->post('floor');
		$data['remarks']				=	$this->input->post('remarks');
		$data['created_on']				=	time();
		$data['created_by']				=	$this->session->userdata('user_id');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('room', $data);

		$this->session->set_flashdata('success', $this->lang->line('room_added_successfully'));

		redirect(base_url() . 'rooms', 'refresh');
	}

	

	function cancel_invoice($idx = '',$room_id = '')
	{

			$data['status']					=	2;

			$datar['status']				=	0;

			$this->db->where('room_id', $room_id);
			$this->db->update('room', $datar);
			$this->db->where('idx', $idx);
			$this->db->update('room_invoice', $data);

			$this->session->set_flashdata('success', "Success cancel invoice!");
	
			redirect(base_url() . 'room_invoices', 'refresh');


	}

	function add_room_invoice()
	{
		if ($this->input->post('idx') == 0) {
			if ($this->db->get_where('room_invoice', array('status' => 1, 'room_id' => $this->input->post('room_id')))->num_rows() > 0) {
			
				$this->session->set_flashdata('warning', 'Already Taken!');
	
				redirect(base_url() . 'room_invoices', 'refresh');
	
			}else{
				$room_day		=	$this->db->get_where('room', array('room_id' => $this->input->post('room_id')))->row()->daily_rent;
				$room_month		=	$this->db->get_where('room', array('room_id' => $this->input->post('room_id')))->row()->monthly_rent;
				$numbers = '';
				for($i = 0; $i < 9; $i++){
					$numbers .= $i;
				}
	
				$invoice = substr(str_shuffle($numbers), 0, 9);
	
				$startTimeStamp = strtotime($this->input->post('lease_start'));
				$endTimeStamp = strtotime($this->input->post('lease_end'));
				
				$timeDiff = abs($endTimeStamp - $startTimeStamp);
				
				$numberDays = $timeDiff/86400;  // 86400 seconds in one day
				
				$numberDays = intval($numberDays);
				$t_amout = "";
				if($numberDays >= 30){
					$t_amout = $room_month;
				}else{
					$total_amount_in = $numberDays * $room_day ;
					$t_amout = round($total_amount_in , 2);
				}
	
				$data['invoice_number']			=	$invoice;
				$data['room_id']				=	$this->input->post('room_id');
				$data['tenant_id']				=	$this->input->post('tenant_id');
				$data['lease_start']		    =	strtotime($this->input->post('lease_start'));
				$data['lease_end']			    =	strtotime($this->input->post('lease_end'));
				$data['created_on']				=	time();
				$data['total_amount']			=	$t_amout;
				$data['status']					=	$this->input->post('status');

				$datar['status']					=	$this->input->post('status');
				

				$this->db->where('room_id', $this->input->post('room_id'));
			    $this->db->update('room', $datar);
		
				$this->db->insert('room_invoice', $data);
		
				$this->session->set_flashdata('success', "Success add new invoice!");
		
				redirect(base_url() . 'room_invoices', 'refresh');
			}
		}else{

			$idx = $this->input->post('idx');
			$room_day		=	$this->db->get_where('room', array('room_id' => $this->input->post('room_id')))->row()->daily_rent;
			$room_month		=	$this->db->get_where('room', array('room_id' => $this->input->post('room_id')))->row()->monthly_rent;
			$numbers = '';
			for($i = 0; $i < 9; $i++){
				$numbers .= $i;
			}

			$invoice = substr(str_shuffle($numbers), 0, 9);

			$startTimeStamp = strtotime($this->input->post('lease_start'));
			$endTimeStamp = strtotime($this->input->post('lease_end'));
			
			$timeDiff = abs($endTimeStamp - $startTimeStamp);
			
			$numberDays = $timeDiff/86400;  // 86400 seconds in one day
			
			$numberDays = intval($numberDays);
			$t_amout = "";
			if($numberDays >= 30){
				$t_amout = $room_month;
			}else{
				$total_amount_in = $numberDays * $room_day ;
				$t_amout = round($total_amount_in , 2);
			}

			$data['invoice_number']			=	$invoice;
			$data['room_id']				=	$this->input->post('room_id');
			$data['tenant_id']				=	$this->input->post('tenant_id');
			$data['lease_start']		    =	strtotime($this->input->post('lease_start'));
			$data['lease_end']			    =	strtotime($this->input->post('lease_end'));
			$data['total_amount']			=	$t_amout;
			$data['status']					=	$this->input->post('status');

			$datar['status']				=	$this->input->post('status');

			$this->db->where('room_id', $this->input->post('room_id'));
			$this->db->update('room', $datar);
			$this->db->where('idx', $idx);
			$this->db->update('room_invoice', $data);
	
			$this->session->set_flashdata('success', "Success update invoice!");
	
			redirect(base_url() . 'room_invoices', 'refresh');
		}
		
		
	}


	function manage_service_invoice()
	{
		if ($this->input->post('idx') == 0) {
			if ($this->db->get_where('services_invoice', array('tenant_id' => $this->input->post('tenant_id'), 'service_id' => $this->input->post('service_id'), 'lease_start' => strtotime($this->input->post('lease_start'))))->num_rows() > 0) {
				$service_amount		=	$this->db->get_where('service', array('service_id' => $this->input->post('service_id')))->row()->cost;

				$t_amout = $service_amount * $this->input->post('count_service');

				$this->db->get_where('services_invoice', array('tenant_id' => $this->input->post('tenant_id'), 'service_id' => $this->input->post('service_id'), 'lease_start' => $this->input->post('lease_start')));
				$this->db->set('count', '`count`+ '.$this->input->post('count_service'), FALSE);
				$this->db->set('total_amount', '`total_amount`+ '.$t_amout , FALSE);
			    $this->db->update('services_invoice');
			
				$this->session->set_flashdata('warning', 'Already Taken!');
	
				redirect(base_url() . 'services_invoices', 'refresh');
	
			}else{
				
				$service_amount		=	$this->db->get_where('service', array('service_id' => $this->input->post('service_id')))->row()->cost;
				$numbers = '';
				for($i = 0; $i < 9; $i++){
					$numbers .= $i;
				}
	
				$invoice = substr(str_shuffle($numbers), 0, 9);
	
				$t_amout = $service_amount * $this->input->post('count_service');
				// SSELECT `idx`, `invoice_number`, `service_id`, `tenant_id`, `lease_start`, `created_on`, `count`, `total_amount`, `status` FROM `services_invoice` WHERE 1

				$data['invoice_number']			=	$invoice;
				$data['service_id']				=	$this->input->post('service_id');
				$data['tenant_id']				=	$this->input->post('tenant_id');
				$data['lease_start']		    =	strtotime($this->input->post('lease_start'));
				// $data['lease_end']			    =	strtotime($this->input->post('lease_end'));
				$data['created_on']				=	time();
				$data['count']					=	$this->input->post('count_service');
				$data['total_amount']			=	$t_amout;
				$data['status']					=	$this->input->post('status');

		
				$this->db->insert('services_invoice', $data);
		
				$this->session->set_flashdata('success', "Success add new invoice!");
		
				redirect(base_url() . 'services_invoices', 'refresh');
			}
		}else{

			$idx = $this->input->post('idx');
			$service_amount		=	$this->db->get_where('service', array('service_id' => $this->input->post('service_id')))->row()->cost;
				$numbers = '';
				for($i = 0; $i < 9; $i++){
					$numbers .= $i;
				}
	
				$invoice = substr(str_shuffle($numbers), 0, 9);
	
				$t_amout = $service_amount * $this->input->post('count_service');
				// SSELECT `idx`, `invoice_number`, `service_id`, `tenant_id`, `lease_start`, `created_on`, `count`, `total_amount`, `status` FROM `services_invoice` WHERE 1

				$data['invoice_number']			=	$invoice;
				$data['service_id']				=	$this->input->post('service_id');
				$data['tenant_id']				=	$this->input->post('tenant_id');
				$data['lease_start']		    =	strtotime($this->input->post('lease_start'));
				// $data['lease_end']			    =	strtotime($this->input->post('lease_end'));
				$data['created_on']				=	time();
				$data['count']					=	$this->input->post('count_service');
				$data['total_amount']			=	$t_amout;
				$data['status']					=	$this->input->post('status');

			$this->db->where('idx', $idx);
			$this->db->update('services_invoice', $data);
	
			$this->session->set_flashdata('success', "Success update invoice!");
	
			redirect(base_url() . 'services_invoices', 'refresh');
		}
		
		
	}

	function update_room($room_id = '')
	{
		$existing_room_number 			=	$this->db->get_where('room', array('room_id' => $room_id))->row()->room_number;
		$existing_floor_number			=	$this->db->get_where('room', array('room_id' => $room_id))->row()->floor;

		if ($existing_room_number != $this->input->post('room_number') || $existing_floor_number != $this->input->post('floor')) {
			$rooms 							= 	$this->db->get('room')->result_array();
			foreach ($rooms as $room) {
				if ($room['room_number'] == $this->input->post('room_number') && $room['floor'] == $this->input->post('floor')) {
					$this->session->set_flashdata('warning', $this->lang->line('room_already_exists'));

					redirect(base_url() . 'rooms', 'refresh');
				}
			}
		}

		$data['room_number']			=	$this->input->post('room_number');
		$data['daily_rent']				=	$this->input->post('daily_rent');
		$data['monthly_rent']			=	$this->input->post('monthly_rent');
		$data['floor']					=	$this->input->post('floor');
		$data['remarks']				=	$this->input->post('remarks');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('room_id', $room_id);
		$this->db->update('room', $data);

		$this->session->set_flashdata('success', $this->lang->line('room_updated_successfully'));

		redirect(base_url() . 'rooms', 'refresh');
	}

	function remove_room($room_id = '')
	{
		$this->db->where('room_id', $room_id);
		$this->db->delete('room');

		$this->session->set_flashdata('success', $this->lang->line('room_deleted_successfully'));

		redirect(base_url() . 'rooms', 'refresh');
	}

	function assign_tenant($room_id = '')
	{
		$data['status']			=	1;
		$data['timestamp']		=	time();
		$data['updated_by']		=	$this->session->userdata('user_id');

		$this->db->where('room_id', $room_id);
		$this->db->update('room', $data);

		$data2['room_id']		=	$room_id;
		$data2['status']		=	1;
		$data2['timestamp']		=	time();
		$data2['updated_by']	=	$this->session->userdata('user_id');

		$this->db->where('tenant_id', $this->input->post('tenant_id'));
		$this->db->update('tenant', $data2);

		$array = array('user_type' => 3, 'person_id' => $this->input->post('tenant_id'));
		$this->db->where($array);
		$this->db->update('user', $data);

		$this->session->set_flashdata('success', $this->lang->line('room_assigned_successfully'));

		redirect(base_url() . 'rooms', 'refresh');
	}

	function vacant_room($room_id = '')
	{
		$data['status']			=	0;
		$data['timestamp']		=	time();
		$data['updated_by']		=	$this->session->userdata('user_id');

		$this->db->where('room_id', $room_id);
		$this->db->update('room', $data);

		$tenant_id 				=	$this->db->get_where('tenant', array('room_id' => $room_id))->row()->tenant_id;

		$data2['room_id']		=	0;
		$data2['status']		=	0;
		$data2['timestamp']		=	time();
		$data2['updated_by']	=	$this->session->userdata('user_id');

		$this->db->where('tenant_id', $tenant_id);
		$this->db->update('tenant', $data2);

		$this->session->set_flashdata('success', $this->lang->line('room_vacant_now'));

		redirect(base_url() . 'rooms', 'refresh');
	}

	function add_tenant()
	{
		if ($this->db->get_where('tenant', array('name' => $this->input->post('name')))->num_rows() > 0) {
			
			$this->session->set_flashdata('warning', 'Guest Name already exist!');

			redirect(base_url() . 'add_tenant', 'refresh');

		}else{
			$data['name']				=	$this->input->post('name');
			$data['mobile_number']		=	$this->input->post('mobile_number');
			$data['email']				=	$this->input->post('email');
			$data['id_type_id']			=	$this->input->post('id_type_id');
			$data['id_number']			=	$this->input->post('id_number');
			$data['home_address']		=	$this->input->post('home_address_line_1') . '<br>' . $this->input->post('home_address_line_2');
			$data['emergency_person']	=	$this->input->post('emergency_person');
			$data['emergency_contact']	=	$this->input->post('emergency_contact');
			$data['room_id']			=	$this->input->post('room_id') ? $this->input->post('room_id') : 0;
	
			if ($this->input->post('lease_start') && $this->input->post('lease_end')) {
				$data['lease_start']		=	strtotime($this->input->post('lease_start'));
				$data['lease_end']			=	strtotime($this->input->post('lease_end'));
			}
	
			$data['profession_id']		=	$this->input->post('profession_id');
			$data['work_address']		=	$this->input->post('work_address_line_1');
			$data['status']				=	$this->input->post('status');
			$data['extra_note']			=	$this->input->post('extra_note');
			$data['created_on']			=	time();
			$data['created_by']			=	$this->session->userdata('user_id');
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');
	
			$this->db->insert('tenant', $data);
	
	
			$this->session->set_flashdata('success', $this->lang->line('tenant_added_successfully'));
	
			redirect(base_url() . 'tenants', 'refresh');
	
		}
		
	}

	function change_tenant_image($tenant_id = '')
	{
		$ext 							= 	pathinfo($_FILES['image_link']['name'], PATHINFO_EXTENSION);

		if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
			$image_link 				= 	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->image_link;

			if (isset($image_link)) unlink('uploads/tenants/' . $image_link);

			$tenant_name 				=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->name;

			$data['image_link'] 		= 	strtolower(explode(" ", $tenant_name)[0]) . '_' . time() . '.' . $ext;
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['image_link']['tmp_name'], 'uploads/tenants/' . $data['image_link']);

			$this->db->where('tenant_id', $tenant_id);
			$this->db->update('tenant', $data);

			$this->session->set_flashdata('success', $this->lang->line('tenant_image_updated_successfully'));

			redirect(base_url() . 'tenants', 'refresh');
		} else {
			$this->session->set_flashdata('warning', $this->lang->line('tenant_image_supported_type'));

			redirect(base_url() . 'tenants', 'refresh');
		}
	}

	function change_tenant_id_image($tenant_id = '')
	{
		$ext_id_front 					= 	pathinfo($_FILES['id_front_image_link']['name'], PATHINFO_EXTENSION);
		$ext_id_back 					= 	pathinfo($_FILES['id_back_image_link']['name'], PATHINFO_EXTENSION);

		if ($ext_id_front == 'jpeg' || $ext_id_front == 'jpg' || $ext_id_front == 'png' || $ext_id_front == 'JPEG' || $ext_id_front == 'JPG' || $ext_id_front == 'PNG') {
			$image_link 				= 	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->id_front_image_link;

			if (isset($image_link)) unlink('uploads/tenants/' . $image_link);

			$tenant_name 				=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->name;

			$data['id_front_image_link'] = 	strtolower(explode(" ", $tenant_name)[0]) . '_id_front_' . time() . '.' . $ext_id_front;
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['id_front_image_link']['tmp_name'], 'uploads/tenants/' . $data['id_front_image_link']);

			$this->db->where('tenant_id', $tenant_id);
			$this->db->update('tenant', $data);

			$this->session->set_flashdata('success', $this->lang->line('tenant_image_front_success'));
		} else {
			$this->session->set_flashdata('warning', $this->lang->line('tenant_image_supported_type'));
		}

		if ($ext_id_back == 'jpeg' || $ext_id_back == 'jpg' || $ext_id_back == 'png' || $ext_id_back == 'JPEG' || $ext_id_back == 'JPG' || $ext_id_back == 'PNG') {
			$image_link 				= 	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->id_back_image_link;

			if (isset($image_link)) unlink('uploads/tenants/' . $image_link);

			$tenant_name 				=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->name;

			$data['id_back_image_link'] = 	strtolower(explode(" ", $tenant_name)[0]) . '_id_back_' . time() . '.' . $ext_id_back;
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['id_back_image_link']['tmp_name'], 'uploads/tenants/' . $data['id_back_image_link']);

			$this->db->where('tenant_id', $tenant_id);
			$this->db->update('tenant', $data);

			$this->session->set_flashdata('success', $this->lang->line('tenant_image_back_success'));
		} else {
			$this->session->set_flashdata('warning', $this->lang->line('tenant_image_supported_type'));
		}

		redirect(base_url() . 'tenants', 'refresh');
	}

	function change_tenant_lease($tenant_id = '')
	{
		$ext 							= 	pathinfo($_FILES['lease_link']['name'], PATHINFO_EXTENSION);

		if ($ext == 'pdf' || $ext == 'PDF') {
			$lease_link 				= 	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->lease_link;

			if (isset($lease_link)) unlink('uploads/tenants/' . $lease_link);

			$tenant_name 				=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->name;

			$data['lease_link'] 		= 	strtolower(explode(" ", $tenant_name)[0]) . '_lease_' . time() . '.' . $ext;
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['lease_link']['tmp_name'], 'uploads/tenants/' . $data['lease_link']);

			$this->db->where('tenant_id', $tenant_id);
			$this->db->update('tenant', $data);

			// $this->session->set_flashdata('success', $this->lang->line('tenant_image_updated_successfully'));

			redirect(base_url('tenants'), 'refresh');
		} else {
			// $this->session->set_flashdata('warning', $this->lang->line('tenant_image_supported_type'));

			redirect(base_url('tenants'), 'refresh');
		}
	}

	function update_tenant($tenant_id = '')
	{
		

		$data['name']					=	$this->input->post('name');
		$data['mobile_number']			=	$this->input->post('mobile_number');
		$data['email']					=	$this->input->post('email');
		$data['id_type_id']				=	$this->input->post('id_type_id');
		$data['id_number']				=	$this->input->post('id_number');
		$data['home_address']			=	$this->input->post('home_address_line_1');
		$data['emergency_person']		=	$this->input->post('emergency_person');
		$data['emergency_contact']		=	$this->input->post('emergency_contact');
		$data['profession_id']			=	$this->input->post('profession_id');
		$data['work_address']			=	$this->input->post('work_address_line_1');
		$data['status']					=	$this->input->post('status');
		$data['extra_note']				=	$this->input->post('extra_note');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		if ($this->input->post('lease_start') && $this->input->post('lease_end')) {
			$data['lease_start']		=	strtotime($this->input->post('lease_start'));
			$data['lease_end']			=	strtotime($this->input->post('lease_end'));
		}

		$this->db->where('tenant_id', $tenant_id);
		$this->db->update('tenant', $data);

		$this->session->set_flashdata('success', $this->lang->line('tenant_updated_successfully'));

		redirect(base_url() . 'tenants', 'refresh');
	}

	function deactivate_tenant($tenant_id = '')
	{
		$room_id 						=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->room_id;

		$data['status']					=	0;
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		if ($room_id) {
			$this->db->where('room_id', $room_id);
			$this->db->update('room', $data);
		}

		$data2['room_id']				=	0;
		$data2['status']				=	0;
		$data2['timestamp']				=	time();
		$data2['updated_by']			=	$this->session->userdata('user_id');

		$this->db->where('tenant_id', $tenant_id);
		$this->db->update('tenant', $data2);

		if ($this->db->get_where('user', array('user_type' => 3, 'person_id' => $tenant_id))->num_rows() > 0) {
			$array = array('user_type' => 3, 'person_id' => $tenant_id);
			$this->db->where($array);
			$this->db->update('user', $data);
		}

		$this->session->set_flashdata('success', $this->lang->line('tenant_deactivated_successfully'));

		redirect(base_url() . 'tenants', 'refresh');
	}

	function remove_tenant($tenant_id = '')
	{
		$room_id 						=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->room_id;

		$data['status']					=	0;
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		if ($room_id) {
			$this->db->where('room_id', $room_id);
			$this->db->update('room', $data);
		}

		$image_link 					= 	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->image_link;

		if (isset($image_link)) unlink('uploads/tenants/' . $image_link);

		$this->db->where('tenant_id', $tenant_id);
		$this->db->delete('tenant');

		if ($this->db->get_where('user', array('user_type' => 3, 'person_id' => $tenant_id))->num_rows() > 0) {
			$array = array('user_type' => 3, 'person_id' => $tenant_id);
			$this->db->where($array);
			$this->db->delete('user');
		}

		$this->session->set_flashdata('success', $this->lang->line('tenant_deleted_successfully'));

		redirect(base_url() . 'tenants', 'refresh');
	}

	function add_utility_bill()
	{
		$ext 								= 	pathinfo($_FILES['image_link']['name'], PATHINFO_EXTENSION);

		if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
			$data['image_link'] 			= 	'utility_' . $this->input->post('year') . '_' . $this->input->post('month') . '_' . time() . '.' . $ext;

			move_uploaded_file($_FILES['image_link']['tmp_name'], 'uploads/bills/' . $data['image_link']);
		}

		$data['utility_bill_category_id']	=	$this->input->post('utility_bill_category_id');
		$data['year']						=	$this->input->post('year');
		$data['month']						=	$this->input->post('month');
		$data['amount']						=	$this->input->post('amount');
		$data['status']						=	$this->input->post('status');
		$data['created_on']					=	time();
		$data['created_by']					=	$this->session->userdata('user_id');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->insert('utility_bill', $data);

		$this->session->set_flashdata('success', $this->lang->line('utility_bill_added_successfully'));

		redirect(base_url() . 'utility_bills', 'refresh');
	}

	function update_utility_bill($utility_bill_id = '')
	{
		$data['utility_bill_category_id']	=	$this->input->post('utility_bill_category_id');
		$data['year']						=	$this->input->post('year');
		$data['month']						=	$this->input->post('month');
		$data['amount']						=	$this->input->post('amount');
		$data['status']						=	$this->input->post('status');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->where('utility_bill_id', $utility_bill_id);
		$this->db->update('utility_bill', $data);

		$this->session->set_flashdata('success', $this->lang->line('utility_bill_updated_successfully'));

		redirect(base_url() . 'utility_bills', 'refresh');
	}

	function change_utility_image($utility_bill_id = '')
	{
		$ext 							= 	pathinfo($_FILES['image_link']['name'], PATHINFO_EXTENSION);

		if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
			$image_link 				= 	$this->db->get_where('utility_bill', array('utility_bill_id' => $utility_bill_id))->row()->image_link;

			if (isset($image_link)) unlink('uploads/bills/' . $image_link);

			$year 						=	$this->db->get_where('utility_bill', array('utility_bill_id' => $utility_bill_id))->row()->year;
			$month 						=	$this->db->get_where('utility_bill', array('utility_bill_id' => $utility_bill_id))->row()->month;

			$data['image_link'] 		= 	'utility_' . $year . '_' . $month . '_' . time() . '.' . $ext;
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['image_link']['tmp_name'], 'uploads/bills/' . $data['image_link']);

			$this->db->where('utility_bill_id', $utility_bill_id);
			$this->db->update('utility_bill', $data);

			$this->session->set_flashdata('success', $this->lang->line('utility_bill_image_updated_successfully'));

			redirect(base_url() . 'utility_bills', 'refresh');
		} else {
			$this->session->set_flashdata('warning', $this->lang->line('tenant_image_supported_type'));

			redirect(base_url() . 'utility_bills', 'refresh');
		}
	}

	function remove_utility_bill($utility_bill_id = '')
	{
		$this->db->where('utility_bill_id', $utility_bill_id);
		$this->db->delete('utility_bill');

		$this->session->set_flashdata('success', $this->lang->line('utility_bill_deleted_successfully'));

		redirect(base_url() . 'utility_bills', 'refresh');
	}

	// Function related to adding utility bill category
	function add_utility_bill_category()
	{
		$data['name']					=	$this->input->post('name');
		$data['created_on']				=	time();
		$data['created_by']				=	$this->session->userdata('user_id');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('utility_bill_category', $data);

		$this->session->set_flashdata('success', $this->lang->line('utility_bill_cat_added_successfully'));

		redirect(base_url() . 'utility_bill_categories', 'refresh');
	}

	// Function related to updating utility bill category
	function update_utility_bill_category($utility_bill_category_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('utility_bill_category_id', $utility_bill_category_id);
		$this->db->update('utility_bill_category', $data);

		$this->session->set_flashdata('success', $this->lang->line('utility_bill_cat_updated_successfully'));

		redirect(base_url() . 'utility_bill_categories', 'refresh');
	}

	// Function related to removing utility bill category
	function remove_utility_bill_category($utility_bill_category_id = '')
	{
		$this->db->where('utility_bill_category_id', $utility_bill_category_id);
		$this->db->delete('utility_bill_category');

		$this->session->set_flashdata('success', $this->lang->line('utility_bill_cat_deleted_successfully'));

		redirect(base_url() . 'utility_bill_categories', 'refresh');
	}

	function add_expense()
	{
		$data['name']						=	$this->input->post('name');
		$data['amount']						=	$this->input->post('amount');
		$data['description']				=	$this->input->post('description');
		$data['year']						=	$this->input->post('year');
		$data['month']						=	$this->input->post('month');
		$data['created_on']					=	time();
		$data['created_by']					=	$this->session->userdata('user_id');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->insert('expense', $data);

		$this->session->set_flashdata('success', $this->lang->line('expense_added_successfully'));

		redirect(base_url() . 'expenses', 'refresh');
	}

	function update_expense($expense_id = '')
	{
		$data['name']						=	$this->input->post('name');
		$data['amount']						=	$this->input->post('amount');
		$data['description']				=	$this->input->post('description');
		$data['year']						=	$this->input->post('year');
		$data['month']						=	$this->input->post('month');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->where('expense_id', $expense_id);
		$this->db->update('expense', $data);

		$this->session->set_flashdata('success', $this->lang->line('expense_updated_successfully'));

		redirect(base_url() . 'expenses', 'refresh');
	}

	function remove_expense($expense_id = '')
	{
		$this->db->where('expense_id', $expense_id);
		$this->db->delete('expense');

		$this->session->set_flashdata('success', $this->lang->line('expense_deleted_successfully'));

		redirect(base_url() . 'expenses', 'refresh');
	}

	function add_staff()
	{
		$users = $this->db->get('user')->result_array();
		foreach ($users as $user) {
			if ($user['email'] == $this->input->post('email')) {
				$this->session->set_flashdata('warning', $this->lang->line('tenant_email_already_registered'));

				redirect(base_url() . 'add_staff', 'refresh');
			}
		}

		$data['name']					=	$this->input->post('name');
		$data['role']					=	$this->input->post('role');
		$data['mobile_number']			=	$this->input->post('mobile_number');
		$data['status']					=	$this->input->post('status');
		$data['remarks']				=	$this->input->post('remarks');
		$data['created_on']				= 	time();
		$data['created_by']				=	$this->session->userdata('user_id');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('staff', $data);

		if ($this->input->post('email')) {
			$data2['person_id']				=	$this->db->insert_id();
			$data2['email']					=	$this->input->post('email');
			$data2['password']				=	$this->input->post('password') ? password_hash($this->input->post('password'), PASSWORD_DEFAULT) : password_hash(123456, PASSWORD_DEFAULT);
			$data2['user_type']				=	2;
			$data2['status']				=	$this->input->post('status');
			$data2['created_on']			= 	time();
			$data2['created_by']			=	$this->session->userdata('user_id');
			$data2['timestamp']				=	time();
			$data2['updated_by']			=	$this->session->userdata('user_id');

			$this->db->insert('user', $data2);

			$permission 					= 	$this->input->post('permission');

			if (isset($permission)) {
				$this->update_staff_permission($data2['person_id'], $permission);
			}
		}

		$this->session->set_flashdata('success', $this->lang->line('staff_added_successfully'));

		redirect(base_url() . 'staff', 'refresh');
	}

	function update_staff($staff_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['role']					=	$this->input->post('role');
		$data['mobile_number']			=	$this->input->post('mobile_number');
		$data['status']					=	$this->input->post('status');
		$data['remarks']				=	$this->input->post('remarks');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('staff_id', $staff_id);
		$this->db->update('staff', $data);

		if ($this->input->post('email')) {
			if ($this->db->get_where('user', array('user_type' => 2, 'person_id' => $staff_id))->num_rows() > 0) {
				$data2['email']					=	$this->input->post('email');
				$data2['status']				=	$this->input->post('status');
				$data2['timestamp']				=	time();
				$data2['updated_by']			=	$this->session->userdata('user_id');

				$array = array('user_type' => 2, 'person_id' => $staff_id);
				$this->db->where($array);
				$this->db->update('user', $data2);
			} else {
				$data2['person_id']				=	$staff_id;
				$data2['email']					=	$this->input->post('email');
				$data2['password']				=	$this->input->post('password') ? password_hash($this->input->post('password'), PASSWORD_DEFAULT) : password_hash(123456, PASSWORD_DEFAULT);
				$data2['user_type']				=	2;
				$data2['status']				=	$this->input->post('status');
				$data2['created_on']			= 	time();
				$data2['created_by']			=	$this->session->userdata('user_id');
				$data2['timestamp']				=	time();
				$data2['updated_by']			=	$this->session->userdata('user_id');

				$this->db->insert('user', $data2);
			}

			$permission 						= 	$this->input->post('permission');

			if (isset($permission)) {
				$this->update_staff_permission($staff_id, $permission);
			}
		}

		$this->session->set_flashdata('success', $this->lang->line('staff_updated_successfully'));

		redirect(base_url() . 'staff', 'refresh');
	}

	function deactivate_staff($staff_id = '')
	{
		$data['status']					=	0;
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('staff_id', $staff_id);
		$this->db->update('staff', $data);

		if ($this->db->get_where('user', array('user_type' => 2, 'person_id' => $staff_id))->num_rows() > 0) {
			$array = array('user_type' => 2, 'person_id' => $staff_id);
			$this->db->where($array);
			$this->db->update('user', $data);
		}

		$this->session->set_flashdata('success', $this->lang->line('staff_deactivated_successfully'));

		redirect(base_url() . 'staff', 'refresh');
	}

	function remove_staff($staff_id = '')
	{
		$this->db->where('staff_id', $staff_id);
		$this->db->delete('staff');

		if ($this->db->get_where('user', array('user_type' => 2, 'person_id' => $staff_id))->num_rows() > 0) {
			$array = array('user_type' => 2, 'person_id' => $staff_id);
			$this->db->where($array);
			$this->db->delete('user');
		}

		$this->session->set_flashdata('success', $this->lang->line('staff_deleted_successfully'));

		redirect(base_url() . 'staff', 'refresh');
	}

	function update_staff_permission($staff_id = '', $permission = [])
	{
		$permissions 					=	'';

		foreach ($permission as $key => $value) {
			$permissions			.=	$value . ',';
		}

		$data['permissions']			=	substr(trim($permissions), 0, -1);
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$array = array('user_type' => 2, 'person_id' => $staff_id);
		$this->db->where($array);
		$this->db->update('user', $data);

		$this->session->set_flashdata('success', $this->lang->line('staff_permission_updated_successfully'));

		redirect(base_url() . 'staff', 'refresh');
	}

	function add_staff_salary()
	{
		$data['staff_id']				=	$this->input->post('staff_id');
		$data['year']					=	$this->input->post('year');
		$data['month']					=	$this->input->post('month');
		$data['amount']					=	$this->input->post('amount');
		$data['status']					=	$this->input->post('status');
		$data['created_on']				= 	time();
		$data['created_by']				=	$this->session->userdata('user_id');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('staff_salary', $data);

		$this->session->set_flashdata('success', $this->lang->line('staff_salary_added_successfully'));

		redirect(base_url('single_month_staff_payroll' . '/' . $data['year'] . '/' . $data['month']), 'refresh');
	}

	function update_staff_salary($staff_salary_id = '')
	{
		$data['staff_id']				=	$this->input->post('staff_id');
		$data['year']					=	$this->input->post('year');
		$data['month']					=	$this->input->post('month');
		$data['amount']					=	$this->input->post('amount');
		$data['status']					=	$this->input->post('status');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('staff_salary_id', $staff_salary_id);
		$this->db->update('staff_salary', $data);

		$this->session->set_flashdata('success', $this->lang->line('staff_salary_updated_successfully'));

		redirect(base_url() . 'staff_payroll', 'refresh');
	}

	function remove_staff_salary($staff_salary_id = '')
	{
		$this->db->where('staff_salary_id', $staff_salary_id);
		$this->db->delete('staff_salary');

		$this->session->set_flashdata('success', $this->lang->line('staff_salary_deleted_successfully'));

		redirect(base_url() . 'staff_payroll', 'refresh');
	}

	function generate_date_range_rents()
	{
		$tenant_id 						= 	$this->input->post('tenant_id');
		$start_date						=	strtotime($this->input->post('start'));
		$end_date						=	strtotime($this->input->post('end'));

		$room_id 						=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->room_id;
		$room_number					= 	$this->db->get_where('room', array('room_id' => $room_id))->row()->room_number;

		$start_year  					= 	date('Y', $start_date);
		$end_year  						= 	date('Y', $end_date);
		$start_month  					= 	date('n', $start_date);
		$end_month  					= 	date('n', $end_date);
		$start_day 						= 	date('d', $start_date);
		$end_day 						= 	date('d', $end_date);

		$invoice['tenant_name']			=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->name;
		$invoice['status']				=	$this->input->post('status');
		$invoice['start_date']			=	strtotime($this->input->post('start'));
		$invoice['end_date']			=	strtotime($this->input->post('end') . '11:59:59 pm');
		$invoice['due_date']			=	strtotime($this->input->post('due_date') . '11:59:59 pm');
		$invoice['invoice_type']		=	0;
		$invoice['tenant_mobile']		=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->mobile_number;
		$invoice['room_number']			=	$room_number;
		$invoice['tenant_id']			=	$tenant_id;
		$invoice['late_fee']			=	0;
		$invoice['invoice_number']		=	$start_year . $start_month . rand(1, 9999) . $tenant_id;
		$invoice['created_on']			= 	time();
		$invoice['created_by']			=	$this->session->userdata('user_id');
		$invoice['timestamp']			=	time();
		$invoice['updated_by']			=	$this->session->userdata('user_id');

		$this->db->insert('invoice', $invoice);

		$invoice_id						=	$this->db->insert_id();

		if ($start_year < $end_year) {
			for ($i = $start_month; $i <= ($end_month + 12); $i++) {
				if ($i > 12) {
					$year = $end_year;
					$month = date('F', strtotime($year . '-' . ($i - 12) . '-01'));
				} else {
					$year = $start_year;
					$month = date('F', strtotime($year . '-' . $i . '-01'));
				}
				
				$days = date('t', strtotime($year . '-' . $month));

				if ($i == $start_month) {
					$days = $days - $start_day + 1;
				} elseif ($i == $end_month) {
					$days = $end_day;
				}

				$data['month']			=	$month;
				$data['year']			=	$year;
				$data['amount']			=	$days * $this->db->get_where('room', array('room_id' => $room_id))->row()->daily_rent;
				$data['invoice_id']		=	$invoice_id;
				$data['tenant_id']		=	$tenant_id;
				$data['created_on']		= 	time();
				$data['created_by']		=	$this->session->userdata('user_id');
				$data['timestamp']		=	time();
				$data['updated_by']		=	$this->session->userdata('user_id');
				$data['status']			=	$this->input->post('status');

				$this->db->insert('tenant_rent', $data);
			}
		} else {
			for ($i = $start_month; $i <= $end_month; $i++) {
				$year = $start_year;
				$month = date('F', strtotime($year . '-' . $i . '-01'));
				$days = date('t', strtotime($year . '-' . $month));

				if ($start_month == $end_month) {
					$days = $end_day - $start_day + 1;
				} elseif ($i == $start_month) {
					$days = $days - $start_day + 1;
				} elseif ($i == $end_month) {
					$days = $end_day;
				}

				$data['month']			=	$month;
				$data['year']			=	$year;
				$data['amount']			=	$days * $this->db->get_where('room', array('room_id' => $room_id))->row()->daily_rent;
				$data['invoice_id']		=	$invoice_id;
				$data['tenant_id']		=	$tenant_id;
				$data['created_on']		= 	time();
				$data['created_by']		=	$this->session->userdata('user_id');
				$data['timestamp']		=	time();
				$data['updated_by']		=	$this->session->userdata('user_id');
				$data['status']			=	$this->input->post('status');

				$this->db->insert('tenant_rent', $data);
			}
		}

		$this->session->set_flashdata('success', $this->lang->line('rent_date_range_generated_successfully'));

		redirect(base_url() . 'invoices', 'refresh');
	}

	function generate_multiple_months_rent()
	{
		$tenant_id 						= 	$this->input->post('tenant_id');
		$year 							=	$this->input->post('year');
		$months 						=	$this->input->post('months');

		$room_id 						=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->room_id;
		$room_number					= 	$this->db->get_where('room', array('room_id' => $room_id))->row()->room_number;

		$invoice['tenant_name']			=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->name;
		$invoice['status']				=	$this->input->post('status');
		$invoice['start_date']			=	strtotime($months[0] . ' ' . '01' . ', ' . $year);
		$invoice['end_date']			=	strtotime($months[count($months) - 1] . ' ' . date('t', strtotime($year . '-' . $months[count($months) - 1])) . ', ' . $year . '11:59:59 pm');
		$invoice['due_date']			=	strtotime($this->input->post('due_date') . '11:59:59 pm');
		$invoice['invoice_type']		=	2;
		$invoice['tenant_mobile']		=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->mobile_number;
		$invoice['room_number']			=	$room_number;
		$invoice['tenant_id']			=	$tenant_id;
		$invoice['late_fee']			=	0;
		$invoice['invoice_number']		=	$year . date('m', strtotime($months[0])) . rand(1, 9999) . $tenant_id;
		$invoice['created_on']			= 	time();
		$invoice['created_by']			=	$this->session->userdata('user_id');
		$invoice['timestamp']			=	time();
		$invoice['updated_by']			=	$this->session->userdata('user_id');

		$this->db->insert('invoice', $invoice);

		$invoice_id						=	$this->db->insert_id();

		for ($i = 0; $i < sizeof($months); $i++) {
			$data['month']			=	$months[$i];
			$data['year']			=	$year;
			$data['amount']			=	$this->db->get_where('room', array('room_id' => $room_id))->row()->monthly_rent;
			$data['invoice_id']		=	$invoice_id;
			$data['tenant_id']		=	$tenant_id;
			$data['created_on']		= 	time();
			$data['created_by']		=	$this->session->userdata('user_id');
			$data['timestamp']		=	time();
			$data['updated_by']		=	$this->session->userdata('user_id');
			$data['status']			=	$this->input->post('status');

			$this->db->insert('tenant_rent', $data);
		}

		$this->session->set_flashdata('success', $this->lang->line('rent_single_tenant_generated_successfully'));

		redirect(base_url() . 'invoices', 'refresh');
	}

	function generate_single_months_rent()
	{
		$tenants 						=	[];
		$year 							= 	$this->input->post('year');
		$month 							= 	$this->input->post('month');

		if ($this->input->post('tenants')[0] == 'All') {
			$active_tenants = $this->db->get_where('tenant', array('status' => 1))->result_array();
			foreach ($active_tenants as $active_tenant) {
				array_push($tenants, $active_tenant['tenant_id']);
			}
		} else {
			$tenants = $this->input->post('tenants');
		}

		for ($i = 0; $i < sizeof($tenants); $i++) {
			$room_id 					=	$this->db->get_where('tenant', array('tenant_id' => $tenants[$i]))->row()->room_id;
			$room_number				= 	$this->db->get_where('room', array('room_id' => $room_id))->row()->room_number;

			$invoice['tenant_name']		=	$this->db->get_where('tenant', array('tenant_id' => $tenants[$i]))->row()->name;
			$invoice['status']			=	$this->input->post('status');
			$invoice['start_date']		=	strtotime($month . ' ' . '01' . ', ' . $year);
			$invoice['end_date']		=	strtotime($month . ' ' . date('t', strtotime($year . '-' . $month)) . ', ' . $year . '11:59:59 pm');
			$invoice['due_date']		=	strtotime($this->input->post('due_date') . '11:59:59 pm');
			$invoice['invoice_type']	=	1;
			$invoice['tenant_mobile']	=	$this->db->get_where('tenant', array('tenant_id' => $tenants[$i]))->row()->mobile_number;
			$invoice['room_number']		=	$room_number;
			$invoice['tenant_id']		=	$tenants[$i];
			$invoice['late_fee']		=	0;
			$invoice['invoice_number']	=	$year . date('m', strtotime($month)) . rand(1, 9999) . $tenants[$i];
			$invoice['created_on']		= 	time();
			$invoice['created_by']		=	$this->session->userdata('user_id');
			$invoice['timestamp']		=	time();
			$invoice['updated_by']		=	$this->session->userdata('user_id');

			$this->db->insert('invoice', $invoice);

			$invoice_id						=	$this->db->insert_id();

			$data['month']			=	$month;
			$data['year']			=	$year;
			$data['amount']			=	$this->db->get_where('room', array('room_id' => $room_id))->row()->monthly_rent;
			$data['invoice_id']		=	$invoice_id;
			$data['tenant_id']		=	$tenants[$i];
			$data['created_on']		= 	time();
			$data['created_by']		=	$this->session->userdata('user_id');
			$data['timestamp']		=	time();
			$data['updated_by']		=	$this->session->userdata('user_id');
			$data['status']			=	$this->input->post('status');

			$this->db->insert('tenant_rent', $data);
		}

		$this->session->set_flashdata('success', $this->lang->line('rent_monthly_generated_successfully'));

		redirect(base_url() . 'invoices', 'refresh');
	}

	function send_invoice_sms($invoice_id = '')
	{
		$tenant_id 		= 	$this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->tenant_id;
		$tenant_mobile	=	$this->db->get_where('tenant', array('tenant_id' => $tenant_id))->row()->mobile_number;
		$late_fee 		= 	$this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->late_fee;
		$this->db->select_sum('amount');
		$this->db->from('tenant_rent');
		$this->db->where('invoice_id', $invoice_id);
		$query = $this->db->get();
		$grand_total = $late_fee > 0 ? $query->row()->amount + $late_fee : $query->row()->amount;

        $message = $this->lang->line('sms_invoice_1') 
		. '#' 
		. $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->invoice_number 
		. $this->lang->line('sms_invoice_2') 
		. date('d M, Y', $this->db->get_where('invoice', array('invoice_id' => $invoice_id))->row()->due_date) 
		. '. '
		. $this->lang->line('sms_invoice_3') . $this->db->get_where('setting', array('name' => 'currency'))->row()->content . ' ' . number_format($grand_total) 
		. ' - '
		. $this->lang->line('from')
		. $this->db->get_where('setting', array('setting_id' => 1))->row()->content;

        if ($this->db->get_where('setting', array('name' => 'number'))->row()->content) {
			if ($tenant_mobile) {
				$from = $this->db->get_where('setting', array('name' => 'number'))->row()->content;
				$to = $tenant_mobile;    
				
				$config['account_sid']	=	$this->db->get_where('setting', array('name' => 'account_sid'))->row()->content;
				$config['auth_token']	=	$this->db->get_where('setting', array('name' => 'auth_token'))->row()->content;
				$config['number']		=	$this->db->get_where('setting', array('name' => 'number'))->row()->content;

				$this->twilio->initialize($config);

				$response = $this->twilio->sms($from, $to, $message);

				if($response->IsError) {
					echo 'Error: ' . $response->ErrorMessage;

					$this->session->set_flashdata('error', $response->ErrorMessage);

					redirect(base_url() . 'invoices', 'refresh');
				} else {
					echo 'Sent message to ' . $to;
					
					$sms['sms'] =   1;

					$this->db->where('invoice_id', $invoice_id);
					$this->db->update('invoice', $sms);

					$this->session->set_flashdata('success', $this->lang->line('sms_sent_successfully'));

        			redirect(base_url() . 'invoices', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', $this->lang->line('tenant_mobile_number_not_found'));

				redirect(base_url() . 'invoices', 'refresh');
			}
        } else {
			$this->session->set_flashdata('error', $this->lang->line('twilio_conf_not_found') . '<a href="' . base_url('website_settings') . '">' . $this->lang->line('website_settings') . '</a>');

			redirect(base_url() . 'invoices', 'refresh');
		}
	}

	function update_invoice($invoice_id = '', $invoice_type = '')
	{
		$data['status']					= 	$this->input->post('status');
		$data['month']					=	$this->input->post('month');
		$data['year']					=	$this->input->post('year');
		$data['amount']					=	$this->input->post('amount');

		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('tenant_rent_id', $invoice_id);
		$this->db->update('tenant_rent', $data);

		$this->session->set_flashdata('success', $this->lang->line('rent_invoice_updated_successfully'));

		redirect(base_url() . 'invoices', 'refresh');
	}

	function update_invoice_status($invoice_id = '')
	{
		$data['status']					= 	$this->input->post('status');
		$data['payment_method_id']		= 	$this->input->post('payment_method_id');
		$data['late_fee']				=	$this->input->post('late_fee') > 0 ? $this->input->post('late_fee') : 0;
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->where('invoice_id', $invoice_id);
		$this->db->update('invoice', $data);

		$tenant_rents = $this->db->get_where('tenant_rent', array('invoice_id' => $invoice_id))->result_array();
		foreach ($tenant_rents as $tenant_rent) {
			$data2['status']				=	$this->input->post('status');
			$data2['timestamp']			=	time();

			$this->db->where('tenant_rent_id', $tenant_rent['tenant_rent_id']);
			$this->db->update('tenant_rent', $data2);
		}

		$this->session->set_flashdata('success', $this->lang->line('rent_invoice_status_updated_successfully'));

		redirect(base_url() . 'invoices', 'refresh');
	}

	function update_invoice_services($invoice_id = '')
	{
		$services_from_db	=	$this->db->get_where('invoice_service', array('invoice_id' => $invoice_id))->result_array();
		foreach ($services_from_db as $row) {
			$this->db->where('invoice_service_id', $row['invoice_service_id']);
			$this->db->delete('invoice_service');
		}

		$service_ids	= 	$this->input->post('service_ids');
		$years 			=	$this->input->post('years');
		$months 		= 	$this->input->post('months');

		foreach ($service_ids as $key => $value) {
            $data['service_id']	=	$value;
            $data['year']     	=   $years[$key];
            $data['month']      =   $months[$key];
            $data['invoice_id'] =   $invoice_id;
            $data['created_on']	= 	time();
			$data['created_by']	=	$this->session->userdata('user_id');
			$data['timestamp']	=	time();
			$data['updated_by']	=	$this->session->userdata('user_id');

            $this->db->insert('invoice_service', $data);
        }

		redirect(base_url('invoice/' . $invoice_id), 'refresh');
	}

	function remove_invoice($invoice_id = '')
	{
		$tenant_rents = $this->db->get_where('tenant_rent', array('invoice_id' => $invoice_id))->result_array();
		foreach ($tenant_rents as $tenant_rent) {
			$this->db->where('invoice_id', $tenant_rent['invoice_id']);
			$this->db->delete('tenant_rent');
		}

		$this->db->where('invoice_id', $invoice_id);
		$this->db->delete('invoice');

		if (file_exists('uploads/invoices/' . $invoice_id . '.pdf'))
			unlink('uploads/invoices/' . $invoice_id . '.pdf');

		$this->session->set_flashdata('success', $this->lang->line('rent_invoice_deleted_successfully'));

		redirect(base_url() . 'invoices', 'refresh');
	}

	function add_notice()
	{
		$data['title']						=	$this->input->post('title');
		$data['notice']						=	$this->input->post('notice');
		$data['created_on']					=	time();
		$data['created_by']					=	$this->session->userdata('user_id');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->insert('notice', $data);

		$this->session->set_flashdata('success', $this->lang->line('notice_added_successfully'));

		redirect(base_url() . 'notices', 'refresh');
	}

	function update_notice($notice_id = '')
	{
		$data['title']						=	$this->input->post('title');
		$data['notice']						=	$this->input->post('notice');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->where('notice_id', $notice_id);
		$this->db->update('notice', $data);

		$this->session->set_flashdata('success', $this->lang->line('notice_updated_successfully'));

		redirect(base_url() . 'notices', 'refresh');
	}

	function remove_notice($notice_id = '')
	{
		$this->db->where('notice_id', $notice_id);
		$this->db->delete('notice');

		$this->session->set_flashdata('success', $this->lang->line('notice_deleted_successfully'));

		redirect(base_url() . 'notices', 'refresh');
	}

	function add_complaint()
	{
		$data['complaint_number']			=	$this->random_strings(11);

		$ext1 								= 	pathinfo($_FILES['complaint_picture_1']['name'], PATHINFO_EXTENSION);
		$ext2 								= 	pathinfo($_FILES['complaint_picture_2']['name'], PATHINFO_EXTENSION);
		$ext3 								= 	pathinfo($_FILES['complaint_video']['name'], PATHINFO_EXTENSION);

		if ($ext1 == 'pdf' || $ext1 == 'PDF' || $ext1 == 'jpeg' || $ext1 == 'JPEG' || $ext1 == 'png' || $ext1 == 'PNG' || $ext1 == 'jpg' || $ext1 == 'JPG') {
			$data['complaint_picture_1']	= 	$data['complaint_number'] . '_complaint_picture_1.' . $ext1;

			move_uploaded_file($_FILES['complaint_picture_1']['tmp_name'], 'uploads/complaints/' . $data['complaint_picture_1']);
		}

		if ($ext2 == 'pdf' || $ext2 == 'PDF' || $ext2 == 'jpeg' || $ext2 == 'JPEG' || $ext2 == 'png' || $ext2 == 'PNG' || $ext2 == 'jpg' || $ext2 == 'JPG') {
			$data['complaint_picture_2'] 	= 	$data['complaint_number'] . '_complaint_picture_2.' . $ext2;

			move_uploaded_file($_FILES['complaint_picture_2']['tmp_name'], 'uploads/complaints/' . $data['complaint_picture_2']);
		}

		if ($ext3 == 'mp4' || $ext3 == 'MP4') {
			$data['complaint_video']		= 	$data['complaint_number'] . '_complaint_video.' . $ext3;

			move_uploaded_file($_FILES['complaint_video']['tmp_name'], 'uploads/complaints/' . $data['complaint_video']);
		}

		$data['subject']					=	$this->input->post('subject');
		$data['status']						=	0;
		$data['tenant_id']					=	($this->session->userdata('user_type') == 3) ? $this->security->xss_clean($this->db->get_where('user', array('user_id' => $this->session->userdata('user_id')))->row()->person_id) : $this->input->post('tenant_id');
		$data['created_on']					=	time();
		$data['created_by']					=	$this->session->userdata('user_id');
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->insert('complaint', $data);

		$data2['complaint_id']					=	$this->db->insert_id();
		$data2['content']					=	$this->input->post('content');
		$data2['created_on']				=	time();
		$data2['created_by']				=	$this->session->userdata('user_id');
		$data2['timestamp']					=	time();
		$data2['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('complaint_details', $data2);

		$this->session->set_flashdata('success', $this->lang->line('complaint_added_successfully'));

		redirect(base_url() . 'complaints', 'refresh');
	}

	private function random_strings($length_of_string)
	{
		$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		return substr(str_shuffle($str_result), 0, $length_of_string);
	}

	function update_complaint($complaint_id = '')
	{
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->where('complaint_id', $complaint_id);
		$this->db->update('complaint', $data);

		$data2['complaint_id']					=	$complaint_id;
		$data2['content']					=	$this->input->post('content');
		$data2['created_on']				=	time();
		$data2['created_by']				=	$this->session->userdata('user_id');
		$data2['timestamp']					=	time();
		$data2['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('complaint_details', $data2);

		$this->session->set_flashdata('success', $this->lang->line('complaint_replied_successfully'));

		redirect(base_url() . 'complaints', 'refresh');
	}

	function close_complaint($complaint_id = '')
	{
		$data['status']						=	1;
		$data['timestamp']					=	time();
		$data['updated_by']					=	$this->session->userdata('user_id');

		$this->db->where('complaint_id', $complaint_id);
		$this->db->update('complaint', $data);

		$this->session->set_flashdata('success', $this->lang->line('complaint_closed_successfully'));

		redirect(base_url() . 'complaints', 'refresh');
	}

	// Function related to adding id type
	function add_id_type()
	{
		$data['name']					=	$this->input->post('name');
		$data['created_on']				= 	time();
		$data['created_by']				= 	$this->session->userdata('user_id');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->insert('id_type', $data);

		$this->session->set_flashdata('success', $this->lang->line('id_type_added_successfully'));

		redirect(base_url('id_type_settings'), 'refresh');
	}

	// Function related to updating id type
	function update_id_type($id_type_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->where('id_type_id', $id_type_id);
		$this->db->update('id_type', $data);

		$this->session->set_flashdata('success', $this->lang->line('id_type_updated_successfully'));

		redirect(base_url('id_type_settings'), 'refresh');
	}

	// Function related to removing id type
	function remove_id_type($id_type_id = '')
	{
		$this->db->where('id_type_id', $id_type_id);
		$this->db->delte('id_type');

		// $this->session->set_flashdata('success', $this->lang->line('id_type_updated_successfully'));

		redirect(base_url('id_type_settings'), 'refresh');
	}

	// Function related to website settings
	function update_website_settings()
	{
		if ($this->input->post('system_name')) {
			$data1['content']			=	$this->input->post('system_name');

			$this->db->where('name', 'system_name');
			$this->db->update('setting', $data1);
		}

		if ($this->input->post('currency')) {
			$data2['content']			=	$this->input->post('currency');

			$this->db->where('name', 'currency');
			$this->db->update('setting', $data2);
		}

		if ($this->input->post('tagline')) {
			$data3['content']			=	$this->input->post('tagline');

			$this->db->where('name', 'tagline');
			$this->db->update('setting', $data3);
		}

		if ($this->input->post('language')) {
			$data4['content']			=	$this->input->post('language');

			$this->db->where('name', 'language');
			$this->db->update('setting', $data4);
		}

		if ($this->input->post('address_line_1') && $this->input->post('address_line_2')) {
			$data6['content']			=	$this->input->post('address_line_1') . '<br>' . $this->input->post('address_line_2');

			$this->db->where('name', 'address');
			$this->db->update('setting', $data6);
		}

		if ($this->input->post('copyright')) {
			$data1['content']			=	$this->input->post('copyright');

			$this->db->where('name', 'copyright');
			$this->db->update('setting', $data1);
		}

		if ($this->input->post('copyright_url')) {
			$data1['content']			=	$this->input->post('copyright_url');

			$this->db->where('name', 'copyright_url');
			$this->db->update('setting', $data1);
		}

		// Font changing switch case of the system
		if ($this->input->post('font')) {
			switch ($this->input->post('font')) {
				case 'PT Sans Narrow':
					$font['content']        =   "'PT Sans Narrow', sans-serif";
					$font_family['content'] =   "PT Sans Narrow";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=PT+Sans+Narrow:wght@400;700&display=swap";
					break;
				case 'Josefin Sans':
					$font['content']        =   "'Josefin Sans', sans-serif";
					$font_family['content'] =   "Josefin Sans";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap";
					break;
				case 'Titillium Web':
					$font['content']        =   "'Titillium Web', sans-serif";
					$font_family['content'] =   "Titillium Web";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap";
					break;
				case 'Mukta':
					$font['content']        =   "'Mukta', sans-serif";
					$font_family['content'] =   "Mukta";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Mukta:wght@200;300;400;500;600;700;800&display=swap";
					break;
				case 'PT Sans':
					$font['content']        =   "'PT Sans', sans-serif";
					$font_family['content'] =   "PT Sans";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap";
					break;
				case 'Rubik':
					$font['content']        =   "'Rubik', sans-serif";
					$font_family['content'] =   "Rubik";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap";
					break;
				case 'Oswald':
					$font['content']        =   "'Oswald', sans-serif";
					$font_family['content'] =   "Oswald";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&display=swap";
					break;
				case 'Poppins':
					$font['content']        =   "'Poppins', sans-serif";
					$font_family['content'] =   "Poppins";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap";
					break;
				case 'Open Sans':
					$font['content']        =   "'Open Sans', sans-serif";
					$font_family['content'] =   "Open Sans";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap";
					break;
				case 'Cantarell':
					$font['content']        =   "'Cantarell', sans-serif";
					$font_family['content'] =   "Cantarell";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Cantarell:ital,wght@0,400;0,700;1,400;1,700&display=swap";
					break;
				case 'Ubuntu':
					$font['content']        =   "'Ubuntu', sans-serif";
					$font_family['content'] =   "Ubuntu";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap";
					break;
				default:
					$font['content']        =   "'PT Sans Narrow', sans-serif";
					$font_family['content'] =   "PT Sans Narrow";
					$font_src['content']    =   "https://fonts.googleapis.com/css2?family=PT+Sans+Narrow:wght@400;700&display=swap";
			}
	
			$this->db->where('name', 'font');
			$this->db->update('setting', $font);
			$this->db->where('name', 'font_family');
			$this->db->update('setting', $font_family);
			$this->db->where('name', 'font_src');
			$this->db->update('setting', $font_src);
		}

		$this->session->set_flashdata('success', $this->lang->line('website_settings_updated_successfully'));

		redirect(base_url() . 'website_settings', 'refresh');
	}

	// Function realted to website favicon update
	function update_website_favicon()
	{
		$ext 							= 	pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION);

		if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
			$favicon 					= 	$this->db->get_where('setting', array('name' => 'favicon'))->row()->content;

			if (isset($favicon)) unlink('uploads/website/' . $favicon);

			$data['content'] 			= 	$_FILES['favicon']['name'];
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['favicon']['tmp_name'], 'uploads/website/' . $data['content']);

			$this->db->where('name', 'favicon');
			$this->db->update('setting', $data);

			$this->session->set_flashdata('success', $this->lang->line('website_favicon_updated_successfully'));

			redirect(base_url() . 'website_settings', 'refresh');
		} else {
			$this->session->set_flashdata('warning', $this->lang->line('tenant_image_supported_type'));

			redirect(base_url() . 'website_settings', 'refresh');
		}
	}

	// Function realted to website login background update
	function update_website_login_bg()
	{
		$ext 							= 	pathinfo($_FILES['login_bg']['name'], PATHINFO_EXTENSION);

		if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
			$login_bg 					= 	$this->db->get_where('setting', array('name' => 'login_bg'))->row()->content;

			if (isset($login_bg)) unlink('uploads/website/' . $login_bg);

			$data['content'] 			= 	$_FILES['login_bg']['name'];
			$data['timestamp']			=	time();
			$data['updated_by']			=	$this->session->userdata('user_id');

			move_uploaded_file($_FILES['login_bg']['tmp_name'], 'uploads/website/' . $data['content']);

			$this->db->where('name', 'login_bg');
			$this->db->update('setting', $data);

			$this->session->set_flashdata('success', $this->lang->line('website_login_background_updated_successfully'));

			redirect(base_url() . 'website_settings', 'refresh');
		} else {
			$this->session->set_flashdata('warning', $this->lang->line('tenant_image_supported_type'));

			redirect(base_url() . 'website_settings', 'refresh');
		}
	}

	// Function related to website smtp
	function update_website_smtp()
	{
		if ($this->input->post('smtp_host')) {
			$data0['content']			=	$this->input->post('smtp_host');

			$this->db->where('name', 'smtp_host');
			$this->db->update('setting', $data0);
		}
		
		if ($this->input->post('smtp_user')) {
			$data1['content']			=	$this->input->post('smtp_user');

			$this->db->where('name', 'smtp_user');
			$this->db->update('setting', $data1);
		}

		if ($this->input->post('smtp_pass')) {
			$data2['content']			=	$this->input->post('smtp_pass');

			$this->db->where('name', 'smtp_pass');
			$this->db->update('setting', $data2);
		}

		$this->session->set_flashdata('success', $this->lang->line('website_smtp_updated_successfully'));

		redirect(base_url() . 'website_settings', 'refresh');
	}

	// Function related to website twilio
    function delete_website_smtp()
    {
        $data['content']			=	'';

        $this->db->where('name', 'smtp_host');
        $this->db->update('setting', $data);
		
		$this->db->where('name', 'smtp_user');
        $this->db->update('setting', $data);

        $this->db->where('name', 'smtp_pass');
        $this->db->update('setting', $data);

        $this->session->set_flashdata('success', $this->lang->line('website_smtp_deleted_successfully'));

		redirect(base_url() . 'website_settings', 'refresh');
    }

	// Function related to website twilio
	function update_website_twilio()
	{
		if ($this->input->post('account_sid')) {
			$data1['content']			=	$this->input->post('account_sid');

			$this->db->where('name', 'account_sid');
			$this->db->update('setting', $data1);
		}

		if ($this->input->post('auth_token')) {
			$data2['content']			=	$this->input->post('auth_token');

			$this->db->where('name', 'auth_token');
			$this->db->update('setting', $data2);
		}

        if ($this->input->post('number')) {
			$data3['content']			=	$this->input->post('number');

			$this->db->where('name', 'number');
			$this->db->update('setting', $data3);
		}

		$this->session->set_flashdata('success', $this->lang->line('website_twilio_updated_successfully'));

		redirect(base_url() . 'website_settings', 'refresh');
	}

    // Function related to website twilio
    function delete_website_twilio()
    {
        $data['content']			=	'';

        $this->db->where('name', 'account_sid');
        $this->db->update('setting', $data);

        $this->db->where('name', 'auth_token');
        $this->db->update('setting', $data);

        $this->db->where('name', 'number');
        $this->db->update('setting', $data);

        $this->session->set_flashdata('success', $this->lang->line('website_twilio_deleted_successfully'));

		redirect(base_url() . 'website_settings', 'refresh');
    }

	// Function related to adding profession
	function add_profession()
	{
		$data['name']					=	$this->input->post('name');
		$data['created_on']				= 	time();
		$data['created_by']				= 	$this->session->userdata('user_id');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->insert('profession', $data);

		$this->session->set_flashdata('success', $this->lang->line('profession_added_successfully'));

		redirect(base_url('profession_settings'), 'refresh');
	}

	// Function related to updating profession
	function update_profession($profession_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->where('profession_id', $profession_id);
		$this->db->update('profession', $data);

		$this->session->set_flashdata('success', $this->lang->line('profession_updated_successfully'));

		redirect(base_url('profession_settings'), 'refresh');
	}

	// Function related to removing profession
	function remove_profession($profession_id = '')
	{
		$this->db->where('profession_id', $profession_id);
		$this->db->delete('profession');

		// $this->session->set_flashdata('success', $this->lang->line('profession_updated_successfully'));

		redirect(base_url('profession_settings'), 'refresh');
	}

	// Function related to adding service
	function add_service()
	{
		$data['name']					=	$this->input->post('name');
		$data['description']					=	$this->input->post('description');
		$data['cost']					=	$this->input->post('cost');
		$data['created_on']				= 	time();
		$data['created_by']				= 	$this->session->userdata('user_id');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->insert('service', $data);

		$this->session->set_flashdata('success', $this->lang->line('service_added_successfully'));

		redirect(base_url('service_settings'), 'refresh');
	}

	// Function related to updating service
	function update_service($service_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['description']					=	$this->input->post('description');
		$data['cost']					=	$this->input->post('cost');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->where('service_id', $service_id);
		$this->db->update('service', $data);

		$this->session->set_flashdata('success', $this->lang->line('service_updated_successfully'));

		redirect(base_url('service_settings'), 'refresh');
	}

	// Function related to removing service
	function remove_service($service_id = '')
	{
		$this->db->where('service_id', $service_id);
		$this->db->delete('service');

		// $this->session->set_flashdata('success', $this->lang->line('profession_updated_successfully'));

		redirect(base_url('service_settings'), 'refresh');
	}

	// Function related to adding payment method
	function add_payment_method()
	{
		$data['name']					=	$this->input->post('name');
		$data['created_on']				= 	time();
		$data['created_by']				= 	$this->session->userdata('user_id');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->insert('payment_method', $data);

		$this->session->set_flashdata('success', $this->lang->line('payment_method_added_successfully'));

		redirect(base_url('payment_method_settings'), 'refresh');
	}

	// Function related to updating payment method
	function update_payment_method($payment_method_id = '')
	{
		$data['name']					=	$this->input->post('name');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->where('payment_method_id', $payment_method_id);
		$this->db->update('payment_method', $data);

		$this->session->set_flashdata('success', $this->lang->line('payment_method_updated_successfully'));

		redirect(base_url('payment_method_settings'), 'refresh');
	}

	// Function related to deleting payment method
	function remove_payment_method($payment_method_id = '')
	{
		$this->db->where('payment_method_id', $payment_method_id);
		$this->db->delete('payment_method');

		// $this->session->set_flashdata('success', $this->lang->line('payment_method_deleted_successfully'));

		redirect(base_url('payment_method_settings'), 'refresh');
	}

	// Function related to adding board member
	function add_board_member()
	{
		if ($_FILES['image']['name']) {
			$ext 						= 	pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

			if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
				$data['image']			=	$_FILES['image']['name'];

				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/board_members/' . $data['image']);
			} else {
				$this->session->set_flashdata('warning', $this->lang->line('tenant_image_supported_type'));

				redirect(base_url() . 'website_settings', 'refresh');
			}
		}		

		$data['name']					=	$this->input->post('name');
		$data['position']				=	$this->input->post('position');
		$data['serial']					=	$this->input->post('serial');
		$data['image']					=	$_FILES['image']['name'];
		$data['created_on']				= 	time();
		$data['created_by']				= 	$this->session->userdata('user_id');
		$data['timestamp']				=	time();
		$data['updated_by']				=	$this->session->userdata('user_id');

		$this->db->insert('board_member', $data);

		$this->session->set_flashdata('success', $this->lang->line('board_member_added_successfully'));

		redirect(base_url() . 'board_member_settings', 'refresh');
	}

	// Function related to updating board member
	function update_board_member($board_member_id = '')
	{
		if ($_FILES['image']['name']) {
			$ext 						= 	pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

			if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'JPEG' || $ext == 'JPG' || $ext == 'PNG') {
				$image 					= 	$this->db->get_where('board_member', array('board_member_id' => $board_member_id))->row()->image;
				
				if (isset($image)) unlink('uploads/board_members/' . $image);

				$data['image']			=	$_FILES['image']['name'];

				move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/board_members/' . $data['image']);
			} else {
				$this->session->set_flashdata('warning', $this->lang->line('tenant_image_supported_type'));

				redirect(base_url() . 'board_member_settings', 'refresh');
			}
		}

		$data['name']					=	$this->input->post('name');
		$data['position']				=	$this->input->post('position');
		$data['serial']					=	$this->input->post('serial');
		$data['timestamp']				= 	time();
		$data['updated_by']				= 	$this->session->userdata('user_id');

		$this->db->where('board_member_id', $board_member_id);
		$this->db->update('board_member', $data);

		$this->session->set_flashdata('success', $this->lang->line('board_member_updated_successfully'));

		redirect(base_url('board_member_settings'), 'refresh');
	}

	function remove_board_member($board_member_id = '')
	{
		$this->db->where('board_member_id', $board_member_id);
		$this->db->delete('board_member');

		redirect(base_url('board_member_settings'), 'refresh');
	}

	function update_profile_settings($user_id = '')
	{
		$db_password 					=	$this->db->get_where('user', array('user_id' => $user_id))->row()->password;
		$given_password 				=	$this->input->post('old_password');

		$existing_email 				= 	$this->db->get_where('user', array('user_id' => $user_id))->row()->email;

		if (password_verify($given_password, $db_password)) {
			if ($existing_email != $this->input->post('email')) {
				$users = $this->db->get('user')->result_array();
				foreach ($users as $user) {
					if ($user['email'] == $this->input->post('email')) {
						$this->session->set_flashdata('warning', $this->lang->line('tenant_email_already_registered'));

						redirect(base_url() . 'profile_settings', 'refresh');
					}
				}
			}

			$data['email']				=	$this->input->post('email');
			if ($this->input->post('new_password') && ($this->input->post('new_password') == $this->input->post('confirm_password'))) {
				$data['password']		=	password_hash($this->input->post('new_password'), PASSWORD_DEFAULT);
			} else {
				$this->session->set_flashdata('warning', $this->lang->line('new_passwords_do_not_match'));

				redirect(base_url() . 'profile_settings', 'refresh');
			}

			$this->db->where('user_id', $user_id);
			$this->db->update('user', $data);

			$this->session->set_flashdata('success', $this->lang->line('profile_updated_successfully'));

			redirect(base_url() . 'profile_settings', 'refresh');
		} else {
			$this->session->set_flashdata('warning', $this->lang->line('passwords_do_not_match'));

			redirect(base_url() . 'profile_settings', 'refresh');
		}
	}
}
