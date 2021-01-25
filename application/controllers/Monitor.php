<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor extends Base_Controller{
	public $role_id;
	public $user_id;

	public function __construct(){
    	parent::__construct();
		$this->load->library('ion_auth');
		$this->load->helper(array('url','language'));
		$this->lang->load('auth');
		$this->load->model('monitor_model');
		$this->load->model('user_model');
		$this->load->model('common_model','common');
		$this->load->model('post_model','post');
		$this->load->database();
		$user_data = $this->session->userdata();
		$this->role_id = $user_data['role_id'];
		$this->user_id = $user_data['user_id'];
		$this->blockfile_location    = 'assets/images/blockwords/';		
	}
	protected $validation_rules = array(
        'addblock' => array(
            array(
                'field' => 'b_text',
                'label' => 'Text',
                'rules' => 'trim|required|xss_clean|html_escape'
            )
        ),
		'editblock' => array(
            array(
                'field' => 'b_text',
                'label' => 'Text',
                'rules' => 'trim|required|xss_clean|html_escape'
            )
        )
	);

	/* Details */
	public function control(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}else{
            $this->data['view_status']       = checkPermission($this->role_id,'view_status');
		    if($this->data['view_status']){
                $this->data['add_status']    = checkPermission($this->role_id,'add_status');
                $this->data['edit_status']   = checkPermission($this->role_id,'edit_status');
                $this->data['delete_status'] = checkPermission($this->role_id,'delete_status');

				$this->data['blocked_text_res'] = $this->monitor_model->getBlockedText();
                $this->data['title'] = 'Control';
				$this->load->view('monitor/control',$this->data);
				
			}
            else {
                redirect(base_url().'errors');
            }
		}
	}    

	public function addblock() {
		if(checkPermission($this->role_id,'add_status')){
			if ($this->input->post()){
				try{
					$this->form_validation->set_rules($this->validation_rules['addblock']);
					if(!$this->form_validation->run()){
						throw new Exception(validation_errors());
					}
					$post['text'] = $this->input->post('b_text');
					$post['color'] = $this->input->post('b_color');
					$post['reason'] = $this->input->post('b_reason');
					$post['by_file'] = 0;
					$check = $this->common->getTableValue('blocked_text', 'text', $post['text']);
					if ($check){
						$msg = 'Block text is already added.';
						$url = 'monitor/addblock';
						redirectWithMsgFailed($msg,$url);
					}

					$record_id = $this->common->addData('blocked_text',$post);

					if(!$record_id){
						throw new Exception('Failed to add!');
					}
					$msg = 'Block text added successfully!';
					$url = 'monitor/control';
					redirectWithMsgSuccess($msg,$url);
				}
				catch(Exception $e){
					$msg = $e->getMessage();
					$url = 'monitor/addblock';
					redirectWithMsgFailed($msg,$url);
				}			
			}
			$this->data['title'] = 'Control';
			$this->data['block_colors'] = $this->common->block_colors;
			$this->load->view('monitor/addblock', $this->data);
		}
		else{
			redirect(base_url().'errors');
		}
	}
	public function editblock() {
		if(checkPermission($this->role_id,'edit_status')){
			$record_id = abs(intval($this->uri->segment(3)));
			if ($this->input->post()){
				try{
					$this->form_validation->set_rules($this->validation_rules['editblock']);
					if(!$this->form_validation->run()){
						throw new Exception(validation_errors());
					}
					$post['text'] = $this->input->post('b_text');
					$post['color'] = $this->input->post('b_color');
					$post['reason'] = $this->input->post('b_reason');
					$post['by_file'] = 0;
					$check = $this->common->getTableValue('blocked_text', 'text', $post['text']);
					if ($check){
						$msg = 'Block text is already added.';
						$url = 'monitor/addblock';
						redirectWithMsgFailed($msg,$url);
					}
					$update = $this->common->updateData('blocked_text', $post, array('id'=>$record_id));

					if(!$update){
						throw new Exception('Failed to add!');
					}
					$msg = 'Block text updated successfully!';
					$url = 'monitor/control';
					redirectWithMsgSuccess($msg,$url);
				}
				catch(Exception $e){
					$msg = $e->getMessage();
					$url = 'monitor/editblock';
					redirectWithMsgFailed($msg,$url);
				}			
			}
			$this->data['block_edit'] = $this->common->getRowDetails('blocked_text', array('id'=>$record_id));
			if(!empty($this->data['block_edit'])){
				$this->data['title'] = 'Control';
				$this->data['block_colors'] = $this->common->block_colors;
				$this->load->view('monitor/editblock', $this->data);
			}
			else{
				redirect(base_url().'errors');
			}			
		}
		else{
			redirect(base_url().'errors');
		}
	}

	public function delblock() {
		if(checkPermission($this->role_id,'delete_status')){
			$record_id = abs(intval($this->uri->segment(3)));
			try{
				$res = $this->common->deleteData('blocked_text',array('id'=>$record_id));

				if(!$res){
					throw new Exception("Failed to delete!");
				}

				$msg = 'Block text removed successfully!';
				$url = 'monitor/control';
				redirectWithMsgSuccess($msg,$url);
			}
			catch(Exception $e){
				$msg = $e->getMessage();
				$url = 'monitor/control';
				redirectWithMsgFailed($msg,$url);
			}	
		}
		else{
			redirect(base_url().'errors');
		}	
	}

	public function filter() {
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}else{
			$this->data['view_status']       = checkPermission($this->role_id,'view_status');
		    if($this->data['view_status']){
                $this->data['add_status']    = checkPermission($this->role_id,'add_status');
                $this->data['edit_status']   = checkPermission($this->role_id,'edit_status');
                $this->data['delete_status'] = checkPermission($this->role_id,'delete_status');

				$this->data['manual_block_reason'] = $this->common->manual_block_reason;
				$this->data['title'] = 'Filter';
				$this->load->view('monitor/filter',$this->data);	
			}
            else {
                redirect(base_url().'errors');
            }			
		}
	}
	public function userdetail() {
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}else{
			$userid = abs(intval($this->uri->segment(3)));
			$this->data['view_status']       = checkPermission($this->role_id,'view_status');
		    if($this->data['view_status']){
                $this->data['add_status']    = checkPermission($this->role_id,'add_status');
                $this->data['edit_status']   = checkPermission($this->role_id,'edit_status');
                $this->data['delete_status'] = checkPermission($this->role_id,'delete_status');

				$this->data['manual_block_reason'] = $this->common->manual_block_reason;
				$this->data['userid'] = $userid;
				
				$this->data['title'] = 'User detail';
				$this->load->view('monitor/userfilter',$this->data);	
			}
            else {
                redirect(base_url().'errors');
            }
			
		}
	}

	public function user_filter_list() {
		if(!$_SERVER['HTTP_REFERER']) redirect(base_url());

        $this->post->column_order  = array('a.id');
        $this->post->column_search = array('b.first_name', 'b.last_name', 'a.content');

		$userid = (int) $_POST['userid'];
		$pstatus = 1;
		$res = $this->post->postFilterList('show_list', $pstatus, $userid);
        $data = array();
		$no = $_POST['start'];
		
        foreach ($res as $r){
            $no++;
            $row = array();
            $row[] = $no.'.';
            $row[] = $r->data_f_name.' '.$r->data_l_name;
            $row[] = $r->data_content;
			$row[] = $r->ip_address;
			$row[] = $r->state.' '.$r->city.' '.$r->address.' '.$r->country;
			$row[] = $r->reason;

			if ($r->color == 'yellow') {
				$btn_color = "warning";
			} else if($r->color == 'orange'){
				$btn_color = "c_orange";
			} else if ($r->color == 'red') {
				$btn_color = "danger";
			} else if ($r->color == 'green'){
				$btn_color = "success";
			} else {
				$btn_color = "success";
			}
			if ($r->blocked_status == 1){
				if ($r->blocked_way == 1){
					$row[] = '<span class="btn btn-'.$btn_color.' btn-xs reported_item_status">AutoBlocked</span>';
				}else {
					$row[] = '<span class="btn btn-'.$btn_color.' btn-xs reported_item_status">Manually Blocked</span>';
				}
			}else{
				$row[] = '<span class="btn btn-success btn-xs reported_item_status">Active</span>';
			}

			if ($r->blocked_status == 0){
				$row[] = '<a href="'.base_url().'monitor/manualblockpost/'.$r->data_item_id.'" class="mblock btn btn-info btn-xs" title="You can block post manually here">Block</a>';
			}else {
				$tmp = '<a href="'.base_url().'monitor/manualunblockpost/'.$r->data_item_id.'" class="munblock btn btn-info btn-xs" title="Unblock post">UnBlock</a>';
				$user = $this->common->getRowDetails('users',array('id'=>$r->data_userid));
				$status = $user->status;
				if ($status == 1) {
					$tmp .= '<a href="'.base_url().'monitor/deactivateuser/'.$r->data_userid.'" class="btn btn-danger btn-xs mdeactivate" title="Deactivate User"><i class="fa fa-lock"></i></a>';
				}
				$ip = $user->ip_address;
				$ip_blocked = $this->common->getRowDetails('blocked_ip',array('ip_address'=>$ip));
				if (!$ip_blocked){
					$tmp .= '<a href="'.base_url().'monitor/blockip/'.$r->data_userid.'" class="btn btn-danger btn-xs mblockip" title="Block ip"><i class="fa fa-times"></i></a>';
				}
				$tmp .= '<a href="'.base_url().'monitor/deleteuser/'.$r->data_userid.'" class="btn btn-danger btn-xs mdelete" title="Delete User"><i class="fa fa-trash"></i></a>';
				$row[] = $tmp;
			}
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($res),
            "recordsFiltered" => $this->post->count_postfilter_list(NULL, $pstatus, $userid),
            "data" => $data,
        );
        echo json_encode($output);
	}

	public function filter_list() {
		if(!$_SERVER['HTTP_REFERER']) redirect(base_url());

        $this->post->column_order  = array('a.id');
        $this->post->column_search = array('b.first_name', 'b.last_name', 'a.content');

		$pstatus = (int) $_POST['pstatus'];
		$res = $this->post->postFilterList('show_list', $pstatus);
        $data = array();
		$no = $_POST['start'];
		
        foreach ($res as $r){
            $no++;
            $row = array();
            $row[] = $no.'.';
            $row[] = $r->data_f_name.' '.$r->data_l_name;
            $row[] = $r->data_content;
			$row[] = $r->ip_address;
			$row[] = $r->state.' '.$r->city.' '.$r->address.' '.$r->country;
			$row[] = $r->reason;

			if ($r->color == 'yellow') {
				$btn_color = "warning";
			} else if($r->color == 'orange'){
				$btn_color = "c_orange";
			} else if ($r->color == 'red') {
				$btn_color = "danger";
			} else if ($r->color == 'green'){
				$btn_color = "success";
			} else {
				$btn_color = "success";
			}
			if ($r->blocked_status == 1){
				if ($r->blocked_way == 1){
					$row[] = '<span class="btn btn-'.$btn_color.' btn-xs reported_item_status">AutoBlocked</span>';
				}else {
					$row[] = '<span class="btn btn-'.$btn_color.' btn-xs reported_item_status">Manually Blocked</span>';
				}
			}else{
				$row[] = '<span class="btn btn-success btn-xs reported_item_status">Active</span>';
			}

			if ($r->blocked_status == 0){
				$row[] = '<a href="'.base_url().'monitor/manualblockpost/'.$r->data_item_id.'" class="mblock btn btn-info btn-xs" title="You can block post manually here">Block</a>';
			}else {
				$tmp = '<a href="'.base_url().'monitor/manualunblockpost/'.$r->data_item_id.'" class="munblock btn btn-info btn-xs" title="Unblock post">UnBlock</a>';
				$user = $this->common->getRowDetails('users',array('id'=>$r->data_userid));
				$status = $user->status;
				if ($status == 1) {
					$tmp .= '<a href="'.base_url().'monitor/deactivateuser/'.$r->data_userid.'" class="btn btn-danger btn-xs mdeactivate" title="Deactivate User"><i class="fa fa-lock"></i></a>';
				}
				$ip = $user->ip_address;
				$ip_blocked = $this->common->getRowDetails('blocked_ip',array('ip_address'=>$ip));
				if (!$ip_blocked){
					$tmp .= '<a href="'.base_url().'monitor/blockip/'.$r->data_userid.'" class="btn btn-danger btn-xs mblockip" title="Block ip"><i class="fa fa-times"></i></a>';
				}
				$tmp .= '<a href="'.base_url().'monitor/deleteuser/'.$r->data_userid.'" class="btn btn-danger btn-xs mdelete" title="Delete User"><i class="fa fa-trash"></i></a>';
				$row[] = $tmp;
			}
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($res),
            "recordsFiltered" => $this->post->count_postfilter_list(NULL, $pstatus),
            "data" => $data,
        );
        echo json_encode($output);
	}
	public function manualblockpost() {
		try{
			$record_id = abs(intval($this->uri->segment(3)));
			$reasonid = $this->input->get('reasonid');
			$res = $this->post->manual_blockpost($record_id, $reasonid);

			if(!$res){
				throw new Exception("Failed to block!");
			}

			$msg = "Post's blocked manually!";
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgSuccess($msg,$url, 'success_val', TRUE);
		}
		catch(Exception $e){
			$msg = $e->getMessage();
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgFailed($msg,$url,'error_val', TRUE);
		}	
	}

	public function manualunblockpost() {
		try{
			$record_id = abs(intval($this->uri->segment(3)));
			$res = $this->post->manual_unblockpost($record_id);

			if(!$res){
				throw new Exception("Failed to unblock!");
			}

			$msg = "Post's unblocked successfully!";
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgSuccess($msg,$url, 'success_val', TRUE);
		}
		catch(Exception $e){
			$msg = $e->getMessage();
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgFailed($msg,$url,'error_val', TRUE);
		}
	}

	public function deactivateuser() {
		try{			
			$user_id = abs(intval($this->uri->segment(3)));
			$user = $this->common->getRowDetails('users',array('id'=>$user_id));
			$status = $user->status;
			
			$result=$this->ion_auth->deactivate($user_id);
			if($result) {
				$msg = "A user's deactivated Successfully!";
				$url = $_SERVER['HTTP_REFERER'];
				redirectWithMsgSuccess($msg,$url, 'success_val', TRUE);
			} else {
				$msg = 'Something went wrong. Please Try Again Later.';
				$url = $_SERVER['HTTP_REFERER'];
				redirectWithMsgFailed($msg,$url,'error_val', TRUE);
			}
		}
		catch(Exception $e){
			$msg = $e->getMessage();
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgFailed($msg,$url,'error_val', TRUE);
		}
	}
	public function activateuser() {
		try{			
			$user_id = abs(intval($this->uri->segment(3)));
			$user = $this->common->getRowDetails('users',array('id'=>$user_id));
			$status = $user->status;
			
			$result=$this->ion_auth->activate($user_id);
			if($result) {
				$msg = "A user's activated Successfully!";
				$url = $_SERVER['HTTP_REFERER'];
				redirectWithMsgSuccess($msg,$url, 'success_val', TRUE);
			} else {
				$msg = 'Something went wrong. Please Try Again Later.';
				$url = $_SERVER['HTTP_REFERER'];
				redirectWithMsgFailed($msg,$url,'error_val', TRUE);
			}
		}
		catch(Exception $e){
			$msg = $e->getMessage();
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgFailed($msg,$url,'error_val', TRUE);
		}
	}

	public function blockip() {
		try{
			$user_id = abs(intval($this->uri->segment(3)));
			$user = $this->common->getRowDetails('users',array('id'=>$user_id));
			$ip = $user->ip_address;
			$user_data = $this->session->userdata();
			$action_user_id   = $user_data['user_id'];
			
			$blocked_ip_id = $this->user_model->insert_block_ip(array('ip_address' => $ip, 'created_by' => $action_user_id));
			if($blocked_ip_id) {
				$msg = "Ip Address Blocked Successfully!";
				$url = $_SERVER['HTTP_REFERER'];
				redirectWithMsgSuccess($msg,$url, 'success_val', TRUE);
			} else {
				$msg = 'Something went wrong. Please Try Again Later.';
				$url = $_SERVER['HTTP_REFERER'];
				redirectWithMsgFailed($msg,$url,'error_val', TRUE);
			}
		}
		catch(Exception $e){
			$msg = $e->getMessage();
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgFailed($msg,$url,'error_val', TRUE);
		}
	}
	public function unblockip() {
		try{
			$user_id = abs(intval($this->uri->segment(3)));
			$user = $this->common->getRowDetails('users',array('id'=>$user_id));
			$ip = $user->ip_address;
			
			$this->user_model->delete(array('ip_address' => $ip), 'blocked_ip');
			$msg = "Ip Address Unlocked Successfully!";
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgSuccess($msg,$url, 'success_val', TRUE);
		}
		catch(Exception $e){
			$msg = $e->getMessage();
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgFailed($msg,$url,'error_val', TRUE);
		}
	}

	public function deleteuser() {
		try{
			$user_id = abs(intval($this->uri->segment(3)));
			$id = (int) $user_id;
			$res = $this->common->deleteData('users',array('id'=>$id));
			if(!$res){
				throw new Exception("Failed to delete!");
			}
			$msg = "A user's deleted Successfully!";
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgSuccess($msg,$url, 'success_val', TRUE);
		}
		catch(Exception $e){
			$msg = $e->getMessage();
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgFailed($msg,$url,'error_val', TRUE);
		}
	}

	public function user() {
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}else{
			$this->data['view_status'] = checkPermission($this->role_id,'view_status');
		    if($this->data['view_status']){
				$users = $this->user_model->get_SiteUsers();
				$new_users = array();
				foreach ($users as $user){
					$flagged_user_pcn = $this->post->get_flagged_users_post($user->id);
					if ($flagged_user_pcn != 0){
						$user->count = $flagged_user_pcn;
						$new_users[] = $user;
					}
				}

				$this->data['title'] = 'Flagged Users';
				$this->data['users'] = $new_users;
				$this->load->view('monitor/flageduser',$this->data);
			}
			else {
                redirect(base_url().'errors');
            }
		}
	}
	public function update_block_file_content($content, $file_basename) {
		try {
			foreach ($content as $text) {
				if ($text == '' || $text == FALSE){
					continue;
				}
				$check = $this->common->getTableValue('blocked_text', 'text', $text);
				if (!$check) {
					$addedtext['text'] = $text;
					$addedtext['color'] = $this->common->blockfile_color;
					$addedtext['reason'] = $this->common->blockfile_reason . " , file name: ". $file_basename;
					$addedtext['by_file'] = 1;
					$record_id = $this->common->addData('blocked_text',$addedtext);
				}
			}
			return 0;
		} catch(Exception $e){
			return -1;
		}
	}
	public function importfile() {
		$file = $_FILES["blockedwords_file"]["name"];
		$file_tmp  = $_FILES['blockedwords_file']['tmp_name'];
		$file_size = $_FILES['blockedwords_file']['size'];
		$file_type = $_FILES['blockedwords_file']['type'];		
		$file_ext = strtolower(pathinfo($file,PATHINFO_EXTENSION));
		if ($file_ext != 'csv' && $file_ext != 'txt') {
			$msg = 'Sorry, only CSV & TXT files are allowed.'.$file_ext;
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgFailed($msg,$url,'error_val', TRUE);
		}
		if ($file_size > 2000000) {
			$msg = "Sorry, file is too large.";
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgFailed($msg,$url,'error_val', TRUE);
		}
		
		$file_basename = basename($file);
		$target_file = $this->blockfile_location.$file_basename;

		if (file_exists($target_file)){
			$msg = "Sorry, file already exists.";
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgFailed($msg,$url,'error_val', TRUE);
		}
		move_uploaded_file($file_tmp, $target_file);
		$search = array("\r\n", "\n", PHP_EOL);
		if ($file_ext == 'txt') {
			$content = array();
			$fp = fopen($target_file, "r");
			
			if ($fp) {
				while (!feof($fp)) {
					$buffer = fgets($fp, 256);
					$buffer = str_replace($search, "", $buffer);
					$content[] = $buffer;
				}
				fclose($fp);
			}

			$res = $this->update_block_file_content($content, $file_basename);
			if ($res == -1){
				$msg = "Sorry, something went wrong.";
				$url = $_SERVER['HTTP_REFERER'];
				redirectWithMsgFailed($msg,$url,'error_val', TRUE);
			}
			$msg = 'Block texts added successfully!';
			$url = 'monitor/control';
			redirectWithMsgSuccess($msg,$url);
		}
		else if ($file_ext == 'csv'){
			$content = array();
			$fp = fopen($target_file, "r");
			if ($fp) {
				while (($data = fgetcsv($fp)) !== FALSE) {
					$buffer = $data[0];
					$buffer = str_replace($search, "", $buffer);
					$content[] = $buffer;
				}
				fclose($fp);
			}
			$res = $this->update_block_file_content($content, $file_basename);
			if ($res == -1){
				$msg = "Sorry, something went wrong.";
				$url = $_SERVER['HTTP_REFERER'];
				redirectWithMsgFailed($msg,$url,'error_val', TRUE);
			}
			$msg = 'Block texts added successfully!';
			$url = 'monitor/control';
			redirectWithMsgSuccess($msg,$url);

		} else {
			$msg = 'Sorry, something went wrong.'.$file_ext;
			$url = $_SERVER['HTTP_REFERER'];
			redirectWithMsgFailed($msg,$url,'error_val', TRUE);
		}


		$msg = "Uploaded successfully!";
		$url = $_SERVER['HTTP_REFERER'];
		redirectWithMsgSuccess($msg,$url, 'success_val', TRUE);
		
	}
}