<?php

include_once 'core/init.php';
	
	$user = new User();
	
	if(!$user->check()) {
		Redirect::to('index');
	}

	$slug = $user->data()->slug;
		
	$id = Input::page('id');
	
	$sql = 'SELECT * FROM objects WHERE id ='.$id;
		
	DB::getInstance()->query('DELETE FROM objects WHERE id ='.$id)->results();
	Redirect::to('dashboard');
	
?>	
