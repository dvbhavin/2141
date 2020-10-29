<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calendar_entity
{
	public $calendar_id = NULL;
	public $full_date;
	public $month;
	public $year;
	public $day_of_week; //1 monday.. 7 sunday
	public $day_num_in_year; //1 monday.. 7 sunday
	public $week_num_in_year;
	//public $num_of_days_month = '';

	public function __construct()
	{
		$this->full_date = date('Y-m-d');
		$this->month = date('m'); //01 through 12
		$this->year = date('Y');
		$this->day_of_week = date('N');  //1 monday.. 7 sunday
		$this->day_num_in_year = date('z'); //1 monday.. 7 sunday
		$this->week_num_in_year = date('W');
		//$this->num_of_days_month = date('t');
	}
}
