<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Advertise extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->table = 'advertise';
	}

	function index() {
		$title = 'Micro App';
		$this->load_view('app_view', $title);
	}

	function add() {
		$this->load->model(array('advertise_model', 'category_model', 'media_model'));
		$title = 'Add Advertise';
		$this->content->breadcrumb = array(
			'Advertise' =>  'console/advertise',
			$title      =>  NULL
		);

		$this->load->library('form_validation');	
		if($this->form_validation->run('add_advertise')) {
			$post = $this->input->post();
			//xdebug($post);
			$is_special  = (isset($post['is_special']) ? $post['is_special'] : 0);
			$is_premium  = (isset($post['is_premium']) ? $post['is_premium'] : 0);
			$is_delivery = (isset($post['is_delivery']) ? $post['is_delivery'] : 0);

			$advertise = array();
			$advertise['user_id']     = $post['user_id'];
			$advertise['category_id'] = $post['category_id'];
			$advertise['description'] = $post['description'];
			$advertise['discount']    = $post['discount'];
			$advertise['is_special']  = $is_special;
			$advertise['is_premium']  = $is_premium;
			$advertise['is_delivery'] = $is_delivery;
			$advertise['start_on']    = strtotime($post['start_on']);
			$advertise['end_on']      = strtotime($post['end_on']);
			$advertise['created_by']  = $this->session->userdata('user_id');
			$advertise['created_on']  = time();
			$advertise_id = $this->advertise_model->add_data($this->table, $advertise);
			
			if(isset($advertise_id) && !empty($advertise_id)) {
				// upload media

				$filesCount = count($_FILES['medias']['name']);

				for($i = 0; $i < $filesCount; $i++) {
					$_FILES['media']['name']     = $_FILES['medias']['name'][$i];
					$_FILES['media']['type']     = $_FILES['medias']['type'][$i];
					$_FILES['media']['tmp_name'] = $_FILES['medias']['tmp_name'][$i];
					$_FILES['media']['error']    = $_FILES['medias']['error'][$i];
					$_FILES['media']['size']     = $_FILES['medias']['size'][$i];

					$uploadPath = 'upload/advertise/';
					if( ! is_dir($uploadPath)) {
						mkdir($uploadPath, 0755, true);
					}
					$config['upload_path'] 	 = $uploadPath;
					$config['allowed_types'] = 'jpeg|jpg|png';
					$config['max_size']      = 52428800;
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if($this->upload->do_upload('media')) {
						$fileData = $this->upload->data();
						$media[$i]['media']       = $fileData['file_name'];
						$media[$i]['relative_id'] = $advertise_id;
						$media[$i]['module']      = Module::Advertise;
					} else {
						$data['error_msg'] = $this->upload->display_errors();
					}
				}

				if(!empty($media)) {
					$this->media_model->add_media($media);
				}

				redirect('console/advertise/view', 'refresh');
			}
		}
		
		$this->content->title     = $title;
		$this->content->type      = Yes_no::getValue();
		$this->content->_category = $this->category_model->get_category();
		$this->load_view('form_view', $title);
	}

	function edit() {
		$id = $this->uri->segment(4);
		if(!$id) {
			redirect('console/product/view', 'refresh');
		}
		$this->load->model(array('category_model', 'domain_model', 'media_model', 'product_model'));
		
		$title = 'Edit Product';
		$this->content->breadcrumb = array(
			'Product' => 'console/product',
			$title    => NULL
		);

		$this->load->library('form_validation');	
		if($this->form_validation->run('edit_advertise')) {
			$post    = $this->input->post();
			$table   = 'product_master';
			$is_type = (isset($post['is_type']) && !empty($post['is_type']) ? implode(',', $post['is_type']):'');
			if(isset($post['title']) && isset($post['previous']) && ($post['title'] != $post['previous'])) {
				$product['url_key'] = $this->product_catalog->generate_url_key($post['title'], $table);
			}

			$product = array();
			$product['product_id']   = $post['product_id'];
			$product['user_id']      = $post['user_id'];
			$product['category']     = $post['category'];
			$product['title']        = strtolower($post['title']);
			$product['short_detail'] = strtolower($post['short_detail']);
			$product['description']  = $post['description'];
			$product['price']        = $post['price'];
			$product['is_type']      = $is_type;
			$product['status']       = $post['status'];
			$product['updated_by']   = $this->session->userdata('user_id');
			$product['updated_on']   = time();
			$product_id = $this->product_model->edit_data($table, $product, 'product_id', $post['product_id']);
			
			if(isset($product_id) && !empty($product_id)) {
				// upload media
				if(isset($_FILES['medias']['name']) && !empty($_FILES['medias']['name'])) {

					$filesCount = count($_FILES['medias']['name']);

					for($i = 0; $i < $filesCount; $i++) {
						$_FILES['media']['name']     = $_FILES['medias']['name'][$i];
						$_FILES['media']['type']     = $_FILES['medias']['type'][$i];
						$_FILES['media']['tmp_name'] = $_FILES['medias']['tmp_name'][$i];
						$_FILES['media']['error']    = $_FILES['medias']['error'][$i];
						$_FILES['media']['size']     = $_FILES['medias']['size'][$i];

						$uploadPath = 'assets/media/product/';
						if( ! is_dir($uploadPath)) {
							mkdir($uploadPath, 0755, true);
						}
						$config['upload_path'] 	 = $uploadPath;
						$config['allowed_types'] = 'jpeg|jpg|png';
						$config['max_size']      = 52428800;
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						if($this->upload->do_upload('media')) {
							$fileData = $this->upload->data();
							$media[$i]['media']       = $fileData['file_name'];
							$media[$i]['relative_id'] = $product_id;
							$media[$i]['module']      = Module::Product;
						} else {
							$data['error_msg'] = $this->upload->display_errors();
						}
					}

					if(!empty($media)) {
						$this->media_model->add_media($media);
					}
				}

				// add activity log
				$this->_activity_log($product_id, Action::Edit);
				redirect('console/product/view', 'refresh');
			}
		}

		$detail = $this->product_model->get_details($id);
		// get media
		$media = $this->media_model->get_media($detail->product_id, Module::Product);
		$detail->media = (isset($media) && !empty($media) ? $media->media : '' );
		
		$this->content->title     = $title;
    //  $this->content->_category = $this->category_model->get_category();
		$this->content->_category = $this->category_model->categories_by_domain($detail->user_id, $id);
		$this->content->_domain   = $this->domain_model->get_domain_list();
		$this->content->status    = Status::getValue();
		$this->content->type      = Product_type::getValue();
		$this->content->edit      = $detail;
        //xdebug($detail);
		$this->load_view('form_view', 'Edit Product');
	}

	function view() {
		$title = 'View Advertise';
		$this->content->breadcrumb = array(
			'Advertise' =>  'console/advertise',
			$title      =>  NULL
		);
		$this->load->model(array('advertise_model', 'media_model'));
		$this->content->title = $title;

		$_list = $this->advertise_model->advertise();
		/*foreach($_list as $key => $list) :
			$media = $this->media_model->get_media($list->advertise_id, Module::Advertise);
			$_list[$key]->media = (isset($media) && !empty($media) ? $media->media : '' );
		endforeach;*/
		$this->content->_list = $_list;
		$this->load_view('advertise_view', $title);
	}

	function details() {
		$product_id = $this->uri->segment(4);
		if(!$product_id) {
			redirect('console/product/view', 'refresh');
		}
		$this->load->model(array('media_model', 'product_model'));
		$product = $this->product_model->get_details($product_id);
		$title   = toPropercase($product->title);

		$this->content->breadcrumb = array(
			'Product'     =>  'console/product',
			'View Domain' =>  'console/product/view',
			'Products'    =>  'console/product/domain_products/'.$product->user_id,
			$title        =>  NULL
		);

		$this->content->title   = $title;
		$this->content->_media  = $this->media_model->get_media($product->product_id, Module::Product, 'multi');
		$this->content->product = $product;
		//xdebug($product);
		$this->load_view('details_view', $title);
	}

	function delete() {
		$id    = $this->input->post('id');
		$row   = $this->input->post('row');
		$table = $this->input->post('table');
		if(!$id && !$row && !$table) {
			redirect('console/advertise/view', 'refresh');
		}
		$this->load->model(array('advertise_model'));
		$data = array();

		$data[$row]         = $id;
		$data['updated_by'] = $this->session->userdata['user_id'];
		$data['updated_on'] = time();
		$data['is_deleted'] = Deleted_status::DELETED;

		$affected_id = $this->advertise_model->edit_data($table, $data, $row, $id);

		if(isset($affected_id) && !empty($affected_id)) {
			echo 'success';
		}
	}
	
	private function load_view($viewname = 'app_view', $page_title) {
		$this->masterpage->setMasterPage('master_page');
		$this->masterpage->setPageTitle($page_title);
		$this->masterpage->addContentPage('console/advertise/'.$viewname , 'content', $this->content);
		$this->masterpage->show();
	}
	
}
/* end of advertise */