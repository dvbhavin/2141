<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->table  = 'app';
		$this->module = Module::User;
	}

	function index() {
		$title = 'Micro App';
		$this->load->model(array('app_model'));
		$this->load_view('user_view', $title);
	}

	function add() {
		$users = $this->uri->segment(4);
		if(! $users) {
			redirect('console/user/view', 'refresh');
		}
		$title = 'Add Member';
		$this->content->breadcrumb = array(
			'User'  =>  'console/user',
			$title =>  NULL
		);

		$this->load->model(array('user_model', 'media_model'));
		$this->load->library('form_validation');	
		if($this->form_validation->run('add_member')) {
			$post  = $this->input->post();
			
			$table = 'user_master';
			$user  = array();
			$user['first_name']  = strtolower($post['first_name']);
			$user['last_name']   = strtolower($post['last_name']);
			$user['email']       = strtolower($post['email']);
			$user['password']    = $this->hash_password($post['password']);
			$user['mobile']      = $post['mobile'];
			$user['user_status'] = User_status::INACTIVE;
			$user['role']        = $users;
			$user['created_on']  = time();
			$user_id = $this->user_model->add_data($table, $user);

			if($user_id) {
                // add user profile
				$table   = 'user_profile';
				$profile = array();
				$profile['user_id'] = $user_id;
				$profile['age']     = ($post['age'] ? $post['age'] : NULL);
				$profile_id = $this->user_model->add_data($table, $profile);

                // add address
				if(isset($post['pincode']) && !empty($post['pincode'])) {
					$table   = 'address';
					$address = array();
					$address['relative_id'] = $user_id;
					$address['address']     = strtolower($post['address']);
					$address['city']        = strtolower($post['city']);
					$address['state']       = strtolower($post['state']);
					$address['pincode']     = $post['pincode'];
					$address['module']      = Module::User;
					$address['created_by']  = $user_id;
					$address['created_on']  = time();
					$address_id = $this->user_model->add_data($table, $address);
				}
			}
			
			if(isset($user_id) && !empty($profile_id)) {
				redirect('console/user/view/'.$users, 'refresh');
			}
		}

		$this->content->title  = $title;
		$this->content->status = Status::getValue();
		$this->load_view('member_form_view', $title);
	}

	/*function edit() {
		$app_id = $this->uri->segment(4);
		if(! $app_id) {
			redirect('console/app/view', 'refresh');
		}
		$this->load->model(array('app_model', 'media_model'));
		$title = 'Edit App';
		$app   = $this->app_model->get_details($app_id);
		$this->content->breadcrumb = array(
			'App'      => 'console/app',
			$title     => NULL,
			$app->name => NULL
		);

		$this->load->library('form_validation');	
		if($this->form_validation->run('edit_app')) {
			$post  = $this->input->post();
			$app   = array();

			$app['app_id']      = $post['app_id'];
			$app['name']        = strtolower($post['name']);
			$app['description'] = strtolower($post['description']);
			$app['email']       = strtolower($post['email']);
			$app['mobile']      = $post['mobile'];
			$app['status']      = $post['status'];
			$app['updated_by']  = $this->session->userdata('user_id');
			$app['updated_on']  = time();
			$app_id = $this->app_model->edit_data($this->table, $app, 'app_id', $post['app_id']);

			if(isset($app_id) && !empty($app_id)) {
				// upload media
				$uploadPath = 'upload/app/';
				if( ! is_dir($uploadPath)) {
					mkdir($uploadPath, 0755, true);
				}
				$config['upload_path'] 	 = $uploadPath;
				$config['allowed_types'] = 'jpeg|jpg|png';
				$config['max_size']      = 2097152;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if($this->upload->do_upload('media')) {	
					$fileData = $this->upload->data();
					$table    = 'media';
					$media['media']       = $fileData['file_name'];
					$media['relative_id'] = $app_id;
					$media['module']      = $this->module;
				} else {
	                $data['error_msg'] = $this->upload->display_errors();
				}

				if(!empty($media)) {
					$media_id = $this->media_model->add_data($table, $media);
				}

				// add activity log
				$this->_activity_log($app_id, Action::Edit, $this->module);
				redirect('console/app/view', 'refresh');
			}
		}

		$this->content->title  = $title;
		$this->content->status = Status::getValue();
		$this->content->edit   = $app;
		$this->load_view('form_view', $title);
	}*/

	function view() {
		$user = $this->uri->segment(4);
		if(! $user) {
			redirect('console/user/view', 'refresh');
		}

		$title = 'View User';
		$this->content->breadcrumb = array(
			'User' =>  'console/user',
			$title =>  NULL
		);
		$this->load->model(array('user_model'));
		$this->content->title = $title;
		$this->content->_list = $this->user_model->users_by_role($user);
		$this->load_view('list_view', $title);
	}

	function detail() {
		$user_id = $this->uri->segment(4);
		if(! $user_id) {
			redirect('console/user/view', 'refresh');
		}

		$this->load->model(array('user_model'));
		$detail = $this->user_model->user_detail($user_id);
		$title  = $detail->shop_name;
		$this->content->breadcrumb = array(
			'User'      =>  'console/user',
			'View User' =>  'console/user/view',
			$title      =>  NULL
		);

		$this->content->detail = $detail;
		$this->load_view('detail_view', $title);
	}

	function delete() {
		$id    = $this->input->post('id');
		$row   = $this->input->post('row');
		$table = $this->input->post('table');
	//	$file  = $this->input->post('file');
		if(!$id && !$table && !$column) {
			redirect('console/app', 'refresh');
		}
		$this->load->model(array('user_model'));

		$data = array();
		$data[$row]         = $id;
		$data['updated_by'] = $this->session->userdata['user_id'];
		$data['updated_on'] = time();
		$data['user_deleted'] = Deleted_status::DELETED;
		$affected_id = $this->user_model->edit_data($table, $data, $row, $id);

		if(isset($affected_id) && !empty($affected_id)) {

			/*if(isset($table) && !empty($table) && isset($file) && !empty($file)) {
				@unlink($file);
			}*/	
			echo 'success';
		}
	}

	private function load_view($viewname = 'user_view', $page_title) {
		$this->masterpage->setMasterPage('master_page');
		$this->masterpage->setPageTitle($page_title);
		$this->masterpage->addContentPage('console/user/'.$viewname , 'content', $this->content);
		$this->masterpage->show();
	}
	
}
/* end of user */