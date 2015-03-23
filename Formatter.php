<?php

namespace sammaye\mongoyii2;

use Yii;
use yii\helpers\Html;

class Formatter extends \yii\i18n\Formatter
{
    public function asDate($value, $format = null)
    {
		if($value instanceof \MongoDate){
			$value = $value->sec;
		}
		return parent::asDate($value, $format);
    }
}