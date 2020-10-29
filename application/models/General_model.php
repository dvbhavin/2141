<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// load_entities(array('user'));

class General_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
	}
	
	public function tax_list($id = NULL) {
		$this->db->select('t.tax_id, t.name, t.percentage');
		$this->db->from('tax as t');
		$this->db->where(array('t.is_deleted' => Deleted_status::NOT_DELETED));
		if(isset($id) && !empty($id)) {
			$this->db->where(array('t.user_id' => $id));
		}
		$this->db->order_by('t.percentage', 'asc');
		return $this->db->get()->result();
	}

	public function tax_details($id) {
		$this->db->select('t.tax_id, t.name, t.percentage');
		$this->db->from('tax as t');
		$this->db->where(array('t.is_deleted' => Deleted_status::NOT_DELETED, 't.tax_id' => $id));
		return $this->db->get()->row();
	}

	public function get_count($table, $id) {
		$this->db->from($table);
		$this->db->where(array('user_id' => $id, 'is_deleted' => Deleted_status::NOT_DELETED));
		return $this->db->count_all_results();
	}

	###################### banner ######################
	public function banner($app_id, $page = NULL) {
		$this->db->select('a.advertise_id, a.product_id, a.is_page, m.media');
		$this->db->from('advertise as a');
		$this->db->join('media as m', 'm.relative_id = a.advertise_id AND m.module ='.Module::Advertise.' AND m.is_deleted ='.Deleted_status::NOT_DELETED);
		$this->db->where(array('a.app_id' => $app_id, 'a.is_deleted' => Deleted_status::NOT_DELETED));
		if(isset($page) && !empty($page)) {
			$this->db->where(array('a.is_page' => $page));
		}
		$this->db->order_by('a.is_order', 'asc');
		return $this->db->get()->result();
	}

	###################### address ######################
	public function get_address($id, $module) {
		$this->db->select('ad.address_id, ad.address, ad.city, ad.state, ad.pincode, ad.lat, ad.lng');
		$this->db->from('address as ad');
		$this->db->where(array('ad.relative_id' => $id, 'ad.module' => $module, 'ad.is_deleted' => Deleted_status::NOT_DELETED));
		return $this->db->get()->row();
	}

	/*public function customer_list($id = NULL) {
		$this->db->select('c.customer_id, c.entity, c.first_name, c.last_name, c.mobile, c.pan, c.gst, c.address, c.pin_code');
		$this->db->from('customer as c');
		$this->db->where(array('c.is_deleted' => Deleted_status::NOT_DELETED));
		if(isset($id) && !empty($id)) {
			$this->db->where(array('c.user_id' => $id));
		}
		$this->db->order_by('c.entity', 'asc');
		return $this->db->get()->result();
	}

	public function customer_details($id) {
		$this->db->select('c.customer_id, c.entity, c.first_name, c.last_name, c.mobile, c.pan, c.gst, c.address, c.pin_code');
		$this->db->from('customer as c');
		$this->db->where(array('c.is_deleted' => Deleted_status::NOT_DELETED, 'c.customer_id' => $id));
		return $this->db->get()->row();
	}*/

	###################### states ######################
	public function states_list() {
		$this->db->select('s.id, s.name');
		$this->db->from('states as s');
		$this->db->order_by('s.name', 'asc');
		return $this->db->get()->result();
	}

	###################### states ######################
	public function cities_list($id = NULL) {
		$this->db->select('c.id, c.city, c.state_id');
		$this->db->from('cities as c');
		$this->db->where(array('state_id' => $id));
		$this->db->order_by('c.city', 'asc');
		return $this->db->get()->result();
	}

}
/* end of master model */