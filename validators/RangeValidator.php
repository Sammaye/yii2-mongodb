<?php

namespace sammaye\mongoyii2\validators;

use yii\validators\RangeValidator as BaseRangeValidator;

class RangeValidator extends BaseRangeValidator
{
	public $format;
	
	
	/**
	 * @inheritdoc
	 */
	public function validateAttribute($model, $attribute)
	{
	    $result = $this->validateValue($model->$attribute);
	    
	    $value = $model->$attribute;
	    if(!is_array($value)){
	    	$value = $this->format($value);
	    }else{
		    array_walk($value, function($item){
		    	$item = $this->format($item);
		    });
	    }
	    $model->$attribute = $value;
	    
	    
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
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
