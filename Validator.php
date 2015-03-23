<?php

namespace common\components;

class Validator extends \yii\validators\Validator
{
	/**
	 * @var array list of built-in validators (name => class or configuration)
	 */
	public static $builtInValidators = [
		'id' => 'common\components\MongoIdValidator',
		'boolean' => 'yii\validators\BooleanValidator',
		'captcha' => 'yii\captcha\CaptchaValidator',
		'compare' => 'yii\validators\CompareValidator',
		'date' => 'common\components\DateValidator',
		'default' => 'yii\validators\DefaultValueValidator',
		'double' => 'yii\validators\NumberValidator',
		'email' => 'yii\validators\EmailValidator',
		'exist' => 'yii\validators\ExistValidator',
		'file' => 'yii\validators\FileValidator',
		'filter' => 'yii\validators\FilterValidator',
		'image' => 'yii\validators\ImageValidator',
		'in' => 'yii\validators\RangeValidator',
		'integer' => [
			'class' => 'common\components\NumberValidator',
			'integerOnly' => true,
		],
		'match' => 'yii\validators\RegularExpressionValidator',
		'number' => 'common\components\NumberValidator',
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
	];
}