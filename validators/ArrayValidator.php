<?php

namespace sammaye\mongoyii2\validators;

use Yii;
use sammaye\mongoyii2\DynamicModel;
use sammaye\mongoyii2\validators\Validator;

class ArrayValidator extends Validator
{
	public $required = false;
	public $min = 0;
	public $max = 99999999;
	public $rules = [];
	
	private $_errors = [];
	
	public function validateAttribute($model, $attribute)
	{
		if(($result = $this->validateValue($model->$attribute)) === false){
			foreach($this->_errors as $error){
				$this->addError(
					$model, 
					$attribute, 
					$error, 
					[
						'attribute' => $model->getAttributeLabel($attribute)
					]
				);
			}
			return false;
		}else{
			$model->$attribute = $result;
			return true;
		}
	}
	
	public function validateValue($value)
	{
		if(is_string($value)){
			$value = explode(',', $value);
		}
		$newValue = [];
	
		if($this->required && empty($value)){
			$this->_errors[] = '{attribute} cannot be empty.';
			return false;
		}elseif(empty($value)){
			return true;
		}
	
		if($this->min > -1 && $this->min > count($value)){
			$this->_errors[] = '{attribute} has too few entries.';
			return false;
		}
	
		if($this->max > -1 && $this->max < count($value)){
			$this->_errors[] = '{attribute} has too many entries.';
			return false;
		}
	
		$vModel = new DynamicModel();
		$vModel->rules(is_array($this->rules) ? $this->rules : []);
	
		foreach($value as $k => $v){
			 
			$vModel->clearAttributes();
			$vModel->clearErrors();
			 
			$vModel->setAttributes(['value' => $v]);
			$vModel->validate();
			 
			if($vModel->getErrors()){
				$this->_errors[] = '{attribute} was not filled in correctly.';
				break;
			}
			$newValue[] = $vModel->value;
		}
	
		if(count($this->_errors) > 0){
			return false;
		}
		return $newValue;
	}
}