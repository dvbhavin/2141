<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_entity {
	public $first_name;
	public $last_name;
	public $mobile;
	public $role         = User_role::MEMBER;
	public $user_status  = User_status::INACTIVE;
	public $user_deleted = Deleted_status::NOT_DELETED;
	public $created_by   = NULL;
	public $created_on;

	public function __construct() {
		$this->created_on = time();
	}
}

class User_add_entity extends User_entity {
	public $user_key = '';
	public $email;
	public $password = '';
	public $created_by = NULL;
	public $created_on;

	public function __construct() {
		parent::__construct();
		$this->created_on = time();
	}
}

class Change_password_entity {
	public $user_id;
	public $password;
	public $updated_by;
	public $updated_on;

	public function __construct() {
		$this->updated_on = time();
	}
}