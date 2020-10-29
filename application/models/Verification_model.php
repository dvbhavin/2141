<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verification_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
	}

	/* mobile verification */
	function add_otp($user_id, $otp) {
		return $this->_set_code(Verification_code_type::MOBILE, $user_id, $otp);
	}

	function get_otp($code_id) {
		return $this->_get_code_data(Verification_code_type::MOBILE, $code_id);
	}

	function verify_otp($user_id, $otp) {
		if($this->_verify_code(Verification_code_type::MOBILE, $user_id, $otp)) {
			if($this->_verify_mobile($user_id)) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/* email verification */
	/*function add_email_verification_code($user_id, $verification_code)
	{
		return $this->_set_code(Verification_code_type::EMAIL, $user_id, $verification_code);
	}

	function get_data_by_verification_code($verification_code)
	{
		return $this->_get_data_by_code(Verification_code_type::EMAIL, $verification_code);
	}

	function get_verification_code_by_user($user_id)
	{
		return $this->_get_code_data_by_user(Verification_code_type::EMAIL, $user_id);
	}

	function verify_email($user_id, $verification_code)
	{
		if($this->_verify_code(Verification_code_type::EMAIL, $user_id, $verification_code))
		{
			if($this->_verify_user_email($user_id))
			{
				return TRUE;
			}
		}
		return FALSE;
	}*/

	/* Reset code functions */
	/*function add_password_reset_code($user_id, $reset_code)
	{
		return $this->_set_code(Verification_code_type::PASSWORD, $user_id, $reset_code);
	}

	function get_data_by_reset_code($reset_code)
	{
		return $this->_get_data_by_code(Verification_code_type::PASSWORD, $reset_code);
	}

	function reset_password($user_id, $reset_code, $new_password)
	{
		if($this->_verify_code(Verification_code_type::PASSWORD, $user_id, $reset_code))
		{
			if($this->_reset_password($user_id, $new_password))
			{
				return TRUE;
			}
		}
		return FALSE;
	}*/

	private function _set_code($type, $user_id, $code) {
		$data = array(
			'user_id'    => $user_id,
			'type'       => $type,
			'code'       => $code,
			'created_on' => time(),
			'status'     => Verification_code_status::ACTIVE
		);

		if($this->db->insert('verification_code_master', $data)) {
			return $this->db->insert_id();
		}
	}

	private function _get_code_data($type, $code_id) {
		$where = array(
			'code_id' => $code_id,
			'type'    => $type,
			'status'  => Verification_code_status::ACTIVE
		);
		$row = $this->db->get_where('verification_code_master', $where)->row();
		if($row) {
			return $row;
		} else {
			return FALSE;
		}
	}

	private function _get_code_data_by_user($type, $user_id)
	{
		$where = array(
			'user_id' => $user_id,
			'type' => $type,
			'status' => Verification_code_status::ACTIVE
		);
		$row = $this->db->get_where('verification_code_master', $where)->row();
		if($row)
		{
			return $row;
		}
		else
		{
			return FALSE;
		}
	}

	private function _get_data_by_code($type, $code) {
		$where = array(
			'code' => $code,
			'type' => $type,
			'status' => Verification_code_status::ACTIVE
		);
		$row = $this->db->get_where('verification_code_master', $where)->row();
		if($row) {
			return $row;
		} else {
			return FALSE;
		}
	}

	private function _verify_code($type, $user_id, $code) {
		$data = array(
			'status' => Verification_code_status::VERIFIED
		);
		$where = array(
			'user_id' => $user_id,
			'type'    => $type,
			'code'    => $code,
			'status'  => Verification_code_status::ACTIVE
		);

		$row = $this->db->get_where('verification_code_master', $where)->row();
		if($row) {
			$this->db->where($where);
			return $this->db->update('verification_code_master', $data);
		} else {
			return FALSE;
		}
	}

	private function _deactivate_code($type, $user_id, $code) {
		$data = array(
			'status' => Verification_code_status::DEACTIVATED
		);
		$where = array(
			'user_id' => $user_id,
			'type'    => $type,
			'status'  => Verification_code_status::ACTIVE
		);
		$this->db->where($where);
		$this->db->update('verification_code_master', $data);
	}

	private function _verify_mobile($user_id) {
		$data = array(
			'mobile_verified' => Verified::VERIFIED
		);
		$this->db->where('user_id', $user_id);
		return $this->db->update('user_master', $data);
	}

	private function _verify_user_email($user_id)
	{
		$data = array(
			'email_verified' => Verified::VERIFIED
		);
		$this->db->where('user_id',$user_id);
		return $this->db->update('user_master', $data);
	}

	private function _reset_password($user_id, $new_password) {
		$data = array(
			'password' => $new_password
		);
		$this->db->where('user_id',$user_id);
		return $this->db->update('user_master', $data);
	}

	public function first_change_password($user_id, $new_password) {
		$data = array(
			'password'   => $new_password,
			'last_login' => time()
		);
		$this->db->where('user_id',$user_id);
		return $this->db->update('user_master', $data);
	}
}
/* end verification model */