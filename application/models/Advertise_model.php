<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advertise_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
	}
	
	public function advertise($cats = NULL) {
		$this->db->select('ad.advertise_id, ad.description, ad.discount, ad.is_special, ad.is_premium, ad.is_delivery, cm.category_id, cm.name as category_name, up.user_id, u.mobile, up.shop_name, add.address_id, add.address, add.location, add.city, add.state, add.pincode, add.lat, add.lng, ad.banner, ad.short_video, m.media as cat_image');
		$this->db->from('advertise as ad');
		$this->db->join('category as cm', 'cm.category_id = ad.category_id', 'left');
		$this->db->join('user_profile as up', 'up.user_id = ad.user_id', 'left');
		$this->db->join('user_master as u', 'u.user_id = ad.user_id', 'left');
		$this->db->join('address as add', 'add.relative_id = ad.user_id', 'left');
		$this->db->join('media as m', 'm.relative_id = ad.category_id AND m.module ='.Module::Category.' AND m.is_deleted ='.Deleted_status::NOT_DELETED, 'left');
		$this->db->where(array('ad.is_deleted' => Deleted_status::NOT_DELETED));
		if(isset($cats) && !empty($cats)) {
			$this->db->where(array('ad.category_id' => $cats));
		}
		$this->db->order_by('ad.advertise_id', 'desc');
		$this->db->group_by('ad.advertise_id');
		return $this->db->get()->result();
	}

}
/* end of advertise model */