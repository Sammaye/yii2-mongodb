<?php

namespace common\components;

use yii\base\Object;
use common\components\MongoCursor;

class ActiveMongoCursor extends MongoCursor implements \Iterator
{
	public function current()
	{
		return $this->query->populate([$this->cursor->current()])[0];
	}
}