<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * For this functions to use globally add "exception_helper" in auto load
 */
class MY_Exceptions extends CI_Exceptions
{
	public function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
	}
	
	public function show_400($heading = '' , $message = '', $reason = '', $action = '')
	{
		if(empty($heading))
		{
			$heading = 'Bad Request';
		}
		if(empty($message))
		{
			$message = "The request could not be understood by the server due to malformed syntax.";
		}
		if(empty($reason))
		{
			$reason = array(
				'The request headers might not be set properly.','Invalid method is used to request the data.'
			);
		}
		if(empty($action))
		{
			$action = array(
				'Click button below to go back home to continue to explore site.',
				'If you think its other error, please report it by simply clicking button below. While reporting mention your problem, this error message and link that caused it.'
			);
		}
		$error_info = 'The HTTP Error 400 Bad request means that server could not understand the request due to invalid syntax.';
		$this->_show_error_page($heading, $message, $reason, $action, $error_info, 400);
	}
	
	public function show_401($heading = '' , $message = '', $reason = '', $action = '')
	{
		if(empty($heading))
		{
			$heading = 'Session Expired';
		}
		if(empty($message))
		{
			$message = "The request could not be completed by the server because your session has been expired or not created yet.";
		}
		if(empty($reason))
		{
			$reason = array(
				'You have not logged in yet.','The session has been expired.'
			);
		}
		if(empty($action))
		{
			$action = array(
				'Click button below to go back home to continue to explore site.',
				'If you think its other error, please report it by simply clicking button below. While reporting mention your problem, this error message and link that caused it.'
			);
		}
		$error_info = 'The HTTP Error 401 The request has not been applied because it lacks valid authentication credentials for the target resource.';
		$this->_show_error_page($heading, $message, $reason, $action, $error_info, 401);
	}
	
	public function show_403($heading = '' , $message = '', $reason = '', $action = '')
	{
		if(empty($heading))
		{
			$heading = 'Forbidden';
		}
		if(empty($message))
		{
			$message = "You don't have permission to access requested resource on this server.";
		}
		if(empty($reason))
		{
			$reason = array(
				"You don't have access rights or permission to access requested resource.",
			);
		}
		if(empty($action))
		{
			$action = array(
				'Click button below to go back home to continue to explore site.',
				'If you think its other error, please report it by simply clicking button below. While reporting mention your problem, this error message and link that caused it.'
			);
		}
		$error_info = 'The HTTP Error 403 Forbidden means the client does not have access rights to the content, i.e. they are unauthorized, so server is rejecting to give proper response.';
		$this->_show_error_page($heading, $message, $reason, $action, $error_info, 403);
	}
	
	public function show_404_page($heading = '' , $message = '', $reason = '', $action = '')
	{
		if(empty($heading))
		{
			$heading = 'Page Not Found';
		}
		if(empty($message))
		{
			$message = 'The requested resource not found on server.';
		}
		if(empty($reason))
		{
			$reason = array(
				'The address you have entered is incorrect.',
				'The resource you are looking for might be moved, renamed or deleted and no longer available on this link.',
			);
		}
		if(empty($action))
		{
			$action = array(
				'Hit the back button to go back to previous page.',
				'Click button below to go back home & continue to explore site.',
				'If you typed the address in address bar, make sure that it is correct.',
				'If you think its other error, please report it by simply clicking button below. While reporting mention your problem, this error message and link that caused it.'
			);
		}
		$error_info = 'The HTTP Error 404 Not Found means the requested resource is not found on this server. It might have been removed, renamed or deleted.';
		$this->_show_error_page($heading, $message, $reason, $action, $error_info, 404);
	}
	
	public function show_410($heading = '' , $message = '', $reason = '', $action = '')
	{
		if(empty($heading))
		{
			$heading = 'Gone';
		}
		if(empty($message))
		{
			$message = 'The requested resource is no longer available.';
		}
		if(empty($reason))
		{
			$reason = array(
				'The link might be expired.',
				'The resource you are looking might be deleted.',
				'If you clicked on a link from an email, it may be out of date.'
			);
		}
		if(empty($action))
		{
			$action = array(
				'Click button below to go back home & continue to explore site.',
				'Check the address or try to retype the address.',
				'If you clicked on a link from an email, make sure it is not expired.',
				'If you think its other error, please report it by simply clicking button below. While reporting mention your problem, this error message and link that caused it.'
			);
		}
		$error_info = 'The HTTP Error 410 Gone means the requested resource is no longer available at the server and no forwarding address is known. The resource might be deleted or expired.';
		$this->_show_error_page($heading, $message, $reason, $action, $error_info, 410);
	}
	
	private function _show_error_page($heading, $message, $reason, $action, $error_info, $status_code = 500)
	{
		set_status_header($status_code);
		$message = '<p>'.(is_array($message) ? implode('</p><p>', $message) : $message).'</p>';
		$reason = '<li>'.(is_array($reason) ? implode('</li><li>', $reason) : $reason).'</li>';
		$action = '<li>'.(is_array($action) ? implode('</li><li>', $action) : $action).'</li>';
		$this->error_data = array('heading' => $heading, 'message' => $message,'reason' => $reason,'action' => $action,'error_info' => $error_info,'status_code' => $status_code);
		return $this->load_view($heading);
	}
	
	private function load_view($page_title="Error")
	{
		$this->CI->masterpage->setMasterPage('masterpage_view');
		$this->CI->masterpage->setPageTitle($page_title);
		$this->CI->masterpage->addContentPage('errors/custom_error_view' , 'content', $this->error_data);
        $this->CI->masterpage->show();
	}
}
/* end MY_Exceptions */