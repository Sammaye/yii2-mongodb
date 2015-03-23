<?php

namespace common\components;

use Yii;
use common\components\MongoCursor;

class Query extends \yii\mongodb\Query
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
	
	/**
	 * Builds the Mongo cursor for this query.
	 * @param Connection $db the database connection used to execute the query.
	 * @return \MongoCursor mongo cursor instance.
	 */
	public function buildCursor($db = null)
	{
		$cursor = $this->getCollection($db)->find($this->composeCondition(), $this->composeSelectFields());
		if (!empty($this->orderBy)) {
			$cursor->sort($this->composeSort());
		}
		$cursor->limit($this->limit);
		$cursor->skip($this->offset);
	
		return $cursor;
	}
	
	
	/**
	 * Composes select fields from raw [[select]] value.
	 * @return array select fields.
	 */
	private function composeSelectFields()
	{
		$selectFields = [];
		if (!empty($this->select)) {
			foreach ($this->select as $fieldName => $inc) {
				if(is_string($fieldName)){
					$selectFields[$fieldName] = $inc;
				}else{
					$selectFields[$inc] = true;
				}
			}
		}
		return $selectFields;
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
	
	/**
	 * Performs 'findAndModify' query and returns a single row of result.
	 * @param array $update update criteria
	 * @param array $options list of options in format: optionName => optionValue.
	 * @param Connection $db the Mongo connection used to execute the query.
	 * @return array|null the original document, or the modified document when $options['new'] is set.
	 */
	public function modify($update, $options = [], $db = null)
	{
		$collection = $this->getCollection($db);
		if (!empty($this->orderBy)) {
			$options['sort'] = $this->composeSort();
		}
	
		return $collection->findAndModify($this->composeCondition(), $update, $this->composeSelectFields(), $options);
	}
}