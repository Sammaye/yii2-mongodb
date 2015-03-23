<?php

namespace common\components;

use yii\validators\RangeValidator;

class IntRangeValidator extends RangeValidator
{
	/**
	 * @inheritdoc
	 */
	public function validateAttribute($model, $attribute)
	{
	    $result = $this->validateValue($model->$attribute);
	    
	    $value = $model->$attribute;
	    if(!is_array($value)){
	    	$value = (int)$value;
	    }else{
		    array_walk($value, function($item){
		    	$item = (int)$item;
		    });
	    }
	    $model->$attribute = $value;
	    
	    
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
	}
}
