<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sammaye\mongoyii\validators;

use Yii;
use yii\validators\NumberValidator as BaseNumberValidator;

class NumberValidator extends BaseNumberValidator
{
    public $filter;

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        parent::validateAttribute($model, $attribute);

        if ($this->filter) {
            $model->$attribute = call_user_func($this->filter, $model->$attribute);
        }
    }
}
