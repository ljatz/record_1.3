<?php

class Cookie
{
	private function __construct(){}
	
	public static function exists($key)
	{
		return (isset($_COOKIE[$key])) ? true : false;
	}
	
	public static function get($key)
	{
		if(self::exists($key))
			return $_COOKIE[$key];
	}
	
	public static function put($name, $value, $expire)
	{
		if(setcookie($name, $value, time() + $expire, '/', null, null, true)) {
			return true;
		}
		return false;
	}
	
	public static function delete($name)
	{
		self::put($name, '', -1);
	}	
}

?>