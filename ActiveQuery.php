<?php

namespace sammaye\mongoyii;

use Yii;
use yii\mongodb\ActiveQuery as BaseActiveQuery;
use sammaye\mongoyii\ActiveMongoCursor;

class ActiveQuery extends BaseActiveQuery
{
	public $options;

	public function addOption($key, $value)
	{
		$this->options[$key] = $value;
	}

	public function each()
	{
		return Yii::createObject([
			'class' => ActiveMongoCursor::className(),
			'query' => $this
		]);
	}

	public function raw()
	{
		return $this->buildCursor();
	}

	/**
	 * Builds the Mongo cursor for this query.
	 * @param Connection $db the database connection used to execute the query.
	 * @return \MongoCursor mongo cursor instance.
	 */
	protected function buildCursor($db = null)
	{
		$cursor = $this->getCollection($db)->find($this->composeCondition(), $this->composeSelectFields());
		if (!empty($this->orderBy)) {
			$cursor->sort($this->composeSort());
		}
		$cursor->limit($this->limit);
		$cursor->skip($this->offset);

		foreach($this->options as $k => $v){
			$cursor->addOption($k, $v);
		}

		return $cursor;
	}
}