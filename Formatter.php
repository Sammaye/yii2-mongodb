<?php

namespace sammaye\mongoyii;

use Yii;
use yii\helpers\Html;
use yii\i18n\Formatter as BaseFormatter;

class Formatter extends BaseFormatter
{
    public function asDate($value, $format = null)
    {
		if($value instanceof \MongoDate){
			$value = $value->sec;
		}
		return parent::asDate($value, $format);
    }
}