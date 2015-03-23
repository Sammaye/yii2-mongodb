<?php

namespace sammaye\mongoyii2;

use yii\base\Object;

class MongoCursor extends Object implements \Iterator
{
	public $cursor;
	/**
	 * @var \common\components\ActiveQuery
	 */
	public $query;


	public function __call($method, $params)
	{
		return call_user_func([$this->cursor, $method], $params);
	}

	public function init()
	{
		$this->cursor = $this->query->buildCursor();
		parent::init();
	}
	
	public function rewind()
	{
		$this->cursor->rewind();
	}
	
	public function current()
	{
		return $this->cursor->current();
	}
	
	public function key()
	{
		return $this->cursor->key();
	}
	
	public function next()
	{
		$this->cursor->next();
	}
	
	public function valid()
	{
		return $this->cursor->valid();
	}
}