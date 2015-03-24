<?php

namespace sammaye\mongoyii\validators;

class Validator extends \yii\validators\Validator
{
	/**
	 * @var array list of built-in validators (name => class or configuration)
	 */
	public static $builtInValidators = [
		'id' => 'sammaye\mongoyii\validators\MongoIdValidator',
		'boolean' => 'yii\validators\BooleanValidator',
		'captcha' => 'yii\captcha\CaptchaValidator',
		'compare' => 'yii\validators\CompareValidator',
		'date' => 'sammaye\mongoyii\validators\MongoDateValidator',
		'default' => 'yii\validators\DefaultValueValidator',
		'double' => 'yii\validators\NumberValidator',
		'email' => 'yii\validators\EmailValidator',
		'exist' => 'yii\validators\ExistValidator',
		'file' => 'yii\validators\FileValidator',
		'filter' => 'yii\validators\FilterValidator',
		'image' => 'yii\validators\ImageValidator',
		'in' => 'sammaye\mongoyii\validators\RangeValidator',
		'inInt' => [
			'class' => 'sammaye\mongoyii\validators\RangeValidator',
			'format' => 'int'
		],
		'integer' => [
			'class' => 'sammaye\mongoyii\validators\NumberValidator',
			'integerOnly' => true,
			'format' => 'int'
		],
		'float' => [
			'class' => 'sammaye\mongoyii\validators\NumberValidator',
			'format' => 'float'
		],
		'array' => 'sammaye\mongoyii\validators\ArrayValidator',
		'match' => 'yii\validators\RegularExpressionValidator',
		'number' => 'sammaye\mongoyii\validators\NumberValidator',
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