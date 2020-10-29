<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Library for secure SMS sending using php curl
 * @SMS gateway Provider: TEXTLOCAL
 * @Service name: SMS Gateway
 * @Website of provider: https://www.textlocal.in/
 * @SMS Gateway Control Panel: https://control.textlocal.in/
 * @Version 1.1
 * @Authors : Bhavin Sakariya
 * @project : Shopping 13
 */

Class SMSServices
{
	/*
	* Common Details and Credentials for SMS gateway
	*/

	private $sms_url   = 'http://login.aquasms.com/sendSMS?';
	private $api_key   = 'a2ebaefc-9dc7-486a-9093-b9e1a1eb4ca4'; //API key here
	private $username  = '9033227748'; // Username here
	private $sender_id = 'NatKit'; // Sender ID here
	private $smstype   = 'TRANS'; // SMS Type here

	function __construct(){}

	/*
	* Function to send SMS
	* @parameters :
	* MESSAGE
	* RECIPIENT MOBILE NUMBER
	*/

	private function _send_sms($message, $recipient) {
		/*  Required to add 91 to each mobile number */
		$recipient = $recipient;
		$msg       = urldecode($message);
	//	$msg       = rawurlencode($message);
	
		$url = $this->sms_url;
		$data = array(
			'username'   => urlencode($this->username),
			'apikey'     => urlencode($this->api_key),
			'numbers'    => $recipient,
			'sendername' => urlencode($this->sender_id),
			'smstype'    => urlencode($this->smstype),
			'message'    => $msg
		);
	 	$json_response = $this->_exec_url($url, $data);
	 	return $response = json_decode($json_response);
	 	
	 	if($response->status == 'success') {
	 		return TRUE;
	 	}
	 	return FALSE;
	}

	/*
	* Execute url using php curl library
	* @parameters : URL TO EXECUTE, DATA ARRAY
	*/

	private function _exec_url($url, $data) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		return curl_exec($ch);
	}
	
	/*
	* Send SMS to user with OTP(One Time Password)
	* @parameters : OTP and  USER MOBILE NUMBER
	* Recipient no length : 10
	* approved template: "Your One Time Password (OTP) is {OTP}."
	*/
	
	public function _send_otp($recipient, $otp) {
		$message = $otp.' is your Shopping 13 One Time Password (OTP)';
		return $this->_send_sms($message, $recipient);
	}
}