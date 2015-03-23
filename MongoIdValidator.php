<?php

namespace common\components;

use Yii;
use yii\validators\Validator;

class MongoIdValidator extends Validator
{
	/**
	 * @inheritdoc
	 */
	public function validateAttribute($object, $attribute)
	{
		$value = $object->$attribute;

		if (!($id = $this->parseIdValue($value))) {
			$this->addError($object, $attribute, $this->message, []);
		} else {
			$object->$attribute = $id;
		}
	}
	
	/**
	 * @inheritdoc
	 */
	protected function validateValue($value)
	{
		return $this->parseIdValue($value) === false ? [$this->message, []] : null;
	}
	
	
	protected function parseIdValue($value)
	{
		if($value instanceof \MongoId){
			return $value;
		}
		$id = null;
		try{
			$id = new \MongoId($value);
		}catch(\Exception $e){
			return false;
		}
		if(!$id){
			return false;
		}
		return $id;
	}
}