<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Push_andy_notify {
	private $title;
	private $message;
	private $image_url;
	private $action;
	private $action_destination;
	private $product_data;
	private $data;
	
	function __construct(){ }
 
	public function setTitle($title){
		$this->title = $title;
	}
 
	public function setMessage($message){
		$this->message = $message;
	}
 
	public function setImage($imageUrl){
		$this->image_url = $imageUrl;
	}

	public function setAction($action){
		$this->action = $action;
	}
 
	public function setActionDestination($actionDestination){
		$this->action_destination = $actionDestination;
	}
	
	public function setProductData($productData){
		$this->product_data = $productData;
	}
 
	public function setPayload($data){
		$this->data = $data;
	}
	
	public function getNotificatin(){
		$notification = array();
		$notification['title']    = $this->title;
		$notification['message']  = $this->message;
		$notification['image']    = $this->image_url;
		$notification['action']   = $this->action;
		$notification['redirect'] = $this->action_destination;
		$notification['data']     = $this->product_data;
		return $notification;
	}
}
?>
