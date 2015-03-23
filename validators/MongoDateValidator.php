<?php

namespace common\components;

use Yii;
use yii\validators\DateValidator;

class MongoDateValidator extends DateValidator
{
	public $useMongoDate = true;
	
	public function validateAttribute($object, $attribute)
	{
		$value = $object->$attribute;
		$timestamp = $this->parseDateValue($value);
		if ($timestamp === false) {
			$this->addError($object, $attribute, $this->message, []);
		} elseif ($this->useMongoDate) {
			$object->$attribute = $timestamp;
		} elseif ($this->timestampAttribute !== null) {
			$object->{$this->timestampAttribute} = $timestamp;
		}
	}
	
	protected function parseDateValue($value)
	{
		if($value instanceof \MongoDate){
			return $value;
		}
		
		$ts = parent::parseDateValue($value);
		
		if(!$ts){
			return false;
		}
		
		if($this->useMongoDate){
			return new \MongoDate($ts);
		}else{
			return $ts;
		}
	}
}