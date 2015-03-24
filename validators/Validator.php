<?php

namespace sammaye\mongoyii2\validators;

class Validator extends \yii\validators\Validator
{
	public function __construct($config = [])
	{
		$validators = array_merge(
			static::$builtInValidators, 
			[
				'id' => 'sammaye\mongoyii2\validators\MongoIdValidator',
				'date' => 'sammaye\mongoyii2\validators\MongoDateValidator',
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
				'number' => 'sammaye\mongoyii2\validators\NumberValidator',
			]
		);
		static::$builtInValidators = $validators;
		parent::__construct($config);
	}
}