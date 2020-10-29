<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

load_entities(array('user'));
class Account_model extends MY_Model
{
	
	public function __construct() {
		parent::__construct();
	}

	function get_user_data_by_login_id($login_id) {
		$this->db->select('u.*');
		//$this->db->or_where('mobile',$login_id);
		$this->db->from('user_master as u');
		$this->db->where(array('u.email' => $login_id, 'u.user_deleted' => Deleted_status::NOT_DELETED));
		return $this->db->get()->row();
	}

	function update_login_data($user_id, $ipaddress)  {
		$user_data = array(
			'ip'         => $ipaddress,
			'last_login' => time()
		);
		$this->db->where('user_id', $user_id);
		if($this->db->update('user_master', $user_data)) {
			return TRUE;
		}
	}

	function change_password(Change_password_entity $user_data) {
		$this->db->where('user_id', $user_data->user_id);
		return $this->db->update('user_master', $user_data);
	}
	
	public function get_user_details($user_id, $user_role) {
		$this->db->select('u.user_id ,u.first_name ,u.last_name ,u.email ,u.mobile ,u.role ,u.user_status ,u.password ,u.role ,c.client_id');
		$this->db->from('user_master as u');
		$this->db->join('client_master as c', 'u.user_id = c.user_id','LEFT');
		$this->db->where(array('u.user_id' => $user_id, 'u.user_deleted' => Deleted_status::NOT_DELETED, 'u.role' => $user_role));
		return $this->db->get()->row();
	}

	public function get_user_profile_list($user_id) {
		$this->db->select('profile_id, user_id, contact_no, other_email');
		$this->db->from('user_profile_master');
		$this->db->where(array('user_id =' => $user_id));
		return $this->db->get()->result();
	}
	
	public function delete_user($user) {
		$this->db->where('user_id', $user['user_id']);
		return $this->db->update('user_master', $user);
	}

	public function update_user_profile_pic($user_id,$pic) {
		$this->db->set('profile_pic', $pic);
		$this->db->where('user_id',$user_id);
		if($this->db->update('user_master')) {
			return $user_id;
		}
	}

	public function remove_profile_pic($user_id) {
		$this->db->set('profile_pic',NULL);
		$this->db->where('user_id',$user_id);
		if($this->db->update('user_master')) {
			return $user_id;
		}
	}
	
}
/* end account_model */