<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MY_Controller
{
	function __construct() {
		parent::__construct();
	}

	function index() {
		$this->login();
	}

	function login() {
		$this->load->model('account_model');
		$user_role = $this->session->userdata('user_role');
		$user_id   = $this->session->userdata('user_id');
		
		if($this->session->userdata('user_id')) {
			if(!empty($user_role) && $user_role==User_role::SUPER_ADMIN) {
				redirect('console/dashboard','refresh');
			}
		}
		$this->load_view('login_view','Login');
	}

	function ajax_login() {
		if($this->input->is_ajax_request()) {
			$this->load->library('form_validation');
			if ($this->form_validation->run('login_form') == FALSE) {
				echo json_encode($this->form_validation->error_array());
				exit;
			}
			$status     = 'success';
			$error_type = '';
			$post       = $this->input->post();
			$login_id   = $post['email_id'];
			if(isset($post['action'])) {
				$password = $post['password'];
			} else {
				$password = $this->hash_password($post['password']);
			}
			$this->load->model('account_model');
			$user_data = $this->account_model->get_user_data_by_login_id($login_id);
			if( ! empty($user_data))
			{
				if($user_data->role == User_role::MEMBER && $user_data->is_login_rights == 0) {
					$status = 'fail';
					$error_type = 'no_login_rights';
				} else if($user_data->user_status == User_status::ACTIVE) {
					if($user_data->password == $password) {
						$ipaddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'UNKNOWN';
						if($this->account_model->update_login_data($user_data->user_id, $ipaddress)) {
							/* to do
							*  for trainers and members add access token and athlete id.
							*/
							$session_data = array(
								'user_id' 	=> $user_data->user_id,
								'user_role' => $user_data->role,
								'user_name' => toProperCase($user_data->first_name .' '. $user_data->last_name),
								'email' 	=> $user_data->email,
							);
							$this->session->set_userdata($session_data);
						} else {
							$status     = 'fail';
							$error_type = 'database';
						}
					} else {
						$status     = 'error';
						$error_type = 'password';
					}
				} else if($user_data->user_status == User_status::DEACTIVATED) {
					$status     = 'fail';
					$error_type = 'deactivated';
				} else if($user_data->user_status == User_status::INACTIVE) {
					$status     = 'fail';
					$error_type = 'inactive';
				}
			} else {
				$status     = 'error';
				$error_type = 'login';
			}
			echo json_encode(array('status' => $status, 'error_type' => $error_type));
		} else {
			show_400();
		}
	}

	/*
	function ajax_forgot_password() {
		if($this->input->is_ajax_request())
		{
			$this->load->library('form_validation');
			if ($this->form_validation->run('forgot_password_form') == FALSE)
			{
				echo json_encode($this->form_validation->error_array());
				exit;
			}
			$status = 'success';
			$error_type = '';
			$email = $this->input->post('email');
			$this->load->model('account_model');
			$user_data = $this->account_model->get_table_data_row('user_master', array('email' => $email, 'user_deleted' => Deleted_status::NOT_DELETED));

			if( ! empty($user_data))
			{
				if($user_data->user_status == User_status::ACTIVE)
				{
					$reset_code = md5($email.time());
					$this->load->model('verification_model');
					if($this->verification_model->add_password_reset_code($user_data->user_id,$reset_code))
					{
						if( ! $this->_send_reset_link($user_data,$reset_code,'reset_password'))
						{
							$status = 'fail';
							$error_type = 'mail';
						}
					}
					else
					{
						$status = 'fail';
						$error_type = 'database';
					}
				}
				else if($user_data->user_status == User_status::DEACTIVATED)
				{
					$status = 'fail';
					$error_type = 'deactivated';
				}
				else if($user_data->user_status == User_status::INACTIVE)
				{
					$status = 'fail';
					$error_type = 'inactive';
				}
			}
			else
			{
				$status = 'error';
				$error_type = 'email';
			}
			echo json_encode(array('status' => $status, 'error_type' => $error_type));
		}
		else
		{
			show_400();
		}
	}

	function reset_password() {
		$reset_code = $this->uri->segment(3);
		$this->content->reset_code = $reset_code;
		if($this->session->userdata('user_id')) {
			redirect('dashboard','refresh');
		} else if($reset_code == '') {
			redirect('','refresh');
		}

		$this->load->model('verification_model');
		$code_data = $this->verification_model->get_data_by_reset_code($reset_code);
		if(empty($code_data)) {
			show_404_page();
		}
		if($code_data) {
			$expire_time = $code_data->created_on + (30*60);
			if( ! ($expire_time > time())) {
				show_410('Link Expired !');
			}
		} else {
			show_404_page();
		}
		$this->load_view('reset_password_view','Reset Password');
	}

	function set_password() {
		$reset_code = $this->uri->segment(3);
		$this->content->reset_code = $reset_code;
		if($this->session->userdata('user_id')) {
			redirect('account','refresh');
		} else if($reset_code == '') {
			redirect('','refresh');
		}

		$this->load->model('verification_model');
		$code_data = $this->verification_model->get_data_by_reset_code($reset_code);
		if(empty($code_data)) {
			show_404_page();
		}

		if($code_data) {
			$expire_time = $code_data->created_on + (30*60);
			if( ! ($expire_time > time()))
			{
				show_410('Link Expired !');
			}
		} else {
			show_404_page();
		}
		$this->load_view('set_password_view','Create Password');
	}

	function ajax_register_user() {
		if($this->input->is_ajax_request())
		{
			$this->load->library('form_validation');

			if ($this->form_validation->run('registration-form') == FALSE)
			{
				echo json_encode($this->form_validation->error_array());
				exit;
			}

			$post = $this->input->post();

			$this->load->model('account_model');

			$user = new Register_user_entity();
			$user->first_name 	= $post['firstname'];
			$user->last_name 	= $post['lastname'];
			$user->mobile 		= $post['contact'];
			$user->landline 	= $post['landline'];
			$user->email 		= $post['email'];
			$user->password 	= $this->hash_password($post['password']);
			$user_id 			= $this->account_model->register_user($user);

			$client = new Register_client_entity();
			$client->user_id  	= $user_id;
			$client->user_type 	= $post['user_type'];

			$client_id = $this->account_model->register_client($client);

			if($user_id && $client_id)
			{
				$session_data = array(
					'user_id' 	=> $user_id,
					'client_id' => $client_id,
					'user_type' => $post['user_type'],
					'user_role' => User_role::MEMBER,
					'user_name' => toProperCase($post['firstname'] .' '. $post['lastname']),
					'email' 	=> $post['email'],
					'mobile' 	=> $post['contact']
				);
				$this->session->set_userdata($session_data);

				echo json_encode(array('status' => 'success', 'user_id' => $user_id));
			}
		}
		else
		{
			show_400();
		}
	}

	function ajax_reset_password() {
		if($this->input->is_ajax_request())
		{
			$this->load->library('form_validation');
			if ($this->form_validation->run('reset_password_form') == FALSE)
			{
				echo json_encode($this->form_validation->error_array());
				exit;
			}
			$post = $this->input->post();
			$this->load->model('verification_model');
			$new_password = $this->hash_password($post['new_password']);
			$reset_code = $post['reset_code'];

			$code_data = $this->verification_model->get_data_by_reset_code($reset_code);
			if($code_data)
			{
				//xdebug($code_data);
				if($this->verification_model->reset_password($code_data->user_id, $reset_code,$new_password))
				{
					$this->load->model('user_model');
					$login_data = $this->user_model->get_user_detail_userid($code_data->user_id);
					echo json_encode(array('status'=>'success', 'source' => $login_data->source, 'setup' => $login_data->setup_flag, 'user_id' => $login_data->user_id));
				}
			}
		}
		else
		{
			show_400();
		}
	}

	private function _send_register_mail($user) {
		$name = toProperCase($user->first_name.' '.$user->last_name);
		$data = array(
			'name' => $name
		);

		$this->load->library('email_service');
		$email_data                = new Send_email_options();
		$email_data->email_to      = $user->email;
		$email_data->email_subject = 'Shopping 13 | Welcome';
		$email_data->template      = 'register_account';
		$email_data->data          = $data;

		if($this->email_service->send_email($email_data)) {
			return TRUE;
		}
		return FALSE;
	}

	private function _send_admin_register_mail($user,$source,$city) {
		$name = toProperCase($user->first_name.' '.$user->last_name);
		$data = array(
			'name'   => $name,
			'mobile' => $user->mobile,
			'email'  => $user->email,
			'city'   => $city,
			'source' => $source,
		);

		$this->load->library('email_service');
		$email_data = new Send_email_options();
		if(ENVIRONMENT == 'developers' || ENVIRONMENT == 'development') {
			$email_data->email_to = 'bhavin@cnc.ind.in';
		}
		if(ENVIRONMENT == 'testing') {
			$email_data->email_to = '';
		}
		if(ENVIRONMENT == 'production') {
			$email_data->email_to = '';
		}
		$email_data->email_subject = 'WDP | Member Details';
		$email_data->template      = 'member_register_mail_admin';
		$email_data->data          = $data;

		if($this->email_service->send_email($email_data)) {
			return TRUE;
		}
		return FALSE;
	}
	*/

	function signout() {
		$this->session->sess_destroy();
		redirect('account','refresh');
	}

	function secondsToTime($seconds) {
		$dtF = new DateTime("@0");
		$dtT = new DateTime("@$seconds");
		return $dtF->diff($dtT)->format('%a');
	}

	private function load_view($viewname = 'login_view', $page_title) {
		$this->masterpage->setMasterPage('login_master_page');
		$this->masterpage->setPageTitle($page_title);
		$this->masterpage->addContentPage('account/'.$viewname , 'content', $this->content);
        $this->masterpage->show();
	}

}
/* end of account */