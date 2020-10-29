<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notification
{
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('notification_model');
	}
	
	function send_signup_notification($data)
	{
		$data['message_id'] = '1';
		$data['status'] 	= Notification_status::UNREAD;
		$data['created_on'] = time();
		return $this->CI->notification_model->add_new_notifications($data);
	}
	
	function send_renewal_notification($data)
	{
		$data['message_id'] = '2';
		$data['status'] 	= Notification_status::UNREAD;
		$data['created_on'] = time();
		return $this->CI->notification_model->add_new_notifications($data);
	}
	
}
/* end of notification */