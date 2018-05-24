<?php

class Config
{
	private function __construct(){}
	
	public static function get($file)
	{
		if($file) {
			$path = 'config/' . $file . '.php';
			if(file_exists($path)){
				return include $path;
			}	
		}
		
		return false;
	}
}

?>