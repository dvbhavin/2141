<?php
	$display_delay = 0;
	if( ! empty($this->notify_cnt))
	{
		$display_delay = $this->notify_cnt * 1000;
	}
	?>
	<script>
	setTimeout(function()
	{
		$.notify(
		{
			//options
			<?php 
			if( ! empty($icon))
			{
				?>
				icon: '<?php echo $icon; ?>',
				<?php 
			} 
			if( ! empty($title))
			{
				?>
				title: '<?php echo $title ?>',
				<?php 
			}
			?>
			message: '<?php echo $message ?>',
			<?php 
			if( ! empty($url))
			{
				?>
				url: '<?php echo $url ?>',
				target: '<?php echo $target ?>'
				<?php 
			}
			?>
		},{
			//settings
			allow_dismiss: '<?php echo $allow_dismiss ?>',
			type: '<?php echo $type ?>',
			delay: <?php echo $delay ?>

		});
	}, <?php echo $display_delay; ?>);
	</script>