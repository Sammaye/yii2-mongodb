<?php
namespace sammaye\mongodb\validators;

use Yii;
use yii\validators\BooleanValidator as BaseBooleanValidator;

class BooleanValidator extends BaseBooleanValidator
{
    public $filter;

    public function validateAttribute($model, $attribute)
    {
        $result = $this->validateValue($model->$attribute);
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }

        if ($this->filter) {
            $value = $model->$attribute;
            foreach ($value as $k => $v) {
                $value[$k] = call_user_func($this->filter, $value);
            }
            $model->$attribute = $value;
        }
    }

    protected function validateValue($value)
    {
        return parent::validateValue($value);
    }
}
