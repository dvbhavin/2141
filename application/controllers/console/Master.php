<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->table   = 'category';
		$this->module  = Module::Category;
		$this->user_id = $this->session->userdata('user_id');
	}

	function index() {
		$title = 'Micro App';
		$this->load_view('app_view', $title);
	}

	// start tax
	function add_tax() {
		$title = 'Add Tax';
		$this->content->breadcrumb = array(
			'Master' =>  'console/master',
			'Tax'    =>  'console/master/tax',
			$title   =>  NULL
		);
		$this->content->title = $title;
		$this->load_view('tax_form_view', $title);
	}

	function edit_tax() {
		$id = $this->uri->segment(4);
		if(!$id) {
			redirect('console/master/tax_view', 'refresh');
		}
		$this->load->model(array('general_model'));
		$title = 'Edit Tax';
		$tax   = $this->general_model->tax_details($id);
		$this->content->breadcrumb = array(
			'master'   =>  'console/master',
			$tax->name =>  NULL,
			$title     =>  NULL
		);
		$this->content->title = $title;
		$this->content->edit  = $tax;
		$this->load_view('tax_form_view', $title);
	}

	function ajaxAddTax() {
		if($this->input->is_ajax_request()) {
			if($post = $this->input->post()) {
				$this->load->model(array('general_model'));
				if(!empty($post['tax_id']) && isset($post['tax_id'])) {
                    $table = 'tax';
                    $tax   = array();
                    $tax['tax_id']     = $post['tax_id'];
                    $tax['name']       = strtolower($post['name']);
                    $tax['percentage'] = $post['percentage'];
                    $tax['updated_by'] = $this->user_id;
                    $tax['updated_on'] = time();
                    $tax_id = $this->general_model->edit_data($table, $tax, 'tax_id', $post['tax_id']);

					if(isset($tax_id) && !empty($tax_id)) {
						// add activity log
						$this->_activity_log($tax_id, Action::Edit, Module::Tax);

						echo json_encode(array('status' => 'success', 'message' => 'Tax updated successfully.'));
					}
				} else {
					// add tax data
                    $table = 'tax';
                    $tax   = array();
                    $tax['name']       = strtolower($post['name']);
                    $tax['percentage'] = $post['percentage'];
                    $tax['created_by'] = $this->user_id;
                    $tax['created_on'] = time();
                    $tax_id            = $this->general_model->add_data($table, $tax);

					if(isset($tax_id) && !empty($tax_id)) {
						// add activity log
						$this->_activity_log($tax_id, Action::Add, Module::Tax);

						echo json_encode(array('status' => 'success', 'message' => 'Tax added successfully.'));
					}
				}
			}
		} else {
			show_400();
		}
	}

	function tax() {
		$title = 'View Tax';
		$this->content->breadcrumb = array(
			'Master' =>  'console/master',
			$title   =>  NULL
		);
		$this->load->model(array('general_model'));
		$this->content->title = $title;
		$this->content->_list = $this->general_model->tax_list();
		$this->content->action= base_url('console/master/add_tax');
		$this->load_view('tax_list_view', $title);
	}
	// end tax

	function delete() {
		$id    = $this->input->post('id');
		$row   = $this->input->post('row');
		$table = $this->input->post('table');
		if(!$id && !$table && !$column) {
			redirect('console/category', 'refresh');
		}
		$this->load->model(array('category_model'));
        
        $data = array();
		$data[$row]         = $id;
		$data['updated_by'] = $this->session->userdata['user_id'];
		$data['updated_on'] = time();
		$data['is_deleted'] = Deleted_status::DELETED;
        $affected_id = $this->category_model->edit_data($table, $data, $row, $id);

		if(isset($affected_id) && !empty($affected_id)) {
			// add activity log
			$this->_activity_log($affected_id, Action::Delete, $this->module);

			echo 'success';
		}
	}

	private function load_view($viewname = 'app_view', $page_title) {
		$this->masterpage->setMasterPage('master_page');
		$this->masterpage->setPageTitle($page_title);
		$this->masterpage->addContentPage('console/master/'.$viewname , 'content', $this->content);
        $this->masterpage->show();
	}
	
}
/* end of master */