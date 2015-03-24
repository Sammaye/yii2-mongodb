<?php

namespace sammaye\mongoyii2\validators;

class Validator extends \yii\validators\Validator
{
	/**
	 * @var array list of built-in validators (name => class or configuration)
	 */
	public static $builtInValidators = [
		'id' => 'sammaye\mongoyii2\validators\MongoIdValidator',
		'boolean' => 'yii\validators\BooleanValidator',
		'captcha' => 'yii\captcha\CaptchaValidator',
		'compare' => 'yii\validators\CompareValidator',
		'date' => 'sammaye\mongoyii2\validators\DateValidator',
		'default' => 'yii\validators\DefaultValueValidator',
		'double' => 'yii\validators\NumberValidator',
		'email' => 'yii\validators\EmailValidator',
		'exist' => 'yii\validators\ExistValidator',
		'file' => 'yii\validators\FileValidator',
		'filter' => 'yii\validators\FilterValidator',
		'image' => 'yii\validators\ImageValidator',
		'in' => 'sammaye\mongoyii2\validators\RangeValidator',
		'inInt' => [
			'class' => 'sammaye\mongoyii2\validators\RangeValidator',
			'format' => 'int'
		],
		'integer' => [
			'class' => 'sammaye\mongoyii2\validators\NumberValidator',
			'integerOnly' => true,
			'format' => 'int'
		],
		'float' => [
			'class' => 'sammaye\mongoyii2\validators\NumberValidator',
			'format' => 'float'
		],
		'array' => 'sammaye\mongoyii2\validators\ArrayValidator',
		'match' => 'yii\validators\RegularExpressionValidator',
		'number' => 'sammaye\mongoyii2\validators\NumberValidator',
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