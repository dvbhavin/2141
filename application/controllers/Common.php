<?php defined('BASEPATH') OR exit('No direct script access allowed');

###### This controller contains some common Ajax functions required globally ######
class Common extends MY_Controller {
	function __construct() {
		parent::__construct();
		if( ! $this->input->is_ajax_request()) {
			show_400();
			exit;
		}
	}

	function index($action) {
		if(empty($action)) {
			redirect('/');
		}
	}

	function ajax_check_user_exist($logged_in_status = "not_logged_in") {
		$post = $this->input->post();
		if(isset($post['email'])) {
			$where = array('email' => $post['email'], 'user_deleted' => Deleted_status::NOT_DELETED);
		}
		if(isset($post['mobile'])) {
			$where = array('mobile' => $post['mobile'], 'user_deleted' => Deleted_status::NOT_DELETED);
		}
		if($logged_in_status == "logged_in") {
			$userdata = $this->session->userdata();
			if($userdata) {
				$where['user_id !='] = $userdata['user_id'];
			}
		}
		
		$this->load->model('user_model');
		$user_data = $this->user_model->get_table_data_row('user_master',$where);
		if( ! $user_data) {
			echo json_encode(array('user_exist' => FALSE));
		} else {
			echo json_encode(array('user_exist' => TRUE, 'user_role' => $user_data->role, 'email_verified' => $user_data->email_verified, 'mobile_verified' => $user_data->mobile_verified,  'status' => $user_data->user_status));
		}
	}

	function ajax_check_app_exist() {
		$post = $this->input->post();
		if(isset($post['name'])) {
			$where = array('name' => $post['name'], 'is_deleted' => Deleted_status::NOT_DELETED);
		}
		
		$this->load->model('user_model');
		$app_data = $this->user_model->get_table_data_row('app', $where);
		if( ! $app_data) {
			echo json_encode(array('app_exist' => FALSE));
		} else {
			echo json_encode(array('app_exist' => TRUE));
		}
	}

}
/* end Common */