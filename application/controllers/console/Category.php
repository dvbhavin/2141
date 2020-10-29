<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->table  = 'category';
		$this->module = Module::Category;
	}

	function index() {
		$title = 'Micro App';
		$this->load_view('app_view', $title);
	}

	function add() {
		$this->load->model(array('category_model', 'media_model'));
		$title = 'Add Category';
		$this->content->breadcrumb = array(
			'Category' =>  'console/category',
			$title     =>  NULL
		);

		$this->load->library('form_validation');	
		if($this->form_validation->run('add_category')) {
			$post     = $this->input->post();
			$url_key  = $this->national->generate_url_key($post['name'], $this->table);
			$category = array();

			$category['parent_id']   = (isset($post['parent_id']) && !empty($post['parent_id']) ? $post['parent_id'] : NULL);
			$category['name']        = strtolower($post['name']);
			$category['description'] = strtolower($post['description']);
			$category['is_order']    = $post['is_order'];
			$category['url_key']     = $url_key;
			$category['created_by']  = $this->session->userdata('user_id');
			$category['created_on']  = time();
			$category_id = $this->category_model->add_data($this->table, $category);

			if(isset($category_id) && !empty($category_id)) {
				// upload media
				$uploadPath = 'upload/category/';
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
					$media['relative_id'] = $category_id;
					$media['module']      = $this->module;
				} else {
	                $data['error_msg'] = $this->upload->display_errors();
				}

				$image = '';
				if(!empty($media)) {
					$media_id = $this->media_model->add_data($table, $media);
					$image    = base_url('upload/category').'/'.$fileData['file_name'];
				}

				// add activity log
				$this->_activity_log($category_id, Action::Add, $this->module);
				redirect('console/category/view', 'refresh');
			}
		}
		$this->content->_parent = $this->category_model->get_category();
		$this->content->title   = $title;
		$this->load_view('form_view', $title);
	}

	function edit() {
		$id = $this->uri->segment(4);
		if(!$id) {
			redirect('console/category/view', 'refresh');
		}
		$this->load->model(array('category_model', 'media_model'));
		
		$title = 'Edit Category';
		$this->content->breadcrumb = array(
            'Category'    => 'console/category',
            $title        => NULL
		);

		$this->load->library('form_validation');	
		if($this->form_validation->run('edit_category')) {
            $post     = $this->input->post();
            $category = array();

            if(isset($post['name']) && isset($post['previous']) && ($post['name'] != $post['previous'])) {
				$category['url_key'] = $this->national->generate_url_key($post['name'], $this->table);
			}

			$category['category_id'] = $post['category_id'];
			$category['parent_id']   = (isset($post['parent_id']) && !empty($post['parent_id']) ? $post['parent_id'] : NULL);
			$category['name']        = strtolower($post['name']);
			$category['description'] = strtolower($post['description']);
			$category['is_order']    = $post['is_order'];
			$category['updated_by']  = $this->session->userdata('user_id');
			$category['updated_on']  = time();
            $category_id = $this->category_model->edit_data($this->table, $category, 'category_id', $post['category_id']);
			
			if(isset($category_id) && !empty($category_id)) {
				// upload media
				$uploadPath = 'upload/category/';
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
					$media['relative_id'] = $category_id;
					$media['module']      = $this->module;
				} else {
	                $data['error_msg'] = $this->upload->display_errors();
				}

				if(!empty($media)) {
					$media_id = $this->media_model->add_data($table, $media);
				}

				// add activity log
				$this->_activity_log($category_id, Action::Edit, $this->module);
				redirect('console/category/view', 'refresh');
			}
		}

		$detail = $this->category_model->details($id);
		//get media
		$media = $this->media_model->get_media($id, $this->module);
		$detail->media    = (isset($media) && !empty($media) ? $media->media : '' );
		$detail->media_id = (isset($media) && !empty($media) ? $media->media_id : '' );
		
		$this->content->title   = $title;
		$this->content->_parent = $this->category_model->get_category($id);
		$this->content->edit    = $detail;
		$this->load_view('form_view', $title);
	}

	function view() {
		$this->load->model(array('category_model', 'media_model'));
		$title  = 'View Category';
		$this->content->breadcrumb = array(
			'Category' =>  'console/category',
			$title     =>  NULL
		);
		$this->content->title = $title;
		$_list = $this->category_model->get_category();
		foreach($_list as $key => $list) :
			$media = $this->media_model->get_media($list->category_id, $this->module);
			$_list[$key]->media = (isset($media) && !empty($media) ? $media->media : '' );
		endforeach;
		$this->content->_list = $_list;
		$this->load_view('categories_view', $title);
	}

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
		$this->masterpage->addContentPage('console/category/'.$viewname , 'content', $this->content);
        $this->masterpage->show();
	}
	
}
/* end of category */