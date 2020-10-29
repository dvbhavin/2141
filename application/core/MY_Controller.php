<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Uncomment following line and override display error setting if disabled in production Environment, No need to uncomment for development Environment */
//error_reporting(-1); ini_set('display_errors', 1);

date_default_timezone_set('Asia/Kolkata');

class MY_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();
		// $this->_check_migration();
		$this->content = new stdClass();
		//$this->content->money_format 		= new NumberFormatter($locale = 'en_IN', NumberFormatter::DECIMAL);

		$this->content->current_directory 	= strtolower($this->router->fetch_directory());
		$this->content->current_section 	= strtolower($this->router->fetch_class());
		$this->content->current_sub_section = strtolower($this->router->fetch_method());
		$this->content->current_url 		= str_replace(base_url(), '',current_url());
		$this->content->userdata 			= $this->session->userdata();
		$this->_get_sess_expiration_time();

		$this->_check_access();
		$this->content->pagetitle  = '';
		$this->content->breadcrumb = [];
		$enc_password = $this->hash_password('123456');

		// android push notification
		$this->topic = 'all';
		$this->key   = 'AAAAKZzc57s:APA91bHkzAftWig4j3cLoEM-oOrtx6_sM-aDbQC6WwLJTfHckQ5ZKBZxtJjiyqv1iueiU1RFWwAG1EV41PfeYUZKlHS5ibWSM7LVH0zNlSL_h-4o81EwAGmgg9v5gRW256GoGtaen9SC';
	}

	protected function set_pagetitle($title) {
		$this->content->pagetitle = $title;
	}

	protected function set_breadcrumb($arr) {
		$this->content->breadcrumb = $arr;
	}
	
	private function _check_migration() {
		$this->config->load('migration');
		if($this->config->item('migration_enabled')) {
			$this->load->library('migration');
			if($this->migration->latest()) {
				$this->national->init_queries();
			}
		}
	}

	private function _get_sess_expiration_time() {
		if($this->session->userdata('user_id')) {
			$this->config->load('config');
			$this->content->sess_expiration_time = $this->config->item('sess_expiration') * 1000;
		}
	}

	private function _check_access() {
		$logged_in_user = $this->session->userdata('user_id');
		$folder 	= $this->content->current_directory;
		$class 		= $this->content->current_section;
		$method 	= $this->content->current_sub_section;
		$access_for = $class;
		$modify_for = $class;
		if(isset($folder) && !empty($folder)) {
			$access_for = $folder.$class;
		}
		if(isset($method) && !empty($method) && $method != 'index') {
			$modify_for = $class.'/'.$method;
		}
		//echo "access for: ".$access_for;
		//echo "<br>modify for: ".$modify_for;
		$user_role  = $this->session->userdata('user_role');
		$permission = [];
		$permission = json_decode($this->national->rolewise_access($user_role));
		$is_ajax    = $this->input->is_ajax_request();

		########## Check protected pages ###########
		if(!in_array($access_for, $permission->public)){
			if(in_array($access_for, $permission->protected)){
				if(!$logged_in_user){
					if ($this->input->is_ajax_request()) {
						show_401();
					} else {
						redirect('account/login');
					}
				}
			} else {
				if(!$logged_in_user) {
					if ($this->input->is_ajax_request()) {
						show_401();
					} else {
						redirect('account/login?continue='.current_url());
					}
				} else if(!in_array($access_for, $permission->private->access))  {
					show_403();
				} else if(in_array($modify_for, $permission->private->not_modify)) {
					show_403();
				} else { 
				}
			}
		} else {
			//echo 'public access';
		}
		//xdebug($permission);
	}

	protected function hash_password($password) {
		$enc_key = $this->config->item('encryption_key');
		$options = [
		    'salt' => '_Key&123$@!#'.$enc_key.'&123$@!#_',
		];
		return @password_hash($password, PASSWORD_BCRYPT, $options);
	}

	protected function _send_reset_link($user_data,$reset_code, $template) {
		$name = toProperCase($user_data->first_name .' '.$user_data->last_name);
		$data = array(
			'name'       => $name,
			'reset_link' => base_url().'account/reset_password/'.$reset_code
		);

		$this->load->library('email_service');
		$email_data                = new Send_email_options();
		$email_data->email_to      = $user_data->email;
		$email_data->email_subject = 'Shopping 13 | Reset Password';
		if(!empty($template)) {
			$email_data->template = $template;
		}
		$email_data->data = $data;

		if($this->email_service->send_email($email_data)) {
			return TRUE;
		}
		return FALSE;
	}

	##### activity log #####
	protected function _activity_log($id, $action, $module) {
		$table    = 'activity_log';
		$activity = new Activity_entity();
		$activity->relative_id = $id;
		$activity->module      = $module;
		$activity->action      = $action;
		$activity->ip          = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'UNKNOWN';
		$activity->created_by  = $this->session->userdata('user_id');
		$activity->created_on  = time();
		
		if($this->activity_model->add_data($table, $activity)) {
			return TRUE;
		}
		return FALSE;
	}

	##### push android notification #####
	function _send_notify($title, $message, $image) {
		$this->load->library('push_andy_notify');
		/* $this->load->model(array('product_model', 'images_model'));
		
		$product = $this->product_model->get_product($productID);
		$_image  = $this->images_model->get_all_by_id($productID);
		foreach($_image as $image):
			$img      = 'product/'.$image['image'];
			$images[] = array('imageID' => $image['id'], 'image' => $img);
		endforeach; */
		$defaultImg        = base_url('upload/na-icon.png');
		$imageUrl          = (!empty($image) && isset($image) ? $image : $defaultImg);
		$actionDestination = '';
		$action            = '';
		$productData       = '';
		
		$this->push_andy_notify->setTitle($title);
		$this->push_andy_notify->setMessage($message);
		$this->push_andy_notify->setImage($imageUrl);
		$this->push_andy_notify->setAction($action);
		$this->push_andy_notify->setActionDestination($actionDestination);
		$this->push_andy_notify->setProductData($productData);
		
		$data   = $this->push_andy_notify->getNotificatin();
		$fields = array(
			'to'   => '/topics/' . $this->topic,
			'data' => $data,
		);


		// Set POST variables
		$url     = 'https://fcm.googleapis.com/fcm/send';
		$headers = array(
			'Authorization: key=' . $this->key,
			'Content-Type: application/json'
		);
		
		// Open connection
		$ch = curl_init();

		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Disabling SSL Certificate support temporarily
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

		// Execute post
		$result = curl_exec($ch);
		if($result === FALSE){
			die('Curl failed: ' . curl_error($ch));
		}

		// Close connection
		curl_close($ch);
		
		// debug(json_encode($fields, JSON_PRETTY_PRINT));
		// xdebug($result);
	}

}
/* end my controller */