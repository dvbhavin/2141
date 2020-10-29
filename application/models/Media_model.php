<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Media_model extends MY_Model
{
	public function __construct() {
		parent::__construct();
	}

	public function add_media($media) {
		if($this->db->insert_batch('media', $media)) {
			return $this->db->insert_id();
		}
	}

	public function delete($media) {
		$this->db->where('media_id', $media['media_id']);
		return $this->db->update('media', $media);
	}

	public function get_media($relative, $module, $ar_type = NULL) {
		$this->db->select('media_id, media');
		$this->db->from('media');
		$this->db->where(array('relative_id' => $relative, 'module' => $module, 'is_deleted' => Deleted_status::NOT_DELETED));
		if(isset($ar_type) && !empty($ar_type) && $ar_type == 'multi') {
			return $this->db->get()->result();
		} else {
			return $this->db->get()->row();
		}
	}

	public function get_media_by_id($media_id) {
		$this->db->select('media_id, media');
		$this->db->from('media');
		$this->db->where(array('media_id' => $media_id, 'is_deleted' => Deleted_status::NOT_DELETED));
		return $this->db->get()->row();
	}

}
/* end media model */