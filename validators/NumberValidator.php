<?php

namespace sammaye\mongoyii\validators;

use yii\validators\NumberValidator as BaseNumberValidator;

class NumberValidator extends BaseNumberValidator
{
	public $format;
	
	
	public function validateAttribute($model, $attribute)
	{
		$result = parent::validateAttribute($model, $attribute);
		/* Not sure about this, left it here in case
		if(
			count($model->getErrors($attribute)) <= 0 && 
			$this->format === null && 
			$this->format !== false
		){
			
			$value = $model->$attribute;
			
			if(preg_match('#^0#', $value)){
				// Starts with 0, string it
				$model->$attribute = $value;
			}elseif(preg_match('#([0-9]+)\.([0-9]+)#')){
				$model->$attribute = floatval($value);
			}else{
				$model->$attribute = $value;
			}
			
		}else{
			$model->$attribute = $this->format($model->$attribute);
		}
		*/
		$model->$attribute = $this->format($model->$attribute);
		return $result;
	}
	
	public function format($value)
	{
		if(is_callable($this->format)){
			return $this->format($value);
		}else{
			switch($this->format){
				case "int":
					return (int)$value;
				case "string":
					return (String)$value;
				case "float":
					return floatval($value);
				default:
					return $value;
			}
		}
	}
}