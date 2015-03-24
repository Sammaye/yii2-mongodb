<?php

namespace sammaye\mongoyii\validators;

class Validator extends \yii\validators\Validator
{
	public function __construct($config = [])
	{
		$validators = array_merge(
			static::$builtInValidators, 
			[
				'id' => 'sammaye\mongoyii\validators\MongoIdValidator',
				'date' => 'sammaye\mongoyii\validators\MongoDateValidator',
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
				'number' => 'sammaye\mongoyii\validators\NumberValidator',
			]
		);
		static::$builtInValidators = $validators;
		parent::__construct($config);
	}
}