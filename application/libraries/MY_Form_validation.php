<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
    public function __construct($rules = array()) {
        parent::__construct($rules);
    }
	
	public function is_not_unique($str, $field) {
		sscanf($field, '%[^.].%[^.]', $table, $field);
		$this->set_message('is_not_unique', 'No record found for {field}.');
		return isset($this->CI->db) ? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() > 0) : FALSE;
	}
	
	public function valid_date($date) {
		if(preg_match("/[0-9]{2}-[0-9]{2}-[0-9]{4}/", $date)) {
			if(checkdate(substr($date, 3, 2), substr($date, 0, 2), substr($date, 6, 4))) {
				return TRUE;
			} else {
				$this->set_message('valid_date', 'Invalid {field}. It must be a valid date in the format of dd-mm-yyyy.');
				return FALSE;
			}
		} else {
			$this->set_message('valid_date', '{field} must be in format of dd-mm-yyyy.');
			return FALSE;
		}
	}
	
	public function is_in_array($str,$enum) {
		$array = array_keys($enum::getValue());
		if(in_array($str, $array)) {
			return TRUE;
		} else {
			$this->set_message('is_in_array', 'Invalid {field} value. It must be in a list of predefined values.');
			return FALSE;
		}
	}
}
/* End MY_Form_validation */