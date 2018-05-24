<?php

return [

	'fetch' => PDO::FETCH_OBJ,  
	'driver' => 'mysql',
	
	'mysql'	=> [
		'host'	=> '127.0.0.1',
		'user'	=> 'root',
		'pass'  => '',
		'db'    => 'rec',
		'options'	=>	[
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		]
	]
];

?>