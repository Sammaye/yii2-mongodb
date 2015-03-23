<?php

namespace common\components;

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