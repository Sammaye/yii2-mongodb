<?php

namespace sammaye\mongoyii2;

use Yii;
use yii\mongodb\Query as BaseQuery;
use sammaye\mongoyii2\MongoCursor;

class Query extends BaseQuery
{
	public function each()
	{
		return Yii::createObject([
			'class' => MongoCursor::className(),
			'query' => $this
		]);
	}
	
	public function raw()
	{
		return $this->buildCursor();
	}
	
	public function buildCursor()
	{
		return parent::buildCursor();
	}
}