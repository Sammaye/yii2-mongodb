<?php

namespace sammaye\mongoyii;

use yii\base\Object;
use sammaye\mongoyii\MongoCursor;

class ActiveMongoCursor extends MongoCursor implements \Iterator
{
	public function current()
	{
		return $this->query->populate([$this->cursor->current()])[0];
	}
}