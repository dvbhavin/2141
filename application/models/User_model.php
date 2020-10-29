<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

load_entities(array('user'));

class User_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
	}
	
	public function add_user(User_entity $data) {
		if($this->db->insert('user_master', $data)) {
			return $this->db->insert_id();
		}
	}

	public function user_exist($mobile, $condition = NULL) {
		$this->db->select('u.user_id, u.email, u.mobile, u.password, u.user_status, u.role');
		$this->db->from('user_master as u');
		$this->db->join('user_profile as up', 'up.user_id = u.user_id', 'left');
		if(isset($condition) && !empty($condition)) {
			// $this->db->where(array('u.password' => '', 'u.user_deleted' => Deleted_status::NOT_DELETED, 'u.role' => User_role::MEMBER, 'u.mobile' => $mobile));
			$this->db->where(array('u.password' => '', 'u.user_deleted' => Deleted_status::NOT_DELETED, 'u.mobile' => $mobile));
			$this->db->or_where(array('u.mobile_verified' => Verified::NOT_VERIFIED));
		} else {
			// $this->db->where(array('u.user_deleted' => Deleted_status::NOT_DELETED, 'u.role' => User_role::SYSTEM_USER, 'u.mobile' => $mobile));
			$this->db->where(array('u.user_deleted' => Deleted_status::NOT_DELETED, 'u.mobile' => $mobile));
		}
		return $this->db->get('user_master')->row();
	}
	
	/*public function update($table, $val, $row, $id) {
		$this->db->where($row, $id);
		return $this->db->update($table, $val);
	}

	public function check_user_exists($mobile) {
		$this->db->where('mobile', $mobile);
		$this->db->where('user_status', User_status::ACTIVE);
		$this->db->where('user_deleted', Deleted_status::NOT_DELETED);
		return $this->db->get('user_master')->row();
	}

	public function check_user_exists_email($email) {
		$this->db->where('email', $email);
		$this->db->where('user_status', User_status::ACTIVE);
		$this->db->where('user_deleted', Deleted_status::NOT_DELETED);
		return $this->db->get('user_master')->row();
	}*/

	function update_password($user_id, $password) {
		$this->db->where('user_id',$user_id);
		$query = $this->db->update('user_master', array('password'=> $password));
		if($query) {
			return true;
		}
		return false;
	}

	public function users_by_role($role = NULL) {
		$this->db->select('u.user_id, u.first_name, u.last_name, u.email, u.email_verified, u.mobile, u.mobile_verified, u.role, up.shop_name, up.age, up.gst_no, up.pan_no');
		$this->db->from('user_master as u');
		$this->db->join('user_profile as up', 'up.user_id = u.user_id', 'left');
		$this->db->where(array('u.user_deleted' => Deleted_status::NOT_DELETED));
		if(isset($role) && !empty($role)) {
			$this->db->where('u.role', $role);	
		}
		return $this->db->get()->result();
	}

	public function user_detail($user_id) {
		$this->db->select('u.user_id, u.first_name, u.last_name, u.email, u.email_verified, u.mobile, u.mobile_verified, u.role, up.shop_name, up.age, up.gst_no, up.pan_no');
		$this->db->from('user_master as u');
		$this->db->join('user_profile as up', 'up.user_id = u.user_id', 'left');
		$this->db->where(array('u.user_id' => $user_id, 'u.user_deleted' => Deleted_status::NOT_DELETED));
		return $this->db->get()->row();
	}

}
/* end of user model */