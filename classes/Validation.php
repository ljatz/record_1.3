<?php

class Validation
{
	private $_passed = false;
	private $_errors = array();
	private $_db = null;
	
	public function __construct()
	{
		$this->_db = DB::getInstance();
	}
	
	public function check($items = array())
	{
		foreach($items as $field => $rules){
			foreach($rules as $rule => $rule_value){
				
				$value = trim(Input::get($field));
				
				if($rule === 'required' && empty($value)) {
					$this->addError($field, "Polje {$field} morate ispuniti.");
				} else if(!empty($value)) {
					switch($rule) {
						case 'min':
							if(strlen($value) < $rule_value)
								$this->addError($field, "Polje {$field} mora imati najmanje {$rule_value} znakova.");
						break;
						case 'max':
							if(strlen($value) > $rule_value)
								$this->addError($field, "Polje {$field} može imati najviše {$rule_value} characters.");
						break;
						case 'matches':
							if($value != Input::get($rule_value))
								$this->addError($field, "Polje {$field} mora odgovarati polju {$rule_value}.");
						break;
						case 'unique':
							$check = $this->_db->get('id',$rule_value,array($field,'=',$value));
							if($check->count())
								$this->addError($field, "{$field} već postoji.");
						break;
						case 'num':
							if(!is_numeric($value)) {
								$this->addError($field, "U polje {$field} možete upisati broj");
							}
						break;
						/*case 'must_have':
							if(!preg_match('/\b  ovdje upisati npr. @naziv_tvrtke.com  (ovo je opcionalno)    \b/i', $value)) {
								$this->addError($field, "Polje {$field} mora imati važeći email.");
							}
						break;*/
					}
				}
			}	
		}
		
		if(empty($this->_errors)) {
			$this->_passed = true;
		}
		
		return $this;
		
	}
	
	public function addError($field, $error)
	{
		$this->_errors[$field] = $error;
	}
	
	public function passed()
	{
		return $this->_passed;
	}
	
	public function errors()
	{
		return $this->_errors;
	}
	
	public function hasError($field)
	{
		if(isset($this->_errors[$field]))
			return $this->_errors[$field];
		return false;
	}
}

?>