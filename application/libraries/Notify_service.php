<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notify_service
{
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	/*
	* Notify Options  
	*/
	
	public $icon = NULL;
	public $icon_size = 'large';
	public $title = NULL;
	public $message = '';
	public $url = NULL;
	public $target = '_blank';
	public $type = 'success';
	public $allow_dismiss = TRUE;
	public $delay = 0;
	
	
	/* 
	* clear previous values
	*/
	
	public function clear()
	{
		$this->icon = NULL;
		$this->title = NULL;
		$this->message = '';
		$this->url = NULL;
		$this->target = '_blank';
		$this->type = 'success';
		$this->allow_dismiss = TRUE;
		$this->delay = 0;
		return $this;
	}
	
	
	/*
	* set_options 
	* @param array $options
	* set options using array 
	* @returns object of options used in notify function
	*/
	
	function set_options($options)
	{
		$this->clear();
		foreach($options as $key => $value)
		{
			$this->{$key} = $value;
		}
		return $this;
	}
	
	/**
	* notify
	*
	* @param	object 	$options object of Notify_options 
	* 					$options = new Notify_options();
	* @param	bool	$return	Whether to return the view output
	*					or leave it to pass data as flashdata for next request
	*/
	
	public function notify($options,$return = FALSE)
	{
		$notify = array(
			'message' => $options->message,
			'icon' => $options->icon,
			'title' => $options->title,
			'url' => $options->url,
			'target' => $options->target,
			'type' => $options->type,
			'allow_dismiss' => $options->allow_dismiss,
			'delay' => $options->delay,
		);
		if($return)
		{
			return $notify;
		}
		else
		{
			$this->CI->notifies[] = $notify;
			$this->CI->session->set_flashdata('notifies',$this->CI->notifies);
		}
	}
	
	/**
	* notify_success
	* 
	* Quick functions for success notification
	*/
	public function notify_success($message,$return = FALSE)
	{
		$options = $this->set_options(array('message' => $message,
			'icon' => 'done',
			'title' => '<b>Success !!</b>',
			'type' => 'success')
		);
		return $this->notify($options,$return);
	}
	
	/**
	* notify_error
	* 
	* Quick functions for error notification
	*/
	public function notify_error($message,$return = FALSE)
	{
		$options = $this->set_options(array('message' => $message,
			'icon' => 'error',
			'title' => '<b>Error!</b>',
			'type' => 'danger')
		);
		return $this->notify($options,$return);
	}
	
	/**
	* notify_warning
	* 
	* Quick functions for warning notification
	*/
	public function notify_warning($message,$return = FALSE)
	{
		$options = $this->set_options(array('message' => $message,
			'icon' => 'warning',
			'title' => '<b>Warning!</b>',
			'type' => 'warning')
		);
		return $this->notify($options,$return);
	}
	
	/**
	* notify_info
	* 
	* Quick functions for info notification
	*/
	 
	public function notify_info($message,$return = FALSE)
	{
		$options = $this->set_options(array('message' => $message,
			'icon' => 'info',
			'title' => '<b>Information</b>',
			'type' => 'info')
		);
		return $this->notify($options,$return);
	}
}
/* end of notify service */