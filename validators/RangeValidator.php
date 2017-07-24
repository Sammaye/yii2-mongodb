<?php
namespace sammaye\mongodb\validators;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\validators\RangeValidator as BaseRangeValidator;

class RangeValidator extends BaseRangeValidator
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