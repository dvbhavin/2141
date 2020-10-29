<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// load_entities(array('user'));

class Category_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
	}
	
	public function get_category($not_id = NULL) {
		$this->db->select('cm.category_id, cm.name, cm.description, cm.is_order, cm.url_key, pc.name as parent, m.media');
		$this->db->from('category as cm');
		$this->db->join('category as pc', 'pc.category_id = cm.parent_id', 'left');
		$this->db->join('media as m', 'm.relative_id = cm.category_id AND m.module ='.Module::Category.' AND m.is_deleted ='.Deleted_status::NOT_DELETED, 'left');
		$this->db->where(array('cm.is_deleted' => Deleted_status::NOT_DELETED));
		if(isset($not_id) && !empty($not_id)) {
			$this->db->where(array('cm.category_id !=' => $not_id));
		}
		$this->db->order_by('cm.is_order', 'asc');
		return $this->db->get()->result();
	}

	public function get_count($app) {
		$this->db->from('category');
		$this->db->where(array('is_deleted' => Deleted_status::NOT_DELETED, 'app_id' => $app));
		return $this->db->count_all_results();
	}

	public function details($id) {
		$this->db->select('cm.category_id, cm.parent_id, cm.name, cm.description, cm.is_order, cm.url_key, m.media');
		$this->db->from('category as cm');
		$this->db->join('media as m', 'm.relative_id = cm.category_id AND m.module ='.Module::Category.' AND m.is_deleted ='.Deleted_status::NOT_DELETED, 'left');
		$this->db->where(array('cm.is_deleted' => Deleted_status::NOT_DELETED, 'cm.category_id' => $id));
		return $this->db->get()->row();
	}

	public function categories($not_id = NULL) {
		$this->db->select('cm.category_id, cm.name, cm.description, cm.is_order, cm.url_key, m.media');
		$this->db->from('category as cm');
		$this->db->join('media as m', 'm.relative_id = cm.category_id AND m.module ='.Module::Category.' AND m.is_deleted ='.Deleted_status::NOT_DELETED, 'left');
		$this->db->where(array('cm.is_deleted' => Deleted_status::NOT_DELETED, 'cm.parent_id' => NULL));
		if(isset($not_id) && !empty($not_id)) {
			$this->db->where(array('cm.category_id !=' => $not_id));
		}
		$this->db->order_by('cm.is_order', 'asc');
		$this->db->group_by('cm.category_id');
		return $this->db->get()->result();
	}

	public function child_categories($parent) {
		$this->db->select('cm.category_id, cm.name, cm.description, cm.url_key, m.media');
		$this->db->from('category as cm');
		$this->db->join('media as m', 'm.relative_id = cm.category_id AND m.module ='.Module::Category.' AND m.is_deleted ='.Deleted_status::NOT_DELETED, 'left');
		$this->db->where(array('cm.is_deleted' => Deleted_status::NOT_DELETED, 'cm.parent_id' => $parent));
		$this->db->order_by('cm.is_order', 'asc');

		$child = $this->db->get();
		$_cats = $child->result();
        foreach($_cats as $key => $cat) {
            $_cats[$key]->child = $this->child_categories($cat->category_id);
        }
        return $_cats;
	}

}
/* end of category model */