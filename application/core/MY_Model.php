<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	public function __construct() {
		parent::__construct();
	}

	/* post data by table name & data */
	public function add_data($table, $data) {
		if($this->db->insert($table, $data)) {
			return $this->db->insert_id();
		}
	}

	/* update data by table name & data */
	public function edit_data($table, $data, $row, $id) {
		$this->db->where($row, $id);
		if($this->db->update($table, $data)) {
			return $id;
		} else {
			return false;
		}
	}

	/* get all data by table name (where condition is optional) */
	public function get_table_data($table, $where = null, $select='*') {
		$this->db->select("{$select}");
		$this->db->from("{$table}");
		if($where) {
			$this->db->where($where);
		}
		return $this->db->get()->result();
	}

	/* get single row by table name and  where condition */
	public function get_table_data_row($table, $where, $select = '*') {
		$this->db->select("{$select}");
		$this->db->from("{$table}");
		if($where) {
			$this->db->where($where);
		}
		return $this->db->get()->row();
	}

	public function get_rows_count($table) {
		return $this->db->count_all("{$table}");
	}

	public function get_result_count($table, $where = NULL) {
		$this->db->from("{$table}");
		if($where) {
			$this->db->where($where);
		}
		return $this->db->count_all_results();
	}

	public function get_lookup_values($table_name, $return_field_name, $matching_field, $field_values) {
		$this->db->select($return_field_name);
		$this->db->from($table_name);
		$this->db->where_in($matching_field, explode(",",$field_values));
		foreach($this->db->get()->result_array() as $value) {
			$data[] = $value[$return_field_name];
		}
		return $data;
	}

}