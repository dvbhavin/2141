<?php

/* set notify mini view path here */
$notify_mini_view = 'common/notify_service/notify_mini_view';

$notifies = $this->session->flashdata('notifies');
if(!empty($notifies))
{
	$this->notify_cnt = 0;
	foreach($notifies as $notify)
	{
		$this->load->view($notify_mini_view, $notify);
		$this->notify_cnt++;
	}
}
?>