<?php

namespace sammaye\mongoyii;

use Yii;
use yii\helpers\Html;

use MongoDB\BSON\UTCDateTime;

class Formatter extends \yii\i18n\Formatter
{
    public function asDate($value, $format = null)
    {
        if ($value instanceof UTCDateTime) {
            $value = $value->toDateTime();
        }
        return parent::asDate($value, $format);
    }
}