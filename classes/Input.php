<?php

class Input
{
	private function __construct(){}
	
	public static function exists($method = 'post')
	{
		switch($method) {
			case 'post':
				return (!empty($_POST)) ? true : false;
			break;
			case 'get':
				return (!empty($_GET)) ? true : false;
			break;
			default:
				return false;
			break;
		}	
	}
	
	public static function get($field, $method = 'post')
	{
		switch($method){
			case 'post':
				return (isset($_POST[$field])) ? $_POST[$field] : false;
			break;
			case 'get':
				return (isset($_GET[$field])) ? $_GET[$field] : false;
			break;
			default:
				return false;
			break;	
		}
	}
	
	public static function page($field, $method = 'get')
	{
		switch($method){
			case 'post':
				return (isset($_POST[$field])) ? $_POST[$field] : false;
			break;
			case 'get':
				return (isset($_GET[$field])) ? $_GET[$field] : false;
			break;
			default:
				return false;
			break;	
		}
	}
}

?>