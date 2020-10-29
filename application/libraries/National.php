<?php defined('BASEPATH') OR exit('No direct script access allowed');

class National
{
	public function __construct()  {
		$this->CI =& get_instance();
	}

	public function init_queries() {
		/* Add super admin details */
		if($this->CI->db->count_all('user_master') == 0) {
			$user = array(
				'role'        => User_role::SUPER_ADMIN,
				'first_name'  => 'Super',
				'last_name'   => 'Admin',
				'mobile'      => '0987654321',
				'email'       => 'admin@w.com',
				'password'    => '$2y$10$X0tleSYxMjMkQCEjQHBybuOHIVYM8KghOYmjQZMWKnCGu.0NNe9/.',
				'user_status' => User_status::ACTIVE,
				'created_on'  => time(),
				'updated_on'  => time()
			);
			$this->CI->db->insert('user_master', $user);
		}
	}

	public function loggedin_user() {
		return ($this->CI->session->userdata('user_name') != '') ? $this->CI->session->userdata('user_name') : '';
	}

	public function rolewise_access($user_role) {
		$permission = [];

		$permission['public'] 	= ['common', 'account', 'webservices'];
		$permission['protected']= ['dashboard', 'profile'];

		if(isset($user_role) || !empty($user_role)) {
			$permission['private'] = [];
			if($user_role == User_role::SUPER_ADMIN)  {
				$permission['private']['access']     = ['console/dashboard', 'console/advertise', 'console/category', 'console/product', 'console/user', 'console/master'];
				$permission['private']['not_modify'] = [];
			} else if($user_role == User_role::MEMBER) {
				$permission['private']['access']     = [];
				$permission['private']['not_modify'] = [];
			}

			if(empty($user_role) || (isset($permission['private']) && count($permission['private']) == 0)) {
				show_410();
			}
		}
		return json_encode($permission);
	}

	public function generate_url_key($name, $table = 'user_master', $column_name = 'url_key', $index = 0) {
		$key = url_title($name, '-', TRUE);
		if($index >0) {
			$key = $key.$index;
		}
	
		$this->CI->db->where($column_name, $key);
		$this->CI->db->from($table);
		$cnt = $this->CI->db->count_all_results();
	
		if($cnt > 0) {
			$index++;
			return $this->generate_url_key($name, $table, $column_name, $index);
		}
		return $key;
	}

}