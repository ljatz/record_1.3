<?php

	include_once 'core/init.php';
	
	$session = Session::all();
	
	foreach($session as $key => $value) {
		switch($key) {
			case 'success':
			case 'info':
			case 'warning':
			case 'danger':
				?>
				<div class="alert alert-<?php echo $key; ?> alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?php echo '<b>'.ucfirst($key). ':</b> '. $value; ?>
				</div>
				<?php
				Session::delete($key);
			break;
			default:
		}
	}
?>