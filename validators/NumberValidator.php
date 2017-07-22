<?php
namespace sammaye\mongodb\validators;

use Yii;
use yii\validators\NumberValidator as BaseNumberValidator;

class NumberValidator extends BaseNumberValidator
{
    public $filter;

    public function validateAttribute($model, $attribute)
    {
        parent::validateAttribute($model, $attribute);

        if ($this->filter) {
            $model->$attribute = call_user_func($this->filter, $model->$attribute);
        }
    }
}
