<?php

namespace sammaye\mongodb\validators;

use yii\validators\Validator as BaseValidator;

class Validator extends BaseValidator
{
    public static $builtInValidators = [
        'id' => [
            'class' => 'yii\mongodb\validators\MongoIdValidator',
            'forceFormat' => 'object'
        ],
        'boolean' => 'yii\validators\BooleanValidator',
        'captcha' => 'yii\captcha\CaptchaValidator',
        'compare' => 'yii\validators\CompareValidator',
        'date' => 'yii\mongodb\validators\MongoDateValidator',
        'datetime' => [
            'class' => 'yii\validators\DateValidator',
            'type' => DateValidator::TYPE_DATETIME,
        ],
        'time' => [
            'class' => 'yii\validators\DateValidator',
            'type' => DateValidator::TYPE_TIME,
        ],
        'default' => 'yii\validators\DefaultValueValidator',
        'double' => 'yii\validators\NumberValidator',
        'each' => 'common\components\mongodb\validators\EachValidator',
        'email' => 'yii\validators\EmailValidator',
        'exist' => 'yii\validators\ExistValidator',
        'file' => 'yii\validators\FileValidator',
        'filter' => 'common\components\mongodb\validators\FilterValidator',
        'image' => 'yii\validators\ImageValidator',
        'in' => 'common\components\mongodb\validators\RangeValidator',
        'inInt' => [
            'class' => 'common\components\mongodb\validators\RangeValidator',
            'filter' => 'intval'
        ],
        'integer' => [
            'class' => 'common\components\mongodb\validators\NumberValidator',
            'integerOnly' => true,
            'fitler' => 'intval'
        ],
        'float' => [
            'class' => 'common\components\mongodb\validators\NumberValidator',
            'format' => 'floatval'
        ],
        'match' => 'yii\validators\RegularExpressionValidator',
        'number' => 'common\components\mongodb\validators\NumberValidator',
        'required' => 'yii\validators\RequiredValidator',
        'safe' => 'yii\validators\SafeValidator',
        'string' => 'yii\validators\StringValidator',
        'trim' => [
            'class' => 'yii\validators\FilterValidator',
            'filter' => 'trim',
            'skipOnArray' => true,
        ],
        'unique' => 'yii\validators\UniqueValidator',
        'url' => 'yii\validators\UrlValidator',
        'ip' => 'yii\validators\IpValidator',
    ];
}