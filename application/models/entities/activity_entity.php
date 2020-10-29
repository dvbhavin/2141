<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_entity {
	public $module;
	public $action;
	public $created_on;

	public function __construct() {
		$this->created_on = time();
	}
}

/* end activity_entity */