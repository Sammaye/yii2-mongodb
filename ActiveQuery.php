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

	/**
	 * Composes condition from raw [[where]] value.
	 * @return array conditions.
	 */
	private function composeCondition()
	{
		if ($this->where === null) {
			return [];
		} else {
			return $this->where;
		}
	}

	/**
	 * Composes select fields from raw [[select]] value.
	 * @return array select fields.
	 */
	private function composeSelectFields()
	{
		$selectFields = [];
		if (!empty($this->select)) {
			foreach ($this->select as $key => $value) {
				if (is_numeric($key)) {
					$selectFields[$value] = true;
				} else {
					$selectFields[$key] = $value;
				}
			}
		}
		return $selectFields;
	}

	/**
	 * Composes sort specification from raw [[orderBy]] value.
	 * @return array sort specification.
	 */
	private function composeSort()
	{
		$sort = [];
		foreach ($this->orderBy as $fieldName => $sortOrder) {
			$sort[$fieldName] = $sortOrder === SORT_DESC ? \MongoCollection::DESCENDING : \MongoCollection::ASCENDING;
		}
		return $sort;
	}
}