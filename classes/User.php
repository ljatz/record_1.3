<?php

class User
{
	private $_db;
	private $_data;
	private $_sessionName = 'korisnik';
	private $_cookieName = 'record';
	private $_cookieExpire = 60*60*24;
	private $_isLoggedIn = false;
	
	public function __construct($user = null)
	{
		$this->_db = DB::getInstance();
		
		if(!$user){
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);
				
				if($this->find($user)) {
					$this->_isLoggedIn = true;
				} else {
					// logout
				}
			}
		} else {
			$this->find($user);
		}
	}
	
	public function create($fields = array())
	{
		if(!$this->_db->insert('users',$fields)) {
			throw new Exception('Nešto nije u redu, pokušajte ponovo!');
		}
	}
	
	public function find($user = null)
	{
		if($user) {
			$field = (is_numeric($user)) ? 'id' : 'email';
			$data = $this->_db->get('*', 'users', array($field,'=',$user));
			
			if($data->count()){
				$this->_data = $data->first();
				return true;
			}
		}
		
		return false;
	}
	
	public function login($email = null, $password = null, $remember = false)
	{
		if(!$email && !$password && $this->exists()){
			Session::put($this->_sessionName, $this->data()->id);
		} else {
			if($this->find($email)) {
				if($this->data()->password === Hash::make($password, $this->data()->salt)){
					
					Session::put($this->_sessionName, $this->data()->id);
					
					if($remember){
						$hash = Hash::unique();
						$hashCheck = $this->_db->get('hash', 'sessions', array('user_id', '=', $this->data()->id));
						
						if(!$hashCheck->count()){
							$this->_db->insert('sessions', array(
								'user_id'	=> $this->data()->id,
								'hash'		=> $hash
							));
						} else {
							$hash = $hashCheck->first()->hash;
						}
						
						Cookie::put($this->_cookieName, $hash, $this->_cookieExpire);
					}
					
					return true;
				}
			} else {
				return 'NOEMAIL';
			}
		}
		
		return false;
	}
	
	public function logout()
	{		
		$this->_db->delete('sessions', 'user_id', $this->data()->id);
		
		Cookie::delete($this->_cookieName);
		
		Session::delete($this->_sessionName);
		
		session_destroy();
	}
	
	public function data()
	{
		return $this->_data;
	}
	
	public function check()
	{
		return $this->_isLoggedIn;
	}
	
	public function exists()
	{
		return (!empty($this->_data)) ? true : false;
	}
}


?>