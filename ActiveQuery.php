<?php

namespace common\components;

use Yii;
use common\components\ActiveMongoCursor;

class ActiveQuery extends \yii\mongodb\ActiveQuery
{
	public function each()
	{
		return Yii::createObject([
			'class' => ActiveMongoCursor::className(),
			'query' => $this
		]);
	}
	
	public function buildCursor($db = null){
		return parent::buildCursor($db);
	}
	
	public function raw()
	{
		return $this->buildCursor();
	}
}