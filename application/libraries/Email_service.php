<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Email_service {

	public function __construct() {
		$this->CI =& get_instance();
	}

	/**
	 * send_email
	 *
	 * @param	object of Send_email_options 
	 * $email_data = new Send_email_options();
	 * @return bool
	 */
	
	public function send_email($email_data, $debug = FALSE) {
		if (empty($email_data->email_to) || empty($email_data->data) || empty($email_data->template)) {
			return FALSE;
		}

		$template_dir = 'email_templates/';
		
		$message = $this->CI->load->view($template_dir.$email_data->template, $email_data->data, TRUE);
		$this->CI->load->library('email');
		$this->CI->email->clear();
		$this->CI->email->initialize(array('mailtype' =>'html'));
		$this->CI->email->set_newline("\r\n");
		$this->CI->email->from($email_data->email_from,$email_data->email_from_name);
		$this->CI->email->to($email_data->email_to);
		if( ! empty($email_data->email_cc)) {
			$this->CI->email->cc($email_data->email_cc);
		}
		if( ! empty($email_data->email_bcc)) {
			$this->CI->email->bcc($email_data->email_bcc);
		}
		if( ! empty($email_data->reply_to)) {
			$this->CI->email->reply_to($email_data->reply_to,$email_data->reply_to_name);
		}

		$this->CI->email->subject($email_data->email_subject);
		$this->CI->email->message($message);

		if( ! empty($email_data->attachments)) {
			foreach($email_data->attachments as $file_name => $file_path) {
				$this->CI->email->attach($file_path, 'attachment', $file_name);
			}
		}
		
		if($debug) {
			debug($this->CI->email);
			@$this->CI->email->send(FALSE);
			xdebug($this->CI->email->print_debugger());
		}
		
		if ( ! @$this->CI->email->send()) {
			return FALSE;
		}

		return TRUE;
	}
}

class Send_email_options {
	public $email_from      = 'no-reply@shopping13.in';
	public $email_from_name = 'Shopping 13';
	public $email_to        = NULL;
	public $email_cc        = NULL;
	public $email_bcc       = NULL;
	public $reply_to        = NULL;
	public $reply_to_name   = NULL;
	public $email_subject   = NULL;
	public $template        = NULL;
	public $data            = NULL;
	public $attachments     = NULL;
	
	/*
	 * setOptions 
	 * set options using array 
	 * @returns object of options usable in send_email function
	 */
	
	function setOptions($options) {
		foreach($options as $key => $value) {
			$this->{$key} = $value;
		}
		return $this;
	}
}
/* end of email service */