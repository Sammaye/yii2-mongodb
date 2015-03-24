<?php

namespace sammaye\mongoyii;

use Yii;
use yii\mongodb\Query as BaseQuery;
use sammaye\mongoyii\MongoCursor;

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
	
	public function buildCursor($db = null)
	{
		return parent::buildCursor($db);
	}
}