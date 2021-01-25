<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Users extends Base_Controller {
        /**
         * 
         */
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));
        $this->load->model('user_model');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}
	
	/**
	 * dashboard()
	 * Admin Dashboard
	 */
	 
	public function dashboard(){
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}else{
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['users'] = $this->ion_auth->users()->result();
			foreach ($this->data['users'] as $k => $user){
				$this->data['users'][$k]->roles = $this->ion_auth->get_users_roles($user->id)->result();
			}
			$this->data['title'] = 'Dashboard';
			$this->load->view('users/dashboard', $this->data);
		}
	}
	
	/**
	 * login()
	 * Admin User Login
	 */
	
	public function login()
	{
		if ($this->input->post())
		{
			//echo"<pre>";
			//print_r($this->input->post());die;
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				$this->session->set_flashdata('success', $this->ion_auth->messages());
				redirect('users/dashboard', 'refresh');
			}
			else
			{
				$this->session->set_flashdata('error', $this->ion_auth->errors());
				redirect('users/login', 'refresh'); 
			}
		}
		else
		{
			$this->data['message'] = $this->session->flashdata('message');
			$this->data['title'] = 'Admin Login';
			$this->load->view('users/login', $this->data);
		}
	}
	
	/**
	 * admin_user()
	 * Show Admin User Listing
	 */
	public function admin_user()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}else{
			$this->data['users'] = $this->user_model->get_AdminUsers();
			$this->data['title'] = 'Admin Users';
			$this->load->view('users/admin_user', $this->data);
		}
	}
	
	/**
	 * site_user()
	 * Show Admin User Listing
	 */
	public function site_user()
	{
		if (!$this->ion_auth->logged_in()){
			redirect('users/login', 'refresh');
		}else{
			$this->data['users'] = $this->user_model->get_SiteUsers();
			$this->data['title'] = 'Site Users';
			$this->load->view('users/site_user', $this->data);
		}
	}
	
	/**
	 * logout()
	 * Admin User Login
	 */
	public function logout()
	{
		$this->data['title'] = "Logout";
		$logout = $this->ion_auth->logout();
		$this->session->set_flashdata('success', $this->ion_auth->messages());
		redirect('users/login', 'refresh');
	}
	
	/**
	 * change_password()
	 * Change Admin User Password
	 */
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('users/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{
			if(!empty(validation_errors())){
				$this->session->set_flashdata('error', validation_errors());
			}
			//$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			/*$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name'    => 'new',
				'id'      => 'new',
				'type'    => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name'    => 'new_confirm',
				'id'      => 'new_confirm',
				'type'    => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);*/
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);

			// render
			$this->load->view('users/change_password', $this->data);
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('sucsess', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('error', $this->ion_auth->errors());
				redirect('users/change_password', 'refresh');
			}
		}
	}
        /**
         * 
         */
	// forgot password
	public function forgot_password()
	{
		// setting validation rules by checking whether identity is username or email
		if($this->config->item('identity', 'ion_auth') != 'email' )
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() == false)
		{
			$this->data['type'] = $this->config->item('identity','ion_auth');
			// setup the input
			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
			);

			if ( $this->config->item('identity', 'ion_auth') != 'email' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->load->view('users/forgot_password', $this->data); 
		}
		else
		{
			$identity_column = $this->config->item('identity','ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if(empty($identity)) {

	            		if($this->config->item('identity', 'ion_auth') != 'email')
		            	{
		            		$this->ion_auth->set_error('forgot_password_identity_not_found');
		            	}
		            	else
		            	{
		            	   $this->ion_auth->set_error('forgot_password_email_not_found');
		            	}

		                $this->session->set_flashdata('message', $this->ion_auth->errors());
                		redirect("users/forgot_password", 'refresh');
            		}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("users/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("users/forgot_password", 'refresh');
			}
		}
	}
        /**
         * 
         */
	// reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name'    => 'new_confirm',
					'id'      => 'new_confirm',
					'type'    => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				$this->load->view('users/reset_password', $this->data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("users/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('users/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("users/forgot_password", 'refresh');
		}
	}
	
    /**
	 * activate($id)
	 * Activate Admin User
	 * @Params $id int
	 */
	 
	public function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->logged_in())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			$msg['type'] = 'success';
			$msg['text'] = "User Status Updated Successfully.................!";
			$msg = json_encode($msg);
			echo $msg; exit;
			
			//redirect("users/admin_user", 'refresh');
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("users/forgot_password", 'refresh');
		}
	}
	
	/**
	 * deactivate($id)
	 * Deactivate Admin User
	 * @Params $id int
	 */
	
	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int) $id;
		// do we really want to deactivate?
		if ($this->input->post('confirm') == 'yes')
		{
			// do we have the right userlevel?
			if ($this->ion_auth->logged_in())
			{
				$result=$this->ion_auth->deactivate($id);
				if($result){
					$msg['type'] = 'success';
					$msg['text'] = "User Status Updated Successfully.................!";
					$msg = json_encode($msg);
					echo $msg; exit;
				}
			}
		}

		// redirect them back to the auth page
		redirect('users/admin_user', 'refresh');
		
	}
	
	/**
	 * create_user()
	 * Create new Admin User
	 */
	
	/*public function create_user()
    {
        

        if (!$this->ion_auth->logged_in())
        {
            redirect('users/logout', 'refresh');
        }
         
		if(!$this->ion_auth->has_permission('admin_create-users')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/admin_user', 'refresh');
		}



        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        if($identity_column!=='email')
        {
            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true)
        {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'role_id'    => $this->input->post('role_id'),
                'phone'      => $this->input->post('phone'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($identity, $password, $email, $additional_data))
        {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message1','<div id="ajax-message" class="alert alert-danger alert-dismissible fade in" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button><strong>Test</strong> </div>');
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("users/admin_user", 'refresh');
        }
        else
        {
            // display the create user form
            // set the flash data error message if there is one
           // $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
           
			$this->data['title'] = $this->lang->line('create_user_heading');
			
            $this->load->view('users/create_user', $this->data);
        }
    }*/

    public function create_user()
	{	
        if (!$this->ion_auth->logged_in())
        {
            redirect('users/logout', 'refresh');
        }
         
		if(!$this->ion_auth->has_permission('admin_create-users')){
			$this->session->set_flashdata('error', "You Don't Have Permission to Perform the Operation");
			redirect('users/admin_user', 'refresh');
		}
		
		if ($this->input->post()){
		$tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        if($identity_column!=='email')
        {
            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true)
        {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'role_id'    => $this->input->post('role_id'),
                'phone'      => $this->input->post('phone'),
            );
        }

		if ($this->form_validation->run() == true &&  $this->ion_auth->register($identity, $password, $email, $additional_data)){
				$this->session->set_flashdata('success_val', $this->ion_auth->messages());
				$this->data['message']=$this->ion_auth->messages();
				redirect("users/admin_user", 'refresh');
			}else{
				
				$this->data['message']=(validation_errors() ? validation_errors() : $this->ion_auth->errors());
				$this->session->set_flashdata('error_val', $this->data['message']);
				$this->data['title'] = $this->lang->line('create_user_heading');
				//$this->load->view('users/create_user', $this->data);
				redirect("users/create_user", 'refresh');
			}
		}
		else
		{
		
			$this->data['message']="";
			$this->data['title'] = $this->lang->line('create_user_heading');		
			$this->load->view('users/create_user', $this->data);
		}
		
	}
	
	
	/**
	 * edit_user($id)
	 * Edit Admin User
	 * @Input Parameter $id
	 *	 
	 */
	
	public function edit_user($id)
	{
		$this->data['title'] = $this->lang->line('edit_user_heading');

		if (!$this->ion_auth->logged_in())
		{
			redirect('users/logout', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$roles=$this->ion_auth->roles()->result_array();
		$currentroles = $this->ion_auth->get_users_roles($id)->result();

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
		//$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');

		if (isset($_POST) && !empty($_POST))
		{
			
			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					 'role_id'   => $this->input->post('role_id'),
					'phone'      => $this->input->post('phone'),
				);
			// check to see if we are updating the user
			   if($this->ion_auth->update($user->id, $data)){
					redirect('users/admin_user', 'refresh');
			    }else{
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->errors() );
					redirect('users/admin_user', 'refresh');
			    }

			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['user'] = $user;
		$this->data['roles'] = $roles;
		$this->data['currentroles'] = $currentroles;
		$this->load->view('users/edit_user', $this->data);
	}
	
	/**
	 * delete_user($id)
	 * Deactivate Admin User
	 * @Params $id int
	 */
	
	public function delete_user($id = NULL)
	{
		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the home page because they must be an administrator to view this
			redirect('users/logout', 'refresh');
		}
		
		$id = (int) $id;
		// do we really want to deactivate?
		//if ($this->input->post('confirm') == 'yes')
		//{
			// do we have the right userlevel?
			if ($this->ion_auth->logged_in())
			{
				$this->ion_auth->delete($id);
				$this->user_model->removeUserFromContactFriends($id);
			}
		//}

		// redirect them back to the auth page
		redirect('users/admin_user', 'refresh');
		
	}
	
	/**
	 * create_role()
	 * Create New Role For Admin Section
	 */
	
	public function create_role()
	{
		$this->data['title'] = $this->lang->line('create_role_title');

		if (!$this->ion_auth->logged_in())
		{
			redirect('users', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('role_name', $this->lang->line('create_role_validation_name_label'), 'required|alpha_dash');

		if ($this->form_validation->run() == TRUE)
		{
			$new_role_id = $this->ion_auth->create_role($this->input->post('role_name'), $this->input->post('description'));
			if($new_role_id)
			{
				// check to see if we are creating the role
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("users", 'refresh');
			}
		}
		else
		{
			// display the create role form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['role_name'] = array(
				'name'  => 'role_name',
				'id'    => 'role_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('role_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			$this->_render_page('auth/create_role', $this->data);
		}
	}
        /**
         * 
         */
	// edit a role
	public function edit_role($id)
	{
		// bail if no role id given
		if(!$id || empty($id))
		{
			redirect('users', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_role_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('users', 'refresh');
		}

		$role = $this->ion_auth->role($id)->row();

		// validate form input
		$this->form_validation->set_rules('role_name', $this->lang->line('edit_role_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$role_update = $this->ion_auth->update_role($id, $_POST['role_name'], $_POST['role_description']);

				if($role_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_role_saved'));
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("users", 'refresh');
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['role'] = $role;

		$readonly = $this->config->item('admin_role', 'ion_auth') === $role->name ? 'readonly' : '';

		$this->data['role_name'] = array(
			'name'    => 'role_name',
			'id'      => 'role_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('role_name', $role->name),
			$readonly => $readonly,
		);
		$this->data['role_description'] = array(
			'name'  => 'role_description',
			'id'    => 'role_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('role_description', $role->description),
		);

		$this->_render_page('users/edit_role', $this->data);
	}

        /**
         * 
         * @return type
         */
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}
        /**
         * 
         * @return boolean
         */
	public function _valid_csrf_nonce()
	{
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
        /**
         * 
         * @param type $view
         * @param type $data
         * @param type $returnhtml
         * @return type
         */
	public function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense 
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}

}
