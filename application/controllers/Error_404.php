<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error_404 extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		if( ! $this->input->is_cli_request())
		{
			show_404_page();
		}
		else
		{
			exit("\nERROR: 404 \t Page Not Found\n");
		}
	}
}