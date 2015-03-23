<?php

namespace common\components;

use Yii;

class Mongo
{
	public static function date(\MongoDate $mongoDate)
	{
		return mktime(
			0, 
			0, 
			0, 
			date('m', $mongoDate->sec), 
			date('d', $mongoDate->sec), 
			date('Y', $mongoDate->sec)
		);
	}
}