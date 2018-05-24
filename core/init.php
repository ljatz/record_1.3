<?php

	session_start();
	session_regenerate_id(true);

	spl_autoload_register(function($class){
		include 'classes/' . $class . '.php'; 
	});

	$items = Config::get('app');
		if($items['debug'] === false){
			error_reporting(0);
			echo '<h1>Nešto je pošlo krivo!</h1>';
		}

	require_once 'functions/sanitize.php';
	require_once 'functions/debug.php';

	if(Cookie::exists('record') && !Session::exists('user')) {
		$hash = Cookie::get('record');
		$hashChek = DB::getInstance()->get('user_id', 'sessions', array('hash', '=', $hash));
		
		if($hashChek->count()) {
			$user = new User($hashChek->first()->user_id);
			$user->login();
		}
	}

?>