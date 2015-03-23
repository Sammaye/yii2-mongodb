<?php

namespace sammaye\mongoyii2;

use Yii;
use yii\mongodb\ActiveQuery as BaseActiveQuery;
use sammaye\mongoyii2\ActiveMongoCursor;

class ActiveQuery extends BaseActiveQuery
{
	public function each()
	{
		return Yii::createObject([
			'class' => ActiveMongoCursor::className(),
			'query' => $this
		]);
	}
	
	public function buildCursor($db = null)
	{
		return parent::buildCursor($db);
	}
	
	public function raw()
	{
		return $this->buildCursor();
	}
}