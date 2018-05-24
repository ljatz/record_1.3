<?php

class Session
{
	private function __construct(){}
	
	public static function exists($key)
	{
		return (isset($_SESSION[$key])) ? true : false;
	}
	
	public static function all()
	{
		return $_SESSION;
	}
	
	public static function get($key)
	{
		if(self::exists($key))
			return $_SESSION[$key];
	}
	
	public static function put($key, $value)
	{
		return $_SESSION[$key] = $value;
	}
	
	public static function delete($key)
	{
		if(self::exists($key))
			unset($_SESSION[$key]);
	}
	
	public static function flash($key, $msg='')
	{
		if(self::exists($key)) {
			
			$message = self::get($key);
			self::delete($key);
			return $message;
			
		} else if(!empty($msg)) {
			self::put($key, $msg);
			return true;
		}
		
		return false;
	}
}

?>