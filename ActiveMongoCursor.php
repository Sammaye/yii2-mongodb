<?php

namespace sammaye\mongoyii2;

use yii\base\Object;
use sammaye\mongoyii2\MongoCursor;

class ActiveMongoCursor extends MongoCursor implements \Iterator
{
	public function current()
	{
		return $this->query->populate([$this->cursor->current()])[0];
	}
}