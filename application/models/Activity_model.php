<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

load_entities(array('activity'));

class Activity_model extends MY_Model {
	public function __construct() {
		parent::__construct();
	}
	
	public function add(activity_entity $activity) {
		if($this->db->insert('activity_log', $activity)) {
			return $this->db->insert_id();
		}
	}
	
}
/* end of activity model */