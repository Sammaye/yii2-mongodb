<?php

namespace common\components;

use yii\validators\NumberValidator as NumValidator;

class NumberValidator extends NumValidator
{
	public function validateAttribute($model, $attribute)
	{
		$result = parent::validateAttribute($model, $attribute);
		$model->$attribute = (int)$model->$attribute;
		return $result;
	}
}