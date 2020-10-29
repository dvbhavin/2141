<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

##### Returns url to asset directory #####
function asset_url() {
   return base_url().'assets/';
}

##### Returns url to upload directory #####
function upload_dir() {
   return base_url().'uploads/';
}

function mqr() {
   return base_url().'upload/qr_code/';
}

function mcat() {
   return base_url().'upload/category/';
}

function mad() {
   return base_url().'upload/advertise/';
}

##### Returns url to upload directory #####
function upload_path() {
   return "./uploads/";
}

##### Print the data and continue the execution #####
function debug($array) {
	echo "<pre>"; print_r($array); echo "</pre>";
}

##### Print the data and exit #####
function xdebug($array) {
	debug($array);exit;
}

##### var_dump data and continue the execution #####
function vardump($array) {
	echo "<pre>"; var_dump($array); echo "</pre>";
}

##### var_dump the data and exit #####
function xvardump($array) {
	vardump($array);exit;
}

##### Print the data into browser console in json format and continue #####
function cdebug($array) {
	echo "<script>console.log(".json_encode($array, JSON_PRETTY_PRINT).");</script>";
}

##### Print the last query #####
function last_query() {
	$CI =& get_instance();
	echo $CI->db->last_query();
}

##### Print the last query and exit #####
function xlast_query()
{
	last_query();exit;
}
/*	Returns the string in proper case
 *	@param 	string or array
 *			seperater_to_replace string
*/
function toProperCase($string,$seperater_to_replace = NULL) {
	if(gettype($string) == 'string') {
		if( isset($seperater_to_replace)) {
			$string = str_replace($seperater_to_replace,' ',$string);
		}
		return ucwords(strtolower($string));
	} else if(gettype($string) == 'array') {
		foreach($string as $key => $value) {
			$string[$key] = toProperCase($value,$seperater_to_replace);
		}
		return $string;
	}
}

/* Function to include single/multiple entities
 *	@para entity names in array format or comma(,) seperated strings
 *
 *	Note:
 *	Entity file name must be ending with '_entity'.
 *	Do not include '_entity' while passing through parameters.
*/
function load_entities($entities) {
	if(gettype($entities) == 'string') {
		$entities = func_get_args();
	}

	foreach($entities as $entity) {
		include_once( APPPATH . 'models/entities/'.$entity.'_entity'. EXT );
	}
}

##### Remove all spaces and special characters #####
function remove_spaces_specialchars($words) {
	return strtolower(preg_replace("/[^a-zA-Z]/", "", $words));
}

##### check string starts with #####
function string_starts_with($string,$query) {
	return (substr($string, 0, strlen($query)) === $query);
}

##### check string ends with #####
function string_ends_with($string, $query) {
    return strrpos($string, $query) + strlen($query) === strlen($string);
}

#####  string convert to uppercase #####
function toUpperCase($string,$seperater_to_replace = NULL) {
	if(gettype($string) == 'string') {
		if( isset($seperater_to_replace)) {
			$string = str_replace($seperater_to_replace,' ',$string);
		}
		return strtoupper($string);
	} else if(gettype($string) == 'array') {
		foreach($string as $key => $value) {
			$string[$key] = toUpperCase($value,$seperater_to_replace);
		}
		return $string;
	}
}

##### limited charater print #####
function limit_char($str, $length) {
	$string = strip_tags($str);
	if(strlen($string) <= $length) {
		return $string;
	} else {
		$output=substr($string, 0, $length) . '...';
		return trim($output);
	}
}

function timePassed($start_time, $end_time) {
	$timePassed = $end_time - $start_time;
	$elapsedString = "";
	if($timePassed <= 59) {
		// $elapsedString .= "Just now";
		$elapsedString .= "Immediate";
	} elseif($timePassed <= 119) {
		$elapsedString .= "1 Min";
	} elseif($timePassed <= 3599) {
		$minutes = floor($timePassed / 60);
		$timePassed -= $minutes * 60;
		$elapsedString .= $minutes." Mins";
	} elseif($timePassed <= 7199) {
		$elapsedString .= "1 Hour";
	} elseif($timePassed <= 84399) {
		$hours = floor($timePassed / 3600);
		$timePassed -= $hours * 3600;
		$elapsedString .= $hours." Hours";
	} elseif($timePassed <= 172799) {
		$elapsedString .= "1 Day";
	} elseif($timePassed > 86400) {
		$days = floor($timePassed / 86400);
		$timePassed -= $days * 86400;
		$elapsedString .= $days." Days";
	} elseif($timePassed <= 5183999) {
		$elapsedString .= "1 Month";
	} elseif($timePassed <= 31535999) {
		$elapsedString .= "2 Months";
	} elseif($timePassed <= 63071999) {
		$elapsedString .= "1 Year";
	} else {
		$elapsedString .= "2 Years";
	}
	return $elapsedString;
}

function dateDiff($startTime) {
	/* $now = time();
	$difference = $now - $startTime;
	$days = floor($difference / (60*60*24));
	return $days; */
	
	$now = time();
	$diff 	= abs($now - $startTime); 
	$years	= floor($diff / (365*60*60*24)); 
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
	$days 	= floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24)); 
	$hours 	= floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60)); 
	return round($days.'.'.$hours, PHP_ROUND_HALF_UP);
}

function percentage($actual, $total) {
	if(isset($actual) && !empty($actual)) {
		$per = ($actual*100) / $total;
		$data = number_format($per, 0);
	} else {
		$data = 0;
	}
	return $data;
}
function encode($str) {
    return strtolower(addslashes(htmlentities($str, ENT_QUOTES)));
}

function decode($str) {
    return toProperCase(stripslashes(html_entity_decode(trim($str),ENT_QUOTES)));
}
/* end of helper */