<?php

class DB
{
	private static $_instance = null;
	private $_config;
	private $_connection;
	private $_query;
	private $_error = false;
	private $_results;
	private $_count = 0;
	
	private function __construct()
	{
		$this->_config = Config::get('database');
		$driver = $this->_config['driver'];
		$host = $this->_config[$driver]['host'];
		$user = $this->_config[$driver]['user'];
		$pass = $this->_config[$driver]['pass'];
		$db = $this->_config[$driver]['db'];
		
		try {
			$this->_connection = new PDO($driver.':host='.$host.';dbname='.$db, $user, $pass);
		} catch(PDOException $e) {
			die($e->getMessage());
		}
		
	}
	
	private function __clone(){}
	
	public static function getInstance()
	{
		if(!self::$_instance){
			self::$_instance = new DB();
		}
		
		return self::$_instance;
	}
	
	public function query($sql, $values=array())
	{
		$this->_error = false;
		
		if($this->_query = $this->_connection->prepare($sql)){
			$x = 1;
			if(!empty($values)) {
				foreach($values as $value) {
					$this->_query->bindValue($x, $value);
					$x++;
				}
			}
			
			if($this->_query->execute()){
				$this->_results = $this->_query->fetchAll($this->_config['fetch']);
				$this->_count = $this->_query->rowCount();
			} else {
				$this->_error = true;
			}	
		} else {
			$this->_error = true;
		}
		
		return $this;
	}
	
	private function action($action, $table, $where = array())
	{
		if(!empty($where) && count($where) === 3){
			$operators = array('=','<','>','<=','>=','<>');
			
			$field    = $where[0];
			$operator = $where[1];
			$value    = $where[2];
			
			if(in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				
				if(!$this->query($sql,array($value))->error()) {
					return $this;
				}
			}
			
		} else {
			$sql = "{$action} FROM {$table}";
			
			if(!$this->query($sql)->error()) {
				return $this;
			}
		}

		return false;
	}
	
	public function insert($table, $fields)
	{
		$columns = implode(',', array_keys($fields));
		$values = '';
		$fields_num = count($fields);
		$x = 1;
		
		foreach ($fields as $field) {
			$values .= '?';
			if($x < $fields_num) {
				$values .= ',';
			}
			$x++;
		}
		
		$sql = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
		
		if(!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;
	}
	
	public function update($table, $fields = array(), $id) {
		$values = '';

		foreach ($fields as $key => $field) {
			$values .= $key . '=?, ';
		}
		  
		$values = substr($values, 0, -2);
		array_push($fields, $id);
		$sql = "UPDATE {$table} SET {$values} WHERE id=?";

		if(!$this -> query($sql, $fields) -> error()) {
			return $this;
		}
			return false;
	}
	
	public function get($field, $table, $where = array())
	{
		return $this->action("SELECT {$field}", $table, $where);
	}
	
	public function find($id, $table)
	{
		return $this->action('SELECT *', $table, array('id','=',$id));
	}
	
	public function delete($table, $field, $value)
	{
		return $this->action('DELETE', $table, array($field,'=',$value));
	}	
	
	public function getConnection()
	{
		return $this->_connection;
	}
	
	public function error()
	{
		return $this->_error;
	}
	
	public function results()
	{
		return $this->_results;
	}
	
	public function first()
	{
		return $this->_results[0];
	}
	
	public function count()
	{
		return $this->_count;
	}
	
}

?>