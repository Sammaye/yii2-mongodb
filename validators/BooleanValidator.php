<?php

namespace sammaye\mongodb\validators;

use Yii;
use yii\validators\BooleanValidator as BaseBooleanValidator;

class BooleanValidator extends BaseBooleanValidator
{
    public $type = 'int';

    public function validateAttribute($model, $attribute)
    {
        $result = $this->validateValue($model->$attribute);
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
            return false;
        }

        $val = (int)$model->$attribute;
        if ($this->type === 'int') {
            $model->$attribute = $val;
        } elseif ($val) {
            $model->$attribute = true;
        } else {
            $model->$attribute = false;
        }
    }

    protected function validateValue($value)
    {
        return parent::validateValue($value);
    }
}
