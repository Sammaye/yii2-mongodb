<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sammaye\mongoyii\validators;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\validators\RangeValidator as BaseRangeValidator;

class RangeValidator extends BaseRangeValidator
{
    public $filter;

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        parent::validateAttribute($model, $attribute);
        if ($this->filter) {
            $value = $model->$attribute;
            foreach ($value as $k => $v) {
                $value[$k] = call_user_func($this->filter, $value);
            }
            $model->$attribute = $value;
        }
    }
}
