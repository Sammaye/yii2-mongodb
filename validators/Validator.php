<?php

namespace sammaye\mongodb\validators;

use yii\mongodb\validators\MongoDateValidator;
use yii\validators\Validator as BaseValidator;

class Validator extends BaseValidator
{
    public static $builtInValidators = [
        'id' => [
            'class' => 'yii\mongodb\validators\MongoIdValidator',
            'forceFormat' => 'object'
        ],
        'boolean' => 'sammaye\mongodb\validators\BooleanValidator',
        'captcha' => 'yii\captcha\CaptchaValidator',
        'compare' => 'yii\validators\CompareValidator',
        'date' => 'yii\mongodb\validators\MongoDateValidator',
        'datetime' => [
            'class' => 'yii\mongodb\validators\MongoDateValidator',
            'type' => MongoDateValidator::TYPE_DATETIME,
        ],
        'time' => [
            'class' => 'yii\mongodb\validators\MongoDateValidator',
            'type' => MongoDateValidator::TYPE_TIME,
        ],
        'default' => 'yii\validators\DefaultValueValidator',
        'double' => 'yii\validators\NumberValidator',
        'each' => 'sammaye\mongodb\validators\EachValidator',
        'email' => 'yii\validators\EmailValidator',
        'exist' => 'yii\validators\ExistValidator',
        'file' => 'yii\validators\FileValidator',
        'filter' => 'yii\validators\FilterValidator',
        'image' => 'yii\validators\ImageValidator',
        'in' => 'sammaye\mongodb\validators\RangeValidator',
        'inInt' => [
            'class' => 'sammaye\mongodb\validators\RangeValidator',
            'filter' => 'intval'
        ],
        'integer' => [
            'class' => 'sammaye\mongodb\validators\NumberValidator',
            'integerOnly' => true,
            'filter' => 'intval'
        ],
        'float' => [
            'class' => 'sammaye\mongodb\validators\NumberValidator',
            'filter' => 'floatval'
        ],
        'match' => 'yii\validators\RegularExpressionValidator',
        'number' => 'sammaye\mongodb\validators\NumberValidator',
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