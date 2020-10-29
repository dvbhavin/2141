<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// load_entities(array('user'));

class App_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
	}
	
	public function apps() {
		$this->db->select('a.app_id, a.app_key, a.name, a.description, a.email, a.mobile, a.status');
		$this->db->from('app as a');
		$this->db->where(array('a.is_deleted' => Deleted_status::NOT_DELETED));
		return $this->db->get()->result();
	}

	public function get_details($id) {
		$this->db->select('a.app_id, a.app_key, a.name, a.description, a.email, a.mobile, a.status, m.media_id, m.media');
		$this->db->from('app as a');
		$this->db->join('media as m', 'm.relative_id = a.app_id AND m.module ='.Module::App.' AND m.is_deleted ='.Deleted_status::NOT_DELETED, 'left');
		$this->db->where(array('a.is_deleted' => Deleted_status::NOT_DELETED, 'a.app_id' => $id));
		return $this->db->get()->row();
	}

}
/* end of app model */