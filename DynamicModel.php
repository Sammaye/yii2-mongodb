<?php

namespace sammaye\mongoyii2;

use Yii;
use sammaye\mongoyii2\Model;
use yii\validators\Validator;

class DynamicModel extends Model
{
	private $_rules = [];
	private $_names = [];
	private $_attributes = [];
	
	public function __construct(array $attributes = [], $config = [])
	{
		$names = [];
		foreach ($attributes as $name => $value) {
			if (is_integer($name)) {
				$this->_attributes[$value] = null;
				$names[] = $value;
			} else {
				$this->_attributes[$name] = $value;
				$names[] = $name;
			}
		}
		$this->_names = array_keys(array_flip($names));
		parent::__construct($config);
	}
	
	public function __get($name)
	{
		if($this->hasAttribute($name)){
			return $this->_attributes[$name];
		}else{
			return parent::__get($name);
		}
	}
	
	public function __set($name, $value)
	{
		if($this->hasAttribute($name)){
			$this->_attributes[$name] = $value;
		}else{
			parent::__set($name, $value);
		}
	}
	
	public function __isset($name)
	{
		if ($this->hasAttribute($name)) {
			return isset($this->_attributes[$name]);
		} else {
			return parent::__isset($name);
		}
	}

	public function __unset($name)
	{
		if ($this->hasAttribute($name)) {
			unset($this->_attributes[$name]);
		}else{
			parent::__unset($name);
		}
	}

	public function hasAttribute($name)
	{
		return isset($this->_attributes[$name]) || in_array($name, $this->attributes());
	}
	
	public function getAttribute($name)
	{
		return isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
	}
	
	public function setAttribute($name, $value)
	{
		if (array_key_exists($name, $this->_attributes)) {
			$this->_attributes[$name] = $value;
		} else {
			throw new InvalidParamException(get_class($this) . ' has no attribute named "' . $name . '".');
		}
	}
	
	public function clearAttributes()
	{
		$this->_attributes = [];
	}

	public function rules($rules = null)
	{
		if($rules){
			$attributes = [];
			
			foreach($rules as $k => $v){
				if(is_array($v[0])){
					foreach($v[0] as $field){
						$atttributes[] = $field;
					}
				}else{
					if($v[0] === '$'){
						$rules[$k][0] = 'value';
					}
					$attributes[] = $rules[$k][0];
				}
			}
			
			if(empty($this->_names)){
				$this->_names = array_keys(array_flip($attributes));
			}
			$this->_rules = $rules;
		}
		return $this->_rules;
	}
	
	/**
	 * Adds a validation rule to this model.
	 * You can also directly manipulate [[validators]] to add or remove validation rules.
	 * This method provides a shortcut.
	 * @param string|array $attributes the attribute(s) to be validated by the rule
	 * @param mixed $validator the validator for the rule.This can be a built-in validator name,
	 * a method name of the model class, an anonymous function, or a validator class name.
	 * @param array $options the options (name-value pairs) to be applied to the validator
	 * @return static the model itself
	 */
	public function addRule($attributes, $validator, $options = [])
	{
		$validators = $this->getValidators();
		$validators->append(Validator::createValidator($validator, $this, (array) $attributes, $options));
	
		return $this;
	}
	
	public function attributes($names = null)
	{
		if($names){
			$this->_names = $names;
		}
		return $this->_names;
	}
}