<?php

namespace common\components;

use Yii;
use ArrayObject;
use yii\base\InvalidConfigException;
use yii\mongodb\ActiveRecord as MongoActiveRecord;
use common\components\Subdocument;
use common\components\ActiveQuery;
use common\components\Validator;

class ActiveRecord extends MongoActiveRecord
{
	private $_subdocuments = [];
	
	public function init()
	{
		return parent::init();
	}
	
	public static function find()
	{
		return Yii::createObject(ActiveQuery::className(), [get_called_class()]);
	}
	
	public function subdocuments()
	{
		return [];
	}
	
	/**
	 * Creates validator objects based on the validation rules specified in [[rules()]].
	 * Unlike [[getValidators()]], each time this method is called, a new list of validators will be returned.
	 * @return ArrayObject validators
	 * @throws InvalidConfigException if any validation rule configuration is invalid
	 */
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
			}elseif($v instanceof Subdocument){
				$attributes[$k] = $v->getRawAttributes();
			}
		}
		return $attributes;
	}
}