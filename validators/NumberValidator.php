<?php

namespace sammaye\mongoyii2\validators;

use yii\validators\NumberValidator as BaseNumberValidator;

class NumberValidator extends BaseNumberValidator
{
	public $cast = true;
	
	
	public function validateAttribute($model, $attribute)
	{
		$result = parent::validateAttribute($model, $attribute);
		$model->$attribute = $this->cast ? (int)$model->$attribute : $model->$attribute;
		return $result;
	}
}