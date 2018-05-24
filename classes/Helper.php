<?php

class Helper
{
	private function __construct(){}
	
	public static function getHeader($title='', $file = 'header', $user = null)
	{
		if($file) {
			$path = 'includes/layout/' . $file . '.php';
			if(file_exists($path)){
				return include $path;
			}	
		}
		
		return false;
	}
	
	public static function getFooter($file = 'footer')
	{
		if($file) {
			$path = 'includes/layout/' . $file . '.php';
			if(file_exists($path)){
				return include $path;
			}	
		}
		
		return false;
	}
}

?>