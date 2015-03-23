<?php

namespace sammaye\mongoyii2;

use Yii;
use ArrayObject;
use yii\base\InvalidConfigException;
use yii\mongodb\ActiveRecord as BaseActiveRecord;
use sammaye\mongoyii2\ActiveQuery;
use sammaye\mongoyii2\Validator;

class ActiveRecord extends BaseActiveRecord
{
	public function init()
	{
		return parent::init();
	}
	
	public static function find()
	{
		return Yii::createObject(ActiveQuery::className(), [get_called_class()]);
	}
	
	public function createValidators()
	{
		$validators = new ArrayObject;
		foreach ($this->rules() as $rule) {
			if ($rule instanceof Validator) {
				$validators->append($rule);
			} elseif (is_array($rule) && isset($rule[0], $rule[1])) { // attributes, validator type
				$validator = Validator::createValidator($rule[1], $this, (array) $rule[0], array_slice($rule, 2));
				$validators->append($validator);
			} else {
				throw new InvalidConfigException('Invalid validation rule: a rule must specify both attribute names and validator type.');
			}
		}
		return $validators;
	}
	
	public function getDirtyAttributes($names = null)
	{
		$attributes = parent::getDirtyAttributes($names);
		return $this->getRawAttributes($attributes);
	}
	
	public function getAttributes($names = null, $except = [])
	{
		$attributes = parent::getAttributes($names, $except);
		return $this->getRawAttributes($attributes);
	}
	
	public function getRawAttributes($attributes)
	{
		foreach($attributes as $k => $v){
			if(is_array($v)){
				$attributes[$k] = $this->getRawAttributes($v);
			}
		}
		return $attributes;
	}
}